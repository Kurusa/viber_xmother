<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Models\UserChild;
use App\Services\Status\UserStatusService;
class ChildBirthday extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::CHILD_BIRTHDAY) {
            list($dd, $mm, $yyyy) = explode('/', $this->parser::getMessage());
            if (!checkdate($mm, $dd, $yyyy)) {

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

                $this->viber->sendMessageWithKeyboard($this->text['wrong_birthday_format'], $buttons);
            } else {
                UserChild::where('user_id', $this->user->id)->where('birthday', NULL)->update([
                    'birthday' => $this->parser::getMessage()
                ]);
                $this->triggerCommand(HaveMoreChild::class);
            }
        } else {
            $this->user->status = UserStatusService::CHILD_BIRTHDAY;
            $this->user->save();

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
            $this->viber->sendMessageWithKeyboard($this->text['child_birth'], $buttons);
        }
    }

}