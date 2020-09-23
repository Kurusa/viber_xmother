<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;
class LocationTypeSelect extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::LOCATION_TYPE_SELECT) {
            switch ($this->parser::getMessage()) {
                case 'send_city_name':
                    $this->triggerCommand(ByCityName::class);
                    break;
                case 'choose_from_list':
                    $this->triggerCommand(DistrictSelect::class);
                    break;
            }
        } else {
            $this->user->status = UserStatusService::LOCATION_TYPE_SELECT;
            $this->user->save();
            $buttons = [];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'send_city_name',
                'Text' => $this->text['send_city_name']
            ];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'choose_from_list',
                'Text' => $this->text['choose_from_list']
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

            $this->user->save();
            $this->viber->sendMessageWithKeyboard($this->text['how_choose_city_question'], $buttons);
        }
    }

}