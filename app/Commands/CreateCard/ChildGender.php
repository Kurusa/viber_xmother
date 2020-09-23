<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Models\UserChild;
use App\Services\Status\UserStatusService;

class ChildGender extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::CHILD_GENDER) {
            UserChild::create([
                'user_id' => $this->user->id,
                'gender' => $this->parser::getMessage() == 'girl' ? 1 : 0
            ]);
            $this->triggerCommand(ChildBirthday::class);
        } else {
            $this->user->status = UserStatusService::CHILD_GENDER;
            $this->user->save();
            $buttons[] = [
                'Columns' => 3,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'girl',
                'Text' => $this->text['girl']
            ];
            $buttons[] = [
                'Columns' => 3,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'boy',
                'Text' => $this->text['boy']
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
            $this->viber->sendMessageWithKeyboard($this->text['child_gender'], $buttons);
        }
    }

}