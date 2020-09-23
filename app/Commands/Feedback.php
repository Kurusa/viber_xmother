<?php

namespace App\Commands;

use App\Services\Status\UserStatusService;

class Feedback extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::FEEDBACK) {
            $this->user->status = UserStatusService::DONE;
            $this->user->save();
            if (!$this->user->club_expectation) {
                $buttons[] = [
                    'Columns' => 6,
                    'Rows' => 1,
                    'ActionType' => 'reply',
                    'BgColor' => '#e2c9ff',
                    'TextOpacity' => 60,
                    'TextSize' => 'large',
                    'ActionBody' => 'become_mother_partner',
                    'Text' => $this->text['become_mother_partner']
                ];
            }
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                "ActionType" => "open-url",
                "ActionBody" => "https://www.xmothers.com/spivprachya",
                'Text' => $this->text['become_partner']
            ];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                "ActionType" => "open-url",
                "ActionBody" => "https://www.xmothers.com/spivprachya",
                'Text' => $this->text['im_bloger']
            ];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                "ActionType" => "open-url",
                "ActionBody" => "https://docs.google.com/forms/d/1TcbtRSEAtWRXRZU4psv31tHfR_PTsf_-jpsUyP0aH68/edit",
                'Text' => $this->text['become_club_partner']
            ];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                "ActionType" => "open-url",
                "ActionBody" => "https://www.xmothers.com/pro-klub",
                'Text' => $this->text['about_club']
            ];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'faq',
                'Text' => $this->text['faq']
            ];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                "ActionType" => "open-url",
                "ActionBody" => "https://www.xmothers.com/nashu-parters",
                'Text' => $this->text['our_partners']
            ];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                "ActionType" => "open-url",
                "ActionBody" => "https://www.xmothers.com/cat",
                'Text' => $this->text['find_chat']
            ];
            $buttons[] = [
                'Columns' => 3,
                'Rows' => 1,
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                "ActionType" => "open-url",
                "ActionBody" => "https://www.facebook.com/xmothers",
                'Text' => $this->text['facebook']
            ];
            $buttons[] = [
                'Columns' => 3,
                'Rows' => 1,
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                "ActionType" => "open-url",
                "ActionBody" => "https://www.instagram.com/_xmothers_",
                'Text' => $this->text['instagram']
            ];
            $buttons[] = [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#e2c9ff',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'feedback',
                'Text' => $this->text['feedback']
            ];
            $this->viber->sendMessageWithKeyboard('Дякуємо за звернення! Вашу заявку опрацюють найближчим часом!', $buttons);
            $admins = explode(',', env('ADMIN_ID'));
            foreach ($admins as $admin) {
                $this->viber->sendMessageWithKeyboard('Звернення від користувача ' . $this->user->phone_number . "\n" . $this->parser::getMessage(), $buttons, $admin);
            }
        } else {
            $this->user->status = UserStatusService::FEEDBACK;
            $this->user->save();
            $buttons = [];
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
            $this->viber->sendMessageWithKeyboard($this->text['feedback_text'], $buttons);
        }
    }

}