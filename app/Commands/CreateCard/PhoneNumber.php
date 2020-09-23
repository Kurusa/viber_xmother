<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class PhoneNumber extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::PHONE_NUMBER) {
            $this->user->phone_number = $this->parser::getMessage();
            $this->user->save();

            $this->triggerCommand(Email::class);
        } else {
            $this->user->status = UserStatusService::PHONE_NUMBER;
            $this->user->save();

            $buttons = [];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'back',
                'Text' => $this->text['back']
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
            $this->viber->sendMessageWithKeyboard($this->text['phone_number'], $buttons);
        }
    }

}