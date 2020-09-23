<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Models\City;
use App\Services\Status\UserStatusService;

class CitySelect extends BaseCommand
{

    function processCommand()
    {
        $city_list = City::where('district_id', $this->user->district_id)->limit(24)->get();

        $data_list = [];
        foreach ($city_list as $city) {
            $data_list[] = [
                'Columns' => 3,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => $city->title_ua,
                'Text' => $city->title_ua
            ];
        }
        $data_list[] = [
            'Columns' => 6,
            'Rows' => 1,
            'ActionType' => 'reply',
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            'ActionBody' => 'back',
            'Text' => $this->text['back']
        ];

        $this->user->status = UserStatusService::LOCATION_SELECTING;
        $this->user->save();

        $this->viber->sendMessageWithKeyboard($this->text['select_city'], $data_list);
    }

}