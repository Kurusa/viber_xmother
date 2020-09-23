<?php

namespace App\Commands\Mailing;

use App\Commands\BaseCommand;
use App\Commands\MainMenu;
use App\Models\Mailing;
use App\Services\Status\UserStatusService;
class MailingButtonTextUrl extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::MAILING_BUTTON_TEXT_URL) {
            if ($this->parser::getMessage() == 'скасувати') {
                $this->triggerCommand(MainMenu::class);
            } else {
                $mailing = Mailing::where('user_id', $this->user->id)->first();
                $button_data = json_decode($mailing->button, true);
                $button_data['url'] = $this->parser::getMessage();
                $mailing->button = json_encode($button_data);
                $mailing->save();
                $this->triggerCommand(StartMailing::class);
            }
        } else {
            $this->user->status = UserStatusService::MAILING_BUTTON_TEXT_URL;
            $this->user->save();

            $this->viber->sendMessageWithKeyboard('Введіть посилання кнопки', new ReplyKeyboardMarkup([
                ['скасувати'],
            ], false, true));
        }
    }

}