<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class AreYouMom extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::ARE_YOU_MOM) {
            if ($this->parser::getMessage() == 'yes') {
                $this->triggerCommand(ChildGender::class);
            } else {
                $this->triggerCommand(AreYouPregnant::class);
            }
        } else {
            $this->user->status = UserStatusService::ARE_YOU_MOM;
            $this->user->save();

            $buttons = [];
            $buttons[] = [
                'Columns' => 3,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'yes',
                'Text' => $this->text['yes']
            ];
            $buttons[] = [
                'Columns' => 3,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'no',
                'Text' => $this->text['no']
            ];
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

            $this->viber->sendMessageWithKeyboard($this->text['are_you_mom'], $buttons);
        }
    }

}