<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class AreYouPregnant extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::ARE_YOU_PREGNANT) {
            if ($this->parser::getMessage() == 'yes') {
                $this->user->is_pregnant = 1;
                $this->user->save();
                $this->triggerCommand(ChildBirth::class);
            } else {
                $this->triggerCommand(SelectSource::class);
            }
        } else {
            $this->user->status = UserStatusService::ARE_YOU_PREGNANT;
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
            $this->viber->sendMessageWithKeyboard($this->text['are_you_pregnant'], $buttons);
        }
    }

}