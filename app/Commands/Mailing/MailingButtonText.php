<?php

namespace App\Commands\Mailing;

use App\Commands\BaseCommand;
use App\Commands\MainMenu;
use App\Models\Mailing;
use App\Services\Status\UserStatusService;
class MailingButtonText extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::MAILING_BUTTON_TEXT) {
            if ($this->parser::getMessage() == 'скасувати') {
                $this->triggerCommand(MainMenu::class);
            } else {
                Mailing::where('user_id', $this->user->id)->update([
                    'button' => json_encode([
                        'text' => $this->parser::getMessage()
                    ])
                ]);
                $this->triggerCommand(MailingButtonTextUrl::class);
            }
        } else {
            $this->user->status = UserStatusService::MAILING_BUTTON_TEXT;
            $this->user->save();

            $this->viber->sendMessageWithKeyboard('Напишіть назву кнопки', new ReplyKeyboardMarkup([
                ['скасувати'],
            ], false, true));
        }
    }

}