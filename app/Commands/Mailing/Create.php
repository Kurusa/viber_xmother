<?php

namespace App\Commands\Mailing;

use App\Commands\BaseCommand;
use App\Models\Mailing;
use App\Models\District;
use App\Services\Status\UserStatusService;

class Create extends BaseCommand
{

    function processCommand()
    {
        $this->user->status = UserStatusService::CREATE_MAILING;
        $this->user->save();
        // create mailing
        $mailing = Mailing::where('user_id', $this->user->id)->first();
        if (!$mailing) {
            Mailing::create([
                'user_id' => $this->user->id
            ]);
            $mailing = Mailing::where('user_id', $this->user->id)->first();
        }

        // get callback data
        $callback_data = json_decode($this->parser::getMessage(), true);
        $callback_data['a'] = isset($callback_data['a']) ? $callback_data['a'] : 'mailing';

        // convert json from db to array
        $db_mailing_config = json_decode($mailing->value, true);
        // data to build buttons
        $mailing_config = [
            'all_users' => [
                'title' => 'Всім користувачам',
            ],
            'user_pregnant' => [
                'title' => 'Вагітним',
            ],
            'with_child' => [
                'title' => 'Мамам із дітьми',
                'sub_values' => [
                    'sixmoth' => 'До 6 місяців',
                    'oney' => 'До 1 року',
                    'onethree' => '1-3 р.',
                    'fourseven' => '4-7 р.',
                    'seventwel' => '7–12 р.',
                    'thirteen' => '13-18 р.'
                ]
            ],
            'user_city' => [
                'title' => 'Вибір міста проживання',
                'sub_values' => $this->getDistrictSubValues()
            ],
            'user_expects' => [
                'title' => 'Інтереси',
                'sub_values' => [
                    'discount' => 'Знижки від партнерів клубу',
                    'visiting' => 'Відвідування заходів клубу',
                    'communication' => 'Спілкування з мамами',
                    'support' => 'Підтримка від експертів клубу',
                    'access' => 'Доступ до корисної інформації, що надає клуб (статті, новини, вакансії)',
                    'content' => 'Споживання цікавого контенту',
                    'gifts' => 'Розіграші подарунків ',
                ]
            ],
        ];

        switch ($callback_data['a']) {
            case 'mailing':
            case 'back_to_values':
                $buttons = $this->getButtons($mailing_config, $db_mailing_config);
                break;
            case 'unset':
                if ($mailing_config[$callback_data['key']]['sub_values']) {
                    $buttons = $this->getSubButtons($callback_data['key'], $mailing_config[$callback_data['key']]['sub_values'], $db_mailing_config);
                    break;
                }
                unset($db_mailing_config[$callback_data['key']]);
                $buttons = $this->getButtons($mailing_config, $db_mailing_config);
                break;
            case 'set':
                if (isset($mailing_config[$callback_data['key']]['sub_values'])) {
                    $buttons = $this->getSubButtons($callback_data['key'], $mailing_config[$callback_data['key']]['sub_values'], $db_mailing_config);
                    break;
                }
                if ($callback_data['key'] == 'all_users') {
                    $db_mailing_config = [];
                }
                $db_mailing_config[$callback_data['key']] = true;
                $buttons = $this->getButtons($mailing_config, $db_mailing_config);
                break;
            case 'set_sub':
                $db_mailing_config[$callback_data['key']][$callback_data['sub_key']] = true;
                $buttons = $this->getSubButtons($callback_data['key'], $mailing_config[$callback_data['key']]['sub_values'], $db_mailing_config);
                break;
            case 'unset_sub':
                unset($db_mailing_config[$callback_data['key']][$callback_data['sub_key']]);
                $buttons = $this->getSubButtons($callback_data['key'], $mailing_config[$callback_data['key']]['sub_values'], $db_mailing_config);
                break;
            case 'mailing_text':
                $this->triggerCommand(MailingText::class);
                break;
        }

        $mailing->value = json_encode($db_mailing_config, true);
        $mailing->save();

        if (json_decode($mailing->value)) {
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => json_encode([
                    'a' => 'mailing_text',
                ]),
                'Text' => 'Готово'
            ];
        }

        $this->viber->sendMessageWithKeyboard('Оберіть критерії для розсилки', $buttons);
    }

    private function getButtons($mailing_config, $db_mailing_config)
    {
        $buttons = [];
        foreach ($mailing_config as $mailing_key => $config) {
            $text = $config['title'];
            if ($db_mailing_config[$mailing_key]) {
                $text .= ' ☑️';
            }
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => json_encode([
                    'a' => $db_mailing_config[$mailing_key] ? 'unset' : 'set',
                    'key' => $mailing_key,
                ]),
                'Text' => $text
            ];

            if ($mailing_key == 'all_users') {
                if ($db_mailing_config['all_users']) {
                    break;
                }
            }
        }

        return $buttons;
    }

    private function getSubButtons($main_key, $sub_mailing_config, $db_mailing_config)
    {
        $buttons = [];
        foreach ($sub_mailing_config as $mailing_key => $mailing_text) {
            $text = $mailing_text;
            if ($db_mailing_config[$main_key][$mailing_key]) {
                $text .= ' ☑️';
            }
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => json_encode([
                    'a' => $db_mailing_config[$main_key][$mailing_key] ? 'unset_sub' : 'set_sub',
                    'sub_key' => $mailing_key,
                    'key' => $main_key,
                ]),
                'Text' => $text
            ];
        }

        $buttons[] = [
            'Columns' => 6,
            'Rows' => 1,
            'ActionType' => 'reply',
            'BgColor' => '#e2c9ff',
            'TextOpacity' => 60,
            'TextSize' => 'large',
            'ActionBody' => json_encode([
                'a' => 'back_to_values',
            ]),
            'Text' => 'Назад'
        ];

        return $buttons;
    }

    private function getDistrictSubValues()
    {
        $district_list = District::all();
        $sub_values = [];

        foreach ($district_list as $district) {
            $sub_values[$district->id] = $district->title_ua;
        }

        return $sub_values;
    }
}