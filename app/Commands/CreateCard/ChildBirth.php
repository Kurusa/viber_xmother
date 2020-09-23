<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class ChildBirth extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::CHILD_BIRTH) {
            list($dd, $mm, $yyyy) = explode('/', $this->parser::getMessage());
            if (!checkdate($mm, $dd, $yyyy)) {
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

                $this->viber->sendMessageWithKeyboard($this->text['wrong_birthdate_format'], $buttons);
            } else {
                $this->user->child_birth = $this->parser::getMessage();
                $this->user->save();

                $this->triggerCommand(SelectSource::class);
            }
        } else {
            $this->user->status = UserStatusService::CHILD_BIRTH;
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
            $this->viber->sendMessageWithKeyboard($this->text['birth_date'], $buttons);
        }
    }

}