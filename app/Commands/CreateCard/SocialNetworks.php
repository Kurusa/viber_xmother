<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class SocialNetworks extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::SOCIAL_NETWORKS) {
            $this->user->social_networks = $this->parser::getMessage();
            $this->user->save();

            $this->triggerCommand(AreYouMom::class);
        } else {
            $this->user->status = UserStatusService::SOCIAL_NETWORKS;
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

            $this->viber->sendMessageWithKeyboard($this->text['contact'], $buttons);
        }
    }

}