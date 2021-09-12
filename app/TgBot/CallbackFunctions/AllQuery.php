<?php

namespace App\TgBot\CallbackFunctions;

use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;

class AllQuery extends QueryController
{
    private function getCategories()
    {
        return Category::has('companies')->orderBy('name')->get();
    }

    private function getCities()
    {
        return City::has('companies')->orderBy('name')->get();
    }

    protected function sendContact($data): array
    {
        BotUser::where('user_id', $data['user_id'])->update([
            'current_action' => 'sendContact',
            'company_telegram_id' => $data['telegram_id'],
        ]);
        $keyboard = Keyboard::make()
            ->row(Keyboard::button([
                'text' => 'Отправить контакт',
                'request_contact' => true,
            ])
            )
            ->row(Keyboard::button([
                'text' => 'Отмена',
            ])
            );
        $keyboard['resize_keyboard'] = true;
        return [
            'text' => "💡 {$data['user_name']}, если вы хотите, чтобы с Вами связался представитель компании, то отправьте свой контакт, нажав кнопку ниже",
            'reply_markup' => $keyboard,
        ];
//        $msg = ['chat_id' => $this->bot->getWebhookUpdate()->getChat()->id, 'text' => 'Отправляю контакт'];
//        $this->bot->sendMessage($msg);
    }

    /**
     * @throws TelegramSDKException
     */
    protected function show($data): array
    {
        //Начинаю с выбора города
        if ($data['city'] == 'city') {
            return $this->showCities();
        }

        //Начинаю с выбора категории
        if ($data['category'] == 'category') {
            return $this->showCategories();
        }

        //Показываю список категорий для города {$data['city']}
        if ($data['city'] && !$data['category']) {
            return $this->showCategories($data['city']);
        }

        //Показываю список городов для категории {$data['category']}
        if (!$data['city'] && $data['category']) {
            return $this->showCities($data['category']);
        }

        if ($data['city'] || $data['category']) {
            return $this->prepareCompanies($data['city'], $data['category'], $data['page']);
//            return ['text' => "Показываю организации для города {$data['city']} и категории {$data['category']}"];
        }

        return [
            'text' => "Не смог разобрать что показать: ".json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        ];
    }

    /**
     * @throws TelegramSDKException
     */
    private function showCities($category = 0): array
    {
        $buttons = [];
        foreach ($this->getCities() as $city) {
            $buttons[] = [
                'text' => $city->name, 'callback_data' => 'show-'.$city->id.'-'.$category
            ];
        }

        $buttons = array_chunk($buttons, 2);

        if ($category) {
            $buttons[] = [['text' => 'Показать все организации в данной категории', 'callback_data' => 'show-all-'.$category]];
        }

        $keyboard = $this->makeKeyboard($buttons);
        $this->editMessageKeyboard($keyboard);
        return [];
    }

    /**
     * @throws TelegramSDKException
     */
    private function showCategories($city = 0): array
    {
        $buttons = [];
        foreach ($this->getCategories() as $category) {
            $buttons[] = [
                'text' => $category->name, 'callback_data' => 'show-'.$city.'-'.$category->id
            ];
        }

        $buttons = array_chunk($buttons, 2);

        if ($city) {
            $buttons[] = [['text' => 'Показать все организации в городе', 'callback_data' => 'show-'.$city.'-all']];
        }

        $keyboard = $this->makeKeyboard($buttons);
        $this->editMessageKeyboard($keyboard);
        return [];
    }

