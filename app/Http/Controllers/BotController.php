<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\TgBot\CallbackFunctions\AllQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\Update;

class BotController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $botUsers = BotUser::where('active', true)->count();
        return view('admin.bot.index', compact('botUsers'));
    }

    public function users()
    {
        $users = BotUser::all([
            'id', 'created_at', 'updated_at', 'user_id', 'user_name', 'first_name', 'last_name', 'active'
        ]);
        $title = 'Пользователи бота';
        return view('admin.bot.users', compact('users', 'title'));
    }

    public function setToken()
    {
        $token = Bot::where('name', 'token')->first();
        return view('admin.bot.token', compact('token'));
    }

    public function botSettings()
    {
        $settings = Bot::where('name', 'per_page')->get();
        return view('admin.bot.settings', compact('settings'));
    }

    public function botMessages()
    {
        $messages = Bot::whereIn('name', ['command_start', 'main_menu', 'feedback', 'faq'])->get();
        return view('admin.bot.messages', compact('messages'));
    }

    /**
     * @throws \Exception
     */
    public function updateBotSettings(Request $request): \Illuminate\Http\RedirectResponse
    {
        $arr = $request->except('_method', '_token');
        $data = [];
//        $replaceHtml = ['<p>', '</p>'];

        foreach ($arr as $name => $value) {
//            $value = str_replace($replaceHtml, '', $value);
            $data[] = ['name' => $name, 'value' => $value];
        }

        Bot::upsert($data, ['name'], ['value']); //вставка в базу данных из массива

        cache()->flush();
        return redirect()->back()->with('success', 'Настройки сохранены');
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function setWebhook(Request $request): \Illuminate\Http\RedirectResponse
    {
        $bot = new Api($request->token);

        if (!$bot->setWebhook(['url' => $request->url.'bot'])) {
            return redirect()->route('bot')->with('error', 'Webhook НЕ установлен');
        }

        $token = Bot::firstOrNew(['name' => 'token']);
        $token->value = $request->token;
        $token->save();

        return redirect()->route('bot')->with('success', 'Webhook установлен');
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     * @throws \Exception
     */
    public function runBot(): string
    {
        $settings = Bot::where('active', 1)->get();

        $bot = new Api($settings->where('name', 'token')->first()->value ?? null);
        if (!$bot) {
            abort(404, 'Error token');
        }

        $update = $bot->getWebhookUpdate();

//        $bot->sendMessage([
//            'chat_id' => $update->getChat()->id,
//            'text' => json_encode($update, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
//        ]);

        if ($update->getChat()->type === 'private') {
            BotUser::updateOrCreate(['user_id' => $update->getChat()->id], [
                'user_name' => $update->getChat()->username,
                'first_name' => $update->getChat()->firstName,
                'last_name' => $update->getChat()->lastName,
            ])->touch();
        }

        $msg_send = [
            'chat_id' => $update->getChat()->id,
            'parse_mode' => 'HTML',
//            'text' => null,
//            'reply_markup' => null,
//            'reply_to_message_id' => null,
//            'disable_web_page_preview' => false,
//            'disable_notification' => false,
        ];

//        $msg_send = array_merge($msg_send, $this->debug($update));
//        $bot->sendMessage($msg_send);
//        return 'ok';

        if ($update->isType('callback_query')) {
            $msg_send = array_merge($msg_send, $this->callbackHandler($bot, $update, $settings));
        }

        if ($update->isType('message')) {
            $msg_send = array_merge($msg_send, $this->messageHandler($bot, $update));
        }


//        $msg_send = array_merge($msg_send, $this->debug($update));
        if (isset($msg_send['text'])) {
            $bot->sendMessage($msg_send);
        }

        return 'ok';
    }

    private function debug($update): array
    {
        return ['text' => json_encode($update, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),];
    }

    /**
     * @throws \Exception
     */
    private function messageHandler(Api $bot, Update $update): array
    {
        $bot->sendChatAction(['chat_id' => $update->getChat()->id, 'action' => 'typing']);

        $name = $update->getMessage()->getFrom()->getFirstName() ?? 'Уважаемый пользователь';
        $msg_in_text = $update->message->text;

        if ($msg_in_text == '/start' || $msg_in_text == 'Отмена') {
            return $this->startCommand();
        }

        if ($msg_in_text == '🗂 Раздел со скидками от «AMS Discount»') {
            return $this->mainMenu($name);
        }

        if ($msg_in_text == '🔄 Обратная связь') {
            return $this->feedback($name);
        }

        if ($msg_in_text == '⁉ Вопросы и ответы') {
            return $this->faq($name);
        }

        if ($update->getMessage()->contact) {
            $user = BotUser::where('user_id', $update->getChat()->id)->first();

            if ($user->current_action === 'sendContact') {
                $message = $bot->forwardMessage([
                    'chat_id' => $user->company_telegram_id,
                    'from_chat_id' => $update->getChat()->id,
                    'message_id' => $update->getMessage()->messageId,
                ]);

                $bot->sendMessage([
                    'chat_id' => $user->company_telegram_id,
                    'text' => 'Данный клиент запросил связи с представителем компании',
                    'reply_to_message_id' => $message->messageId,
                ]);

                $user->update([
                    'current_action' => null,
                    'company_telegram_id' => null,
                ]);

                return [
                    'text' => 'Контакт отправлен представителю компании. Скоро он свяжется с Вами',
                    'reply_markup' => $this->standartKeyboard(),
                ];
            }
        }

        return ['text' => 'Такой команды не существует. Нажмите /start, чтобы восстановить нижнее меню!'];
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     * @throws \Exception
     */
    private function callbackHandler(Api $bot, Update $update, Collection $settings): array
    {
//        $cb = $update->getMessage();
        $data = $update->callbackQuery->data;
        $name = $update->getMessage()->getChat()->getFirstName() ?? 'Уважаемый пользователь';
//        $dataDecode = json_decode($data);
        $bot->answerCallbackQuery(['callback_query_id' => $update->callbackQuery->id]);

        if ($data == 'mainMenu') {
            return $this->mainMenu($name);
        }

        if ($data == 'start') {
            return $this->startCommand();
        }

        $answer = new AllQuery($bot, $data);

        return $answer->apply();

//        if ($data == 'mainMenu') {
//            return $this->mainMenu($name);
//        } elseif ($data == 'start') {
//            return $this->startCommand();
//        } elseif ($data == 'showCities') {
//            return $this->showCities($bot, $update);
//        } elseif (str_contains($data, 'city-')) {
//            return $this->setCity($bot, $update, $data);
//        } elseif ($data == 'showCategories') {
//            return $this->showCategories($bot, $update);
//        } elseif (str_contains($data, 'category-')) {
//            return $this->setCategory($bot, $update, $data);
//        } elseif ($data == 'all' || str_contains($data, 'showCompanies')) {
//            return $this->allCompanies($bot, $update, $settings->where('name', 'per_page')->first()->value ?? 2);
//        } else {
//            return ['text' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)];
//        }
    }

    private function standartKeyboard(): Keyboard
    {
        return Keyboard::make()
            ->setResizeKeyboard(true)
            ->row(
                Keyboard::button(['text' => '🗂 Раздел со скидками от «AMS Discount»']),
            )
            ->row(
                Keyboard::button(['text' => '🔄 Обратная связь']),
                Keyboard::button(['text' => '⁉ Вопросы и ответы'])
            );
    }

    /**
     * @throws \Exception
     */
    private function startCommand(): array
    {
        $keyboard = $this->standartKeyboard();
        $msg = cache()->remember('commandStart', 60 * 60 * 24 * 30, function () {
            return Bot::where('name', 'command_start')->firstOrFail()->value;
        });

        return [
            'text' => $msg,
            'reply_markup' => $keyboard,
            'disable_web_page_preview' => true,
        ];
    }

    /**
     * @throws \Exception
     */
    private function mainMenu(string $name): array
    {
//        cache()->forget('main_menu');
        $keyboard = Keyboard::make()
            ->inline()
            ->row(
                Keyboard::inlineButton(['text' => 'Город', 'callback_data' => 'show-city-0']),
                Keyboard::inlineButton(['text' => 'Категория', 'callback_data' => 'show-0-category']),
            )
            ->row(
                Keyboard::inlineButton(['text' => 'Показать все организации', 'callback_data' => 'show-all-all']),
            );
        $msg = cache()->remember('main_menu', 60 * 60 * 24 * 30, function () {
            return Bot::where('name', 'main_menu')->firstOrFail()->value;
        });

        $msg = str_replace('{$name}', $name, $msg);

        return [
            'text' => $msg,
            'reply_markup' => $keyboard,
            'disable_web_page_preview' => true,
        ];
    }

    /**
     * @throws \Exception
     */
    private function feedback(string $name): array
    {
        $msg = cache()->remember('bot_feedback', 60 * 60 * 24 * 30, function () {
            return Bot::where('name', 'feedback')->firstOrFail()->value;
        });

        $msg = str_replace('{$name}', $name, $msg);

        return [
            'text' => $msg,
            'disable_web_page_preview' => true,
        ];
    }

    private function faq(string $name): array
    {
        $msg = cache()->remember('bot_faq', 60 * 60 * 24 * 30, function () {
            return Bot::where('name', 'faq')->firstOrFail()->value;
        });

        $msg = str_replace('{$name}', $name, $msg);

        return [
            'text' => $msg,
        ];
    }

    private function makeCard(Company $company): array
    {
        $img = $company->img ? ('https://0e08646159c5.ngrok.io/img/uploads/thumbnail/'.$company->img) : 'https://0e08646159c5.ngrok.io/img/logo.png';
        $text = "<a href='{$img}'>✅</a> {$company->title}

💡 Скидка \"AMS DISCOUNT\" - {$company->discount}
🕐 {$company->working_hours}
🌏 <a href='{$company->site}'>{$company->address}</a>
📞 {$company->tel}


 Максимальные скидки только для пользователей @amsdiscountbot";
//        $keyboard = Keyboard::make()
//            ->inline()
//            ->row(
//                Keyboard::inlineButton(['text' => 'Город', 'callback_data' => 'city']),
//            );

        return [
            'text' => $text,
//            'reply_markup' => $keyboard,
            'parse_mode' => 'HTML'
        ];
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function allCompanies(Api $bot, Update $update, $perPage, $city = 0, $category = 0): array
    {
        /*$companies = cache()->remember('companies', 60 * 60 * 24, function () {
            return Company::where('active', true)
                ->where('date_end', '>', now())
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        });*/

        $data = $update->callbackQuery->data;
        $currentPage = 0;

        if ($data == 'all') {
            $companiesCount = Company::where('active', 1)
                ->where('date_end', '>', now())
                ->count();
        }

        if (str_contains($data, 'showCompanies')) {
            // Приходит строка вида: showCompaniesCity-4-Categories-5
            $regEx = '/City-\d+/';
            if (preg_match($regEx, $data, $matches)) { // Если находит City-4*, то забираем ID
                $city = substr(strrchr($matches[0], '-'), 1);
            }
            $regEx = '/Category-\d+/';
            if (preg_match($regEx, $data, $matches)) {
                $category = substr(strrchr($matches[0], '-'), 1);
            }
            if ($city && $category) {
                $companiesCount = $this->countInCitiesCategories($city, $category);
            } elseif ($city) {
                $companiesCount = $this->countInCities($city);
            } elseif ($category) {
                $companiesCount = $this->countInCategories($category);
            }
        }

        if (strpos($data, 'next-') !== false) {
            \Log::info('$data: '.$data);
            \Log::info('strpos "next-" in $data: '.strpos($data, 'next-'));
            $currentPage = substr($data, strpos($data, 'next-') + 5) ?? 0;
            \Log::info('$currentPage: '.$currentPage);
        }

//        $nextPage = $currentPage * $perPage;

        $companies = $this->getCompanies($perPage, $currentPage, $city, $category);

        $msg = ['chat_id' => $update->getChat()->id];
        foreach ($companies as $company) {
            $company->img ? ('/img/uploads/thumbnail/'.$company->img) : '/img/logo.png';
//            $bot->sendPhoto([
//                'chat_id' => $update->getChat()->id,
//                'photo' => asset($img),
//            ]);
            $msg = array_merge($msg, $this->makeCard($company));
            $bot->sendMessage($msg);
        }

        \Log::info($currentPage.' < '.$companiesCount.' / '.$perPage);
        if ($currentPage < $companiesCount / $perPage - 1) {
            $keyboard = Keyboard::make()
                ->inline()
                ->row(
                    Keyboard::inlineButton([
                        'text' => 'Показать ещё '.$perPage,
                        'callback_data' => 'showCompaniesCity-'.$city.'-Category-'.$category.'-next-'.($currentPage + 1)
//                            json_encode(['next' => $currentPage + 1])
                    ]),
                )
                ->row(
                    Keyboard::inlineButton(['text' => 'Назад', 'callback_data' => 'mainMenu']),
                    Keyboard::inlineButton(['text' => 'Главное меню', 'callback_data' => 'start']),
                );
//        $msg = $name.cache()->remember('main_menu', 60 * 60 * 24 * 30, function () {
//                return Bot::where('name', 'main_menu')->firstOrFail()->value;
//            });
            return [
                'text' => ($currentPage * $perPage + 1).'-'.($currentPage * $perPage + $perPage).' из '.$companiesCount.' найденных организаций',
                'reply_markup' => $keyboard,
            ];
        }

        $keyboard = Keyboard::make()
            ->inline()
            ->row(
                Keyboard::inlineButton(['text' => 'Город', 'callback_data' => 'showCities']),
                Keyboard::inlineButton(['text' => 'Категория', 'callback_data' => 'showCategories']),
            )
            ->row(
                Keyboard::inlineButton(['text' => 'Показать все организации', 'callback_data' => 'all']),
            )
            ->row(
                Keyboard::inlineButton(['text' => 'В начало', 'callback_data' => 'mainMenu']),
            );

        return [
            'text' => 'Мы показали Вам все организации по вашему запросу',
            'reply_markup' => $keyboard,
        ];
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function showCities(Api $bot, Update $update): array
    {
        $keyboard = $this->makeKeyboard('city-', $this->getCities());
        $this->editMessageKeyboard($bot, $update, $keyboard);
        return [];
    }

    private function setCity(Api $bot, Update $update, $data)
    {
        $city = substr(strrchr($data, '-'), 1);
        $butons = [['text' => 'Показать все организации в городе', 'callback_data' => 'showCompaniesCity-'.$city]];
        $keyboard = $this->makeKeyboard('showCompaniesCity-'.$city.'-Category-', $this->getCategories(), $butons);
        $this->editMessageKeyboard($bot, $update, $keyboard);
        return [];
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function showCategories(Api $bot, Update $update): array
    {
        $keyboard = $this->makeKeyboard('category-', $this->getCategories());
        $this->editMessageKeyboard($bot, $update, $keyboard);
        return [];
    }

    private function setCategory(Api $bot, Update $update, $data)
    {
        $category = substr(strrchr($data, '-'), 1);
        $butons = [
            [
                'text' => 'Показать организации этой категории во всех городах',
                'callback_data' => 'showCompaniesCategory-'.$category
            ]
        ];
        $keyboard = $this->makeKeyboard('showCompaniesCategory-'.$category.'-City-', $this->getCities(), $butons);
        $this->editMessageKeyboard($bot, $update, $keyboard);
        return [];
    }

    private function makeKeyboard(string $prefix, Collection $items, array $addButtons = []): Keyboard
    {
        $arr = [];
        foreach ($items as $item) {
            $arr[] = [
                'text' => $item->name, 'callback_data' => $prefix.$item->id
            ];
        }

        $btn = array_chunk($arr, 2);
        if ($addButtons) {
            $btn[] = $addButtons;
        }
        $btn[] = [['text' => 'В начало', 'callback_data' => 'mainMenu']];
        $keyboard = ["inline_keyboard" => $btn];
        return Keyboard::make($keyboard);
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function editMessageKeyboard(Api $bot, Update $update, Keyboard $keyboard)
    {
        $bot->editMessageReplyMarkup([
            'chat_id' => $update->getChat()->id,
            'message_id' => $update->getMessage()->messageId,
            'reply_markup' => $keyboard,
        ]);
    }

    private function getCategories()
    {
        return Category::has('companies')->orderBy('name')->get();
    }

    private function getCities()
    {
        return City::has('companies')->orderBy('name')->get();
    }

    private function countInCitiesCategories($city, $category)
    {
        return Company::where('active', true)
            ->where('date_end', '>', now())
            ->whereHas('cities', function (Builder $query) use ($city) {
                $query->where('id', $city);
            })
            ->whereHas('categories', function (Builder $query) use ($category) {
                $query->where('id', $category);
            })
            ->count();
    }

    private function countInCities($city)
    {
        return Company::where('active', true)
            ->where('date_end', '>', now())
            ->whereHas('cities', function (Builder $query) use ($city) {
                $query->where('id', $city);
            })
            ->count();
    }

    private function countInCategories($category)
    {
        return Company::where('active', true)
            ->where('date_end', '>', now())
            ->whereHas('categories', function (Builder $query) use ($category) {
                $query->where('id', $category);
            })
            ->count();
    }

    private function getCompanies($perPage = 0, $skip = 0, $city = '', $category = '')
    {
        if ($city && $category) {
            return Company::where('active', true)
                ->where('date_end', '>', now())
                ->whereHas('cities', function (Builder $query) use ($city) {
                    $query->where('id', $city);
                })
                ->whereHas('categories', function (Builder $query) use ($category) {
                    $query->where('id', $category);
                })
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'desc')
                ->skip($skip)->take($perPage)->get();
        }

        if ($city) {
            return Company::where('active', true)
                ->where('date_end', '>', now())
                ->whereHas('cities', function (Builder $query) use ($city) {
                    $query->where('id', $city);
                })
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'desc')
                ->skip($skip)->take($perPage)->get();
        }

        if ($category) {
            return Company::where('active', true)
                ->where('date_end', '>', now())
                ->whereHas('categories', function (Builder $query) use ($category) {
                    $query->where('id', $category);
                })
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'desc')
                ->skip($skip)->take($perPage)->get();
        }

        return Company::where('active', 1)
            ->where('date_end', '>', now())
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($skip)->take($perPage)->get();;
    }
}
//.\ngrok http  --host-header=ams-discount.my 80
