<?php

namespace App\Commands\Mailing;

use App\Commands\MainMenu;
use Illuminate\Database\Capsule\Manager as DB;

use App\Commands\BaseCommand;
use App\Models\Mailing;

class StartMailing extends BaseCommand
{

    function processCommand()
    {
        $mailing = Mailing::where('user_id', $this->user->id)->first();
        $mailing_data = json_decode($mailing->value, true);

        $query = 'SELECT chat_id FROM user ';
        if ($mailing_data['all_users']) {
        } elseif ($mailing_data['user_pregnant']) {
            $query .= ' WHERE is_pregnant = 1 ';
        } elseif ($mailing_data['user_expects']) {
            $add_or = false;
            foreach ($mailing_data['user_expects'] as $key => $expect) {
                if ($add_or) {
                    $query .= ' OR ';
                }
                if (!$add_or) {
                    $query .= ' WHERE ';
                }
                $query .= ' club_expectation LIKE "%' . $key . '%" ';
                $add_or = true;
            }
        } elseif ($mailing_data['user_city']) {

        }

        $user_list = DB::select($query);
        foreach ($user_list as $user) {
            try {
                if ($mailing->image) {
                    if ($mailing->button) {
                        $buttons = [];
                        $buttons[] = [json_decode($mailing->button)];

                        $this->viber->sendPhoto($user->chat_id, $mailing->image, $mailing->text ?: '', null, new InlineKeyboardMarkup($buttons));
                    } else {
                        $this->viber->sendPhoto($user->chat_id, $mailing->image, $mailing->text ?: '');
                    }
                } else {
                    if ($mailing->button) {
                        $buttons = [];
                        $buttons[] = [json_decode($mailing->button)];
                        $this->viber->sendMessage($user->chat_id, $mailing->text, 'html', false, null, new InlineKeyboardMarkup($buttons));
                    } else {
                        $this->viber->sendMessage($user->chat_id, $mailing->text);
                    }
                }
            } catch (\Exception $exception) {
                error_log($exception->getMessage());
            }
        }

        DB::delete('DELETE FROM mailing');
        $this->triggerCommand(MainMenu::class);
    }

}