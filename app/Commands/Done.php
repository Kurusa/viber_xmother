<?php

namespace App\Commands;

use App\Services\Status\UserStatusService;

class Done extends BaseCommand
{

    function processCommand()
    {
        $maker = new \App\Services\ImageMakers\ImageMakerMain();
        $maker->setImage(__DIR__ . '/../Services/ImageMakers/template.png');
        $maker->setData([
            'name' => $this->user->card_name,
            'id' => $this->user->id
        ]);
        $maker->constructImage();
        $maker->sendImage($this->user->viber_chat_id);
        $this->user->status = UserStatusService::DONE;
        $this->user->have_card = 1;
        $this->user->save();
        $this->viber->sendMessage($this->text['card_done_message']);
    }

}