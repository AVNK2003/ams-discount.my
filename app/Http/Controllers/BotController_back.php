<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\Update;

class BotController_back extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $bot = Bot::all();
        return view('admin.bot.index', compact('bot'));
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function setWebhook(Request $request): \Illuminate\Http\RedirectResponse
    {
        $bot = new Api($request->token);
//        $bot = new Bot($request->token);
        if ($bot->setWebhook(['url' => $request->url.'bot'])) {
            $token = Bot::firstOrNew(['name' => 'token']);
            $token->value = $request->token;
            $token->save();

            return redirect()->route('bot')->with('success', 'Webhook установлен');
        }
        return redirect()->route('bot')->with('error', 'Webhook НЕ установлен');
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

        $msg_send = [
            'chat_id' => $update->getChat()->id,
            'parse_mode' => 'HTML',
//            'text' => null,
//            'reply_markup' => null,
//            'reply_to_message_id' => null,
//            'disable_web_page_preview' => false,
//            'disable_notification' => false,
        ];

        $bot->sendChatAction(['chat_id' => $msg_send['chat_id'], 'action' => 'typing']);

        if ($update->isType('callback_query')) {
            $msg_send = array_merge($msg_send, $this->callbackHandler($bot, $update, $settings));
        } elseif ($update->isType('message')) {
            $msg_send = array_merge($msg_send, $this->messageHandler($update));
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
    private function messageHandler(Update $update): array
    {
        $name = $update->getMessage()->getFrom()->getFirstName() ?? 'Уважаемый пользователь';
        $msg_in_text = $update->message->text;

        if ($msg_in_text == '/start') {
            return $this->startCommand();
        } elseif ($msg_in_text == '🗂 Раздел со скидками от «AMS Discount»') {
            return $this->mainMenu($name);
        } elseif ($msg_in_text == '🔄 Обратная связь') {
            return $this->feedback($name);
        } elseif ($msg_in_text == '⁉ Вопросы и ответы') {
            return $this->faq($name);
        } else {
            return ['text' => 'Такой команды не существует'];
        }
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function callbackHandler(Api $bot, Update $update, Collection $settings): array
    {
//        $cb = $update->getMessage();
        $data = $update->callbackQuery->data;
        $dataDecode = json_decode($data);
        $bot->answerCallbackQuery(['callback_query_id' => $update->callbackQuery->id]);

        if ($data == 'mainMenu') {
            return $this->mainMenu_cb($bot, $update);
        } elseif ($data == 'start') {
            return $this->startCommand();
        } elseif ($data == 'city') {
//            return ['text' => 'Город'];
            return $this->getCity($bot, $update);
        } elseif ($data == 'category') {
            return $this->getCategory($bot, $update);
        } elseif ($data == 'all' || isset(json_decode($data)->{'next'})) {
            return $this->allCompanies($bot, $update, $settings->where('name', 'per_page')->first()->value ?? 2);
        } else {
            return ['text' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)];
        }
    }

    /**
     * @throws \Exception
     */
    private function startCommand(): array
    {
        $keyboard = Keyboard::make()
            ->setResizeKeyboard(true)
            ->row(
                Keyboard::button(['text' => '🗂 Раздел со скидками от «AMS Discount»']),
            )
            ->row(
                Keyboard::button(['text' => '🔄 Обратная связь']),
                Keyboard::button(['text' => '⁉ Вопросы и ответы'])
            );
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
    private function mainMenu($name): array
    {
//        cache()->forget('main_menu');
        $keyboard = Keyboard::make()
            ->inline()
            ->row(
                Keyboard::inlineButton(['text' => 'Город', 'callback_data' => 'city']),
                Keyboard::inlineButton(['text' => 'Категория', 'callback_data' => 'category']),
            )
            ->row(
                Keyboard::inlineButton(['text' => 'Показать все организации', 'callback_data' => 'all']),
            );
        $msg = $name.cache()->remember('main_menu', 60 * 60 * 24 * 30, function () {
                return Bot::where('name', 'main_menu')->firstOrFail()->value;
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
    private function feedback(string $name): array
    {
        $msg = cache()->remember('bot_feedback', 60 * 60 * 24 * 30, function () {
            return Bot::where('name', 'feedback')->firstOrFail()->value;
        });
        return [
            'text' => $msg,
            'disable_web_page_preview' => true,
        ];
    }

    private function faq(string $name): array
    {
        $msg = '💡'.$name.', <a href="https://telegra.ph/Voprosy-i-otvety-na-chasto-zadavaemye-voprosy-12-22">здесь Вы найдёте ответы на часто задаваемые вопросы.</a>
 
Нажмите кнопку "Посмотреть" 👇для полного просмотра прямо в Telegram.';
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
    private function allCompanies(Api $bot, Update $update, $perPage): array
    {
        /*$companies = cache()->remember('companies', 60 * 60 * 24, function () {
            return Company::where('active', true)
                ->where('date_end', '>', now())
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        });*/

        $data = json_decode($update->callbackQuery->data);
        $currentPage = $data->{'next'} ?? 0;
        $nextPage = $currentPage * $perPage;
        $companiesCount = Company::where('active', 1)
            ->where('date_end', '>', now())
            ->count();

        $companies = Company::where('active', 1)
            ->where('date_end', '>', now())
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($nextPage)->take($perPage)->get();

        $msg = ['chat_id' => $update->getChat()->id];
        foreach ($companies as $company) {
            $img = $company->img ? ('/img/uploads/thumbnail/'.$company->img) : '/img/logo.png';
//            $bot->sendPhoto([
//                'chat_id' => $update->getChat()->id,
//                'photo' => asset($img),
//            ]);
            $msg = array_merge($msg, $this->makeCard($company));
            $bot->sendMessage($msg);
        }

        if ($currentPage < $companiesCount / $perPage - 1) {
            $keyboard = Keyboard::make()
                ->inline()
                ->row(
                    Keyboard::inlineButton([
                        'text' => 'Показать ещё '.$perPage, 'callback_data' => json_encode(['next' => $currentPage + 1])
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
                Keyboard::inlineButton(['text' => 'Город', 'callback_data' => 'city']),
                Keyboard::inlineButton(['text' => 'Категория', 'callback_data' => 'category']),
            )
            ->row(
                Keyboard::inlineButton(['text' => 'Показать все организации', 'callback_data' => 'all']),
            )
            ->row(
                Keyboard::inlineButton(['text' => 'Главное меню', 'callback_data' => 'start']),
            );

        return [
            'text' => 'Мы показали Вам все организации по вашему запросу',
            'reply_markup' => $keyboard,
        ];
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function getCity(Api $bot, Update $update): array
    {
        $citiesBtn = [];
        $cities = City::has('companies')->get();
        foreach ($cities as $city) {
            $citiesBtn[] = ['text' => $city->name, 'callback_data' => json_encode(['city' => $city->id])];
        }
        $keyboard = $this->makeKeyboard($citiesBtn);
        $this->editMessageKeyboard($bot, $update, $keyboard);
        return [];
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function mainMenu_cb(Api $bot, Update $update): array
    {
        $keyboard = Keyboard::make()
            ->inline()
            ->row(
                Keyboard::inlineButton(['text' => 'Город', 'callback_data' => 'city']),
                Keyboard::inlineButton(['text' => 'Категория', 'callback_data' => 'category']),
            )
            ->row(
                Keyboard::inlineButton(['text' => 'Показать все организации', 'callback_data' => 'all']),
            );
        $this->editMessageKeyboard($bot, $update, $keyboard);
        return [];
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function getCategory(Api $bot, Update $update): array
    {
        $categoriesBtn = [];
        $categories = Category::has('companies')->get();
        foreach ($categories as $category) {
            $categoriesBtn[] = [
                'text' => $category->name, 'callback_data' => json_encode(['$category' => $category->id])
            ];
        }
        $keyboard = $this->makeKeyboard($categoriesBtn);
        $this->editMessageKeyboard($bot, $update, $keyboard);
        return [];
    }

    private function makeKeyboard(array $arr): Keyboard
    {
        $btn = array_chunk($arr, 2);
        $btn[] = [['text' => 'Назад', 'callback_data' => 'mainMenu']];
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
}
//ngrok http  --host-header=ams-discount.my 80

//foreach ($companies as $company) {
//            foreach ($company->cities as $city) {
//                $cities[] = $city->name;
//            }
//        }
//        $cities = array_unique($cities);