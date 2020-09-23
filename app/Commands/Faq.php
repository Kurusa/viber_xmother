<?php

namespace App\Commands;

class Faq extends BaseCommand
{

    function processCommand()
    {
        $possible_question_key = explode( '_', $this->parser::getMessage());
        if ($this->text['question_list'][$possible_question_key[1]]) {
            $answer = $this->text['question_list'][$possible_question_key[1]]['answer'];
            if ($this->text['question_list'][$possible_question_key[1]]['buttons']) {
                $this->viber->sendMessageWithKeyboard($answer, $this->text['question_list'][$possible_question_key[1]]['buttons']);
            } else {
                foreach ($this->text['question_list'] as $key => $question) {
                    $buttons[] = [
                        'Columns' => 6,
                        'Rows' => 1,
                        'ActionType' => 'reply',
                        'BgColor' => '#e2c9ff',
                        'TextOpacity' => 60,
                        'TextSize' => 'large',
                        'ActionBody' => 'faq_' . $key,
                        'Text' => $question['question']
                    ];
                }

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
                $this->viber->sendMessageWithKeyboard($answer, $buttons);
            }
        } else {
            $buttons = [];
            foreach ($this->text['question_list'] as $key => $question) {
                $buttons[] = [
                    'Columns' => 6,
                    'Rows' => 1,
                    'ActionType' => 'reply',
                    'BgColor' => '#e2c9ff',
                    'TextOpacity' => 60,
                    'TextSize' => 'large',
                    'ActionBody' => 'faq_' . $key,
                    'Text' => $question['question']
                ];
            }

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

            $this->viber->sendMessageWithKeyboard($this->text['faq_menu'], $buttons);
        }
    }

}