    /**
     * @throws TelegramSDKException
     * @throws \Exception
     */
    private function prepareCompanies($city_id, $category_id, $currentPage): array
    {
        $this->bot->sendChatAction(['chat_id' => $this->bot->getWebhookUpdate()->getChat()->id, 'action' => 'typing']);

        $perPage = cache()->remember('per_page', 60 * 60 * 24 * 30, function () {
            return Bot::where('name', 'per_page')->firstOrFail()->value;
        });

        $skip = $perPage * $currentPage;

        $companies = $this->getCompanies($city_id, $category_id, $perPage, $skip);

//        return [
//            'text' => "объект Companies: ".json_encode($companies['total'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
//            ];

        $msg = ['chat_id' => $this->bot->getWebhookUpdate()->getChat()->id];

        foreach ($companies as $company) {
            if (!is_subclass_of($company, 'Illuminate\Database\Eloquent\Model')) {
                continue;
            }

            $company->img
                ? ('/img/uploads/thumbnail/'.$company->img)
                : '/img/logo.png';

            $msg = array_merge($msg, $this->makeCard($company));
            $this->bot->sendMessage($msg);
        }

        if ($currentPage < $companies['total'] / $perPage - 1) {
            $keyboard = Keyboard::make()
                ->inline()
                ->row(
                    Keyboard::inlineButton([
                        'text' => 'Показать ещё '.$perPage, 'callback_data' => 'show-'.$city_id.'-'.$category_id.'-'.($currentPage + 1)
//                            json_encode(['next' => $currentPage + 1])
                    ]),
                )
                ->row(
                    Keyboard::inlineButton(['text' => 'Назад', 'callback_data' => 'mainMenu']),
                    Keyboard::inlineButton(['text' => 'Главное меню', 'callback_data' => 'start']),
                );

            return [
                'text' => ($currentPage * $perPage + 1).'-'.($currentPage * $perPage + $perPage).' из '.$companies['total'].' найденных организаций',
                'reply_markup' => $keyboard,
            ];
//            $buttons[] = [['text' => 'Показать все организации в городе', 'callback_data' => 'show-'.$city_id.'-all']];
        }

        $keyboard = Keyboard::make()
            ->inline()
            ->row(
                Keyboard::inlineButton(['text' => 'Город', 'callback_data' => 'show-city-0']),
                Keyboard::inlineButton(['text' => 'Категория', 'callback_data' => 'show-0-category']),
            )
            ->row(
                Keyboard::inlineButton(['text' => 'Показать все организации', 'callback_data' => 'show-all-all']),
            )
            ->row(
                Keyboard::inlineButton(['text' => 'В начало', 'callback_data' => 'mainMenu']),
            );

        return [
            'text' => 'Мы показали Вам все организации по вашему запросу',
            'reply_markup' => $keyboard,
        ];
    }

    private function getCompanies($city_id, $category_id, $perPage, $skip)
    {
        if ($city_id == 'all' && $category_id == 'all') {
            $count = Company::where('active', 1)
                ->where('date_end', '>', now())->count();
            return Company::where('active', 1)
                ->where('date_end', '>', now())
                ->orderByDesc('priority')->orderByDesc('created_at')
                ->skip($skip)->take($perPage)->get()->put('total', $count);
        }

        if ($city_id == 'all' && $category_id) {
            $count = Company::where('active', true)
                ->where('date_end', '>', now())
                ->whereHas('categories', function (Builder $query) use ($category_id) {
                    $query->where('id', $category_id);
                })->count();
            return Company::where('active', true)
                ->where('date_end', '>', now())
                ->whereHas('categories', function (Builder $query) use ($category_id) {
                    $query->where('id', $category_id);
                })
                ->orderByDesc('priority')->orderByDesc('created_at')
                ->skip($skip)->take($perPage)->get()->put('total', $count);
        }

        if ($city_id && $category_id == 'all') {
            $count = Company::where('active', true)
                ->where('date_end', '>', now())
                ->whereHas('cities', function (Builder $query) use ($city_id) {
                    $query->where('id', $city_id);
                })->count();
            return Company::where('active', true)
                ->where('date_end', '>', now())
                ->whereHas('cities', function (Builder $query) use ($city_id) {
                    $query->where('id', $city_id);
                })
                ->orderByDesc('priority')->orderByDesc('created_at')
                ->skip($skip)->take($perPage)->get()->put('total', $count);
        }

        $count = Company::where('active', true)
            ->where('date_end', '>', now())
            ->whereHas('cities', function (Builder $query) use ($city_id) {
                $query->where('id', $city_id);
            })
            ->whereHas('categories', function (Builder $query) use ($category_id) {
                $query->where('id', $category_id);
            })->count();
        return Company::where('active', true)
            ->where('date_end', '>', now())
            ->whereHas('cities', function (Builder $query) use ($city_id) {
                $query->where('id', $city_id);
            })
            ->whereHas('categories', function (Builder $query) use ($category_id) {
                $query->where('id', $category_id);
            })
            ->orderByDesc('priority')->orderByDesc('created_at')
            ->skip($skip)->take($perPage)->get()->put('total', $count);
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

//Берем Telegram ID хозяина организации, а если он не указан, то ID Валеры
        $telegram_id = $company->partner->telegram_id ?? '11111';
        $company_id = $company->id;

        $keyboard = Keyboard::make()
            ->inline()
            ->row(Keyboard::inlineButton(['text' => '📝 Записаться/Обратиться', 'callback_data' => "sendContact----{$telegram_id}-{$company_id}"]));

        return [
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard,
        ];
    }
}