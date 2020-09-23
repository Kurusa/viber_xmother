<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class Done extends BaseCommand
{

    function processCommand()
    {
        $this->viber->sendMessage($this->text['card_done_message']);
        $maker = new \App\Services\ImageMakers\ImageMakerMain();
        $maker->setImage(__DIR__ . '/../../Services/ImageMakers/template.png');
        $maker->setData([
            'name' => $this->user->card_name,
            'id' => $this->user->id
        ]);
        $maker->constructImage();
        $maker->sendImage($this->user->viber_chat_id);
        $this->user->status = UserStatusService::DONE;
        $this->user->save();
    }

}