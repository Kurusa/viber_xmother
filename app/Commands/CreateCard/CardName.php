<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class CardName extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::CARD_NAME) {
            $this->user->card_name = $this->parser::getMessage();
            $this->user->save();

            $this->triggerCommand(UserBirthday::class);
        } else {
            $this->user->status = UserStatusService::CARD_NAME;
            $this->user->save();
            $buttons = [];
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

            $this->viber->sendMessageWithKeyboard($this->text['card_name'], $buttons);
        }
    }

}