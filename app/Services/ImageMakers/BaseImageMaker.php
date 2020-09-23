<?php

namespace App\Services\ImageMakers;

use App\ViberHelpers\ViberApi;
use CURLFile;

abstract class BaseImageMaker
{


    protected $width;
    protected $height;
    public $image;
    protected $data;
    protected $text;

    public function setImage($path)
    {
        if ($path) {
            $this->image = imagecreatefrompng($path);
            $this->width = imagesx($this->image);
            $this->height = imagesy($this->image);
        }
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function constructImage()
    {
        $this->name();
        $this->id();
    }

    public function sendImage($chat_id, $id = null)
    {
        $bot = new ViberApi();
        $bot->chat_id = $chat_id;

        imagepng($this->image,'xmothers_card.jpg');
        $buttons = [];
        $buttons[] = [
            'Columns' => 3,
            'Rows' => 1,
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            "ActionType" => "open-url",
            "ActionBody" => "https://www.instagram.com/_xmothers_",
            'Text' => 'Інстаграм'
        ];
        $buttons[] = [
            'Columns' => 3,
            'Rows' => 1,
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            "ActionType" => "open-url",
            "ActionBody" => "https://t.me/joinchat/GXYMWUro0Nuurla2cb7mdw",
            'Text' => 'Чат мам України'
        ];
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            "ActionType" => "open-url",
            "ActionBody" => "https://www.xmothers.com/nashu-parters",
            'Text' => 'Партнери Клубу'
        ];
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'ActionType' => 'reply',
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            'ActionBody' => 'main_menu',
            'Text' => 'Головне меню'
        ];

        $bot->sendMessageWithKeyboard('💜 Приєднуйтесь до нас в Інстаграмі, щоб не пропустити конкурси, анонси зустрічей та презентації нових партнерів!😍

💜А ще в нас є спільний чат мам України, де ви можете знайти відповідь на будь-яке ваше питання!

💜 Не забудьте ознайомитись із нашим списком партнерів! Отримуйте знижки та насолоджуйтесь шопінгом з картою X-Mothers!🛍', $buttons);
        $bot->sendFile('https://viber.xmothersbot.space/xmothers_card.jpg', 'xmothers_card.jpg');
    }

    abstract function name();

    abstract function id();

}