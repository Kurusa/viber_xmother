<?php

namespace App\Commands\CreateCard;

use App\Commands\BaseCommand;
use App\Models\City;
use App\Services\Status\UserStatusService;

class ByCityName extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::CITY_NAME) {
            if ($this->parser::getMessage() == 'back') {
                $this->triggerCommand(LocationTypeSelect::class);
            } else {
                if (strlen(trim($this->parser::getMessage())) > 3) {
                    $possible_city_list = City::where('title_ua', 'like', '%' . $this->parser::getMessage() . '%')
                        ->orWhere('title_ru', 'like', '%' . $this->parser::getMessage() . '%')
                        ->orWhere('title_en', 'like', '%' . $this->parser::getMessage() . '%')
                        ->get();

                    if ($possible_city_list->count()) {
                        $this->user->status = UserStatusService::LOCATION_SELECTING;
                        $this->user->save();

                        $city_list = [];
                        foreach ($possible_city_list as $city) {
                            $city_list[] = [
                                'Columns' => 6,
                                'Rows' => 1,
                                'ActionType' => 'reply',
                                'BgColor' => '#e2c9ff',
                                'TextOpacity' => 60,
                                'TextSize' => 'large',
                                'ActionBody' => $city->title_ua . ', ' . $city->district->title_ua . $this->text['district'],
                                'Text' => $city->title_ua . ', ' . $city->district->title_ua . $this->text['district']
                            ];
                        }

                        $city_list[] = [
                            'Columns' => 6,
                            'Rows' => 1,
                            'ActionType' => 'reply',
                            'BgColor' => '#e2c9ff',
                            'TextOpacity' => 60,
                            'TextSize' => 'large',
                            'ActionBody' => 'back',
                            'Text' => $this->text['back']
                        ];

                        $this->viber->sendMessageWithKeyboard($this->text['did_you_mean_this_city'], $city_list);
                    } else {
                        $this->viber->sendMessage($this->text['cant_find_city']);
                    }
                } else {
                    $this->viber->sendMessage($this->text['more_symbols']);
                }
            }
        } elseif ($this->user->status === UserStatusService::LOCATION_TYPE_SELECT) {
            $this->user->status = UserStatusService::CITY_NAME;
            $this->user->save();

            $buttons = [];
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

            $this->viber->sendMessageWithKeyboard($this->text['request_to_write_city'], $buttons);
        }
    }

}