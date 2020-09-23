<?php

namespace App\Commands\Mailing;

use App\Commands\BaseCommand;
use App\Commands\MainMenu;
use App\Models\Mailing;
use App\Services\Status\UserStatusService;
class MailingButtonTextQuestion extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::MAILING_BUTTON_TEXT_QUESTION) {
            if ($this->parser::getMessage() == 'скасувати') {
                $this->triggerCommand(MainMenu::class);
            } else {
                if ($this->parser::getMessage() == 'так') {
                    $this->triggerCommand(MailingButtonText::class);
                } else {
                    $this->triggerCommand(StartMailing::class);
                }
            }
        } else {
            $this->user->status = UserStatusService::MAILING_BUTTON_TEXT_QUESTION;
            $this->user->save();

            $this->viber->sendMessageWithKeyboard('Бажаєте додати кнопку до розсилки?', new ReplyKeyboardMarkup([
                ['так', 'ні'],
                ['скасувати'],
            ], false, true));
        }
    }

}