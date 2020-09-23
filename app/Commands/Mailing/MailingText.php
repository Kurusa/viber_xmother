<?php

namespace App\Commands\Mailing;

use App\Commands\BaseCommand;
use App\Commands\MainMenu;
use App\Models\Mailing;
use App\Services\Status\UserStatusService;

class MailingText extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::MAILING_TEXT) {
            if ($this->parser::getMessage() == 'скасувати') {
                $this->triggerCommand(MainMenu::class);
            } else {
                Mailing::where('user_id', $this->user->id)->update([
                    'text' => $this->parser::getMessage()
                ]);
                $this->triggerCommand(MailingImage::class);
            }
        } else {
            $this->user->status = UserStatusService::MAILING_TEXT;
            $this->user->save();

            $buttons = [];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'скасувати',
                'Text' => 'скасувати'
            ];

            $this->viber->sendMessageWithKeyboard('Введіть текст розсилки', $buttons);
        }
    }

}