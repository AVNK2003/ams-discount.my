<?php

namespace App\TgBot\CallbackFunctions;

use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;

class QueryController
{
    protected $callback;
    protected $bot;

    public function __construct(Api $bot, string $callback)
    {
        $this->callback = $callback;
        $this->bot = $bot;
    }

    public function apply()
    {
//        return ['text' => json_encode($this->callback, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)];
        $arr = $this->makeArray();

        $data = [
            'city' => $arr[1] ?? 0,
            'category' => $arr[2] ?? 0,
            'page' => $arr[3] ?? 0,
            'telegram_id' => $arr[4] ?? 0,
            'company_id' => $arr[5] ?? 0,
            'user_id' => $this->bot->getWebhookUpdate()->getChat()->id,
            'user_name' => $this->bot->getWebhookUpdate()->getChat()->firstName ?? 'Уважаемый пользователь',
        ];

//        return ['text' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)];

        if (method_exists($this, $arr[0])) {
            return call_user_func_array([$this, $arr[0]], [$data]);
        }

        return ['text' => json_encode($this->callback, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)];
    }

    public function makeArray()
    {
        return explode('-', $this->callback);
    }

    protected function makeKeyboard(array $buttons = []): Keyboard
    {
        $buttons[] = [['text' => 'В начало', 'callback_data' => 'mainMenu']];
        $keyboard = ["inline_keyboard" => $buttons];
        return Keyboard::make($keyboard);
    }

//    protected function makeKeyboard(string $prefix, Collection $items, array $addButtons = []): Keyboard
//    {
//        $arr = [];
//        foreach ($items as $item) {
//            $arr[] = [
//                'text' => $item->name, 'callback_data' => $prefix.$item->id
//            ];
//        }
//
//        $btn = array_chunk($arr, 2);
//        if ($addButtons) {
//            $btn[] = $addButtons;
//        }
//        $btn[] = [['text' => 'В начало', 'callback_data' => 'mainMenu']];
//        $keyboard = ["inline_keyboard" => $btn];
//        return Keyboard::make($keyboard);
//    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    protected function editMessageKeyboard(Keyboard $keyboard)
    {
        $update = $this->bot->getWebhookUpdate();
        $this->bot->editMessageReplyMarkup([
            'chat_id' => $update->getChat()->id,
            'message_id' => $update->getMessage()->messageId,
            'reply_markup' => $keyboard,
        ]);
    }
}