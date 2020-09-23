<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;
class SelectSource extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::SELECT_SOURCE) {
            $answers = array_flip($this->text['source_answers']);
            $answer = $answers[$this->parser::getMessage()];
            $this->user->source = $answer;
            $this->user->save();

            if ($this->user->source == 'friend') {
                $this->triggerCommand(SourceFriend::class);
            } else {
                $this->triggerCommand(WhatDoYouExpect::class);
            }
        } else {
            $this->user->status = UserStatusService::SELECT_SOURCE;
            $this->user->save();

            $buttons = [];
            foreach ($this->text['source_answers'] as $key => $answer) {
                $buttons[] = [
                    'Columns' => 6,
                    'Rows' => 1,
                    'ActionType' => 'reply',
                    'BgColor' => '#e2c9ff',
                    'TextOpacity' => 60,
                    'TextSize' => 'large',
                    'ActionBody' => $answer,
                    'Text' => $answer
                ];
            }

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

            $this->viber->sendMessageWithKeyboard($this->text['source_question'], $buttons);
        }
    }

}