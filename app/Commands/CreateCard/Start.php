<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class Start extends BaseCommand
{

    function processCommand()
    {
        $this->user->status = UserStatusService::CREATE_CARD_START;
        $this->user->save();
        $buttons = [];
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'ActionType' => 'reply',
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            'ActionBody' => 'yes',
            'Text' => $this->text['yes']
        ];
        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'ActionType' => 'reply',
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            'ActionBody' => 'main_menu',
            'Text' => $this->text['main_menu']
        ];

        $this->viber->sendMessageWithKeyboard($this->text['create_card_start_message'], $buttons);
    }

}