<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class WhatDoYouExpect extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::EXPECT) {
            if ($this->parser::getMessage() == 'done') {
                $this->triggerCommand(\App\Commands\Done::class);
                exit();
            }
            $expect = array_flip($this->text['expects']);
            $answer = $expect[$this->parser::getMessage()];
            $this->user->club_expectation = $this->user->club_expectation . ',' . $answer;
            $this->user->save();

            $expects = explode(',', $this->user->club_expectation);
            $buttons = [];
            foreach ($this->text['expects'] as $key => $answer) {
                if (!in_array($key, $expects)) {
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
            }

            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'done',
                'Text' => $this->text['done']
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

            $this->viber->sendMessageWithKeyboard($this->text['what_do_you_expect'], $buttons);
        } else {
            $this->user->status = UserStatusService::EXPECT;
            $this->user->save();

            $buttons = [];
            foreach ($this->text['expects'] as $key => $answer) {
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

            $this->viber->sendMessageWithKeyboard($this->text['what_do_you_expect'], $buttons);
        }
    }

}