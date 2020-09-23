<?php

namespace App\Commands\Mailing;

use App\Commands\BaseCommand;
use App\Commands\MainMenu;
use App\Models\Mailing;
use App\Services\Status\UserStatusService;
class MailingImage extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::MAILING_IMAGE) {
            error_log(json_encode($this->update));
            exit();
            if ($this->parser::getMessage() == 'скасувати') {
                $this->triggerCommand(MainMenu::class);
            } elseif ($this->parser::getMessage() == 'пропустити') {
                $this->triggerCommand(MailingButtonTextQuestion::class);
            } else {
                Mailing::where('user_id', $this->user->id)->update([
                    'image' => $this->update->getMessage()->getPhoto()[0]->getFileId()
                ]);
                $this->triggerCommand(MailingButtonTextQuestion::class);
            }
        } else {
            $this->user->status = UserStatusService::MAILING_IMAGE;
            $this->user->save();
            $buttons = [];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'пропустити',
                'Text' => 'пропустити'
            ];

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

            $this->viber->sendMessageWithKeyboard('Бажаєте додати фото?', $buttons);
        }
    }

}