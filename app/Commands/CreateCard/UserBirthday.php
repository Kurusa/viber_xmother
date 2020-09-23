<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class UserBirthday extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::USER_BIRTHDAY) {
            $this->user->user_birthday = $this->parser::getMessage();
            $this->user->save();

            $this->triggerCommand(LocationTypeSelect::class);
        } else {
            $this->user->status = UserStatusService::USER_BIRTHDAY;
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
            $this->viber->sendMessageWithKeyboard('Введіть Вашу дату народження у форматі дд/мм/рррр', $buttons);
        }
    }

}