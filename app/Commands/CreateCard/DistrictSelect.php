<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Models\District;
use App\Services\Status\UserStatusService;

class DistrictSelect extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::DISTRICT_SELECT) {
            if ($this->parser::getMessage() == 'back') {
                $this->triggerCommand(LocationTypeSelect::class);
            } else {
                $possible_district = District::where('title_ua', 'like', '%' . $this->parser::getMessage() . '%')
                    ->orWhere('title_ru', 'like', '%' . $this->parser::getMessage() . '%')
                    ->orWhere('title_en', 'like', '%' . $this->parser::getMessage() . '%')
                    ->get(['id']);
                if ($possible_district->count()) {
                    $this->user->district_id = $possible_district[0]['id'];
                    $this->user->save();
                    $this->triggerCommand(CitySelect::class);
                } else {
                    $this->viber->sendMessage($this->text['cant_find_city']);
                }
            }
        } elseif ($this->user->status === UserStatusService::LOCATION_TYPE_SELECT) {
            $district_list = District::all();

            $data_list = [];
            foreach ($district_list as $district) {
                $data_list[] = [
                    'Columns' => 3,
                    'Rows' => 1,
                    'ActionType' => 'reply',
                    'BgColor' => '#e2c9ff',
                    'TextOpacity' => 60,
                    'TextSize' => 'large',
                    'ActionBody' => $district->title_ua,
                    'Text' => $district->title_ua
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

            $this->user->status = UserStatusService::DISTRICT_SELECT;
            $this->user->save();

            $this->viber->sendMessageWithKeyboard($this->text['select_district'], $data_list);
        }
    }

}