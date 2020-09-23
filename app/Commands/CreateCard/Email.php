<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;
class Email extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::EMAIL) {
            $this->user->email = $this->parser::getMessage();
            $this->user->save();

            $this->triggerCommand(SocialNetworks::class);
        } else {
            $this->user->status = UserStatusService::EMAIL;
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
            $this->viber->sendMessageWithKeyboard($this->text['email'], $buttons);
        }
    }

}