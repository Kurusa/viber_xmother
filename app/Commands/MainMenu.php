<?php

namespace App\Commands;

use App\Services\Status\UserStatusService;

class MainMenu extends BaseCommand
{

    function processCommand($text = false)
    {
        $this->user->status = UserStatusService::DONE;
        $this->user->save();
        $buttons = [];

        if (!$this->user->club_expectation) {
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'become_mother_partner',
                'Text' => $this->text['become_mother_partner']
            ];
        }
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            "ActionType" => "open-url",
            "ActionBody" => "https://www.xmothers.com/spivprachya",
            'Text' => $this->text['become_partner']
        ];
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            "ActionType" => "open-url",
            "ActionBody" => "https://www.xmothers.com/spivprachya",
            'Text' => $this->text['im_bloger']
        ];
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            "ActionType" => "open-url",
            "ActionBody" => "https://docs.google.com/forms/d/1TcbtRSEAtWRXRZU4psv31tHfR_PTsf_-jpsUyP0aH68/edit",
            'Text' => $this->text['become_club_partner']
        ];
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            "ActionType" => "open-url",
            "ActionBody" => "https://www.xmothers.com/pro-klub",
            'Text' => $this->text['about_club']
        ];
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'ActionType' => 'reply',
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            'ActionBody' => 'faq',
            'Text' => $this->text['faq']
        ];
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            "ActionType" => "open-url",
            "ActionBody" => "https://www.xmothers.com/nashu-parters",
            'Text' => $this->text['our_partners']
        ];
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            "ActionType" => "open-url",
            "ActionBody" => "https://www.xmothers.com/cat",
            'Text' => $this->text['find_chat']
        ];
        $buttons[] = [
            'Columns' => 3,
            'Rows' => 1,
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            "ActionType" => "open-url",
            "ActionBody" => "https://www.facebook.com/xmothers",
            'Text' => $this->text['facebook']
        ];
        $buttons[] = [
            'Columns' => 3,
            'Rows' => 1,
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            "ActionType" => "open-url",
            "ActionBody" => "https://www.instagram.com/_xmothers_",
            'Text' => $this->text['instagram']
        ];
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'ActionType' => 'reply',
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            'ActionBody' => 'feedback',
            'Text' => $this->text['feedback']
        ];

        if ($this->parser::getMessage() == '/start') {
            $text = 'Привіт! Я X-ботик клубу мам!🤱🏽Привіт! Я X-ботик клубу мам!🤱🏽
Якщо ти ще не зареєструвалася в нашому клубі X-Mothers - я тобі допоможу! 🤗
Розкажу про X-Mothers та відповім на питання.
Тисни на кнопку "Головне меню"👇🏼👇🏼';
        } elseif ($this->user->club_expectation) {
            $text = 'Привіт! Це знову я - X-ботик!😎 Я можу ознайомити тебе з партнерами клубу чи показати соціальні мережі клубу X-Mothers. Роби свій вибір та натискай на кнопки нижче👇🏼
Пам’ятай, ти завжди можеш повернутися в меню командою /menu';
        } else {
            $text = 'Привіт! Це знову я - X-ботик!😎
Хочеш стати учасницею клубу X-Mothers? Чи можливо бажаєш спочатку дізнатися про переваги та привілеї учасниць? Роби свій вибір та натискай на кнопки нижче! Пам’ятай, ти завжди можеш повернутися в меню командою - /menu';
        }

        $this->viber->sendMessageWithKeyboard( $text, $buttons);
    }

}