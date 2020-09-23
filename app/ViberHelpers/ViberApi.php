<?php

namespace App\ViberHelpers;

use Dotenv\Dotenv;

class ViberApi
{

    public $result;
    public $chat_id;
    public $curl;

    public $API = 'https://chatapi.viber.com/pa/';

    public function __construct()
    {
        $this->curl = curl_init();

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }

    public function api($method, $params)
    {
        $url = $this->API . $method;

        return $this->do($url, $params);
    }

    private function do(string $url, array $params = []): ?array
    {
        $params = json_encode($params);

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($params),
            'X-Viber-Auth-Token: ' . env('VIBER_BOT_TOKEN')
        ]);

        $this->result = json_decode(curl_exec($this->curl), TRUE);
        return $this->result;
    }

    public function sendMessage($text, $chat_id = null)
    {
        $this->api('send_message', [
            'text' => $text,
            'receiver' => $chat_id ?: $this->chat_id,
            'type' => 'text',
            'sender' => [
                'name' => 'X-Mother'
            ],
        ]);
    }

    public function sendContact($name, $phone, $chat_id = null)
    {
        $this->api('send_message', [
            'receiver' => $chat_id ?: $this->chat_id,
            'type' => 'contact',
            'tracking_data' => 'tracking data',
            'sender' => [
                'name' => 'X-Mother'
            ],
            'contact' => [
                'name' => $name,
                'phone_number' => $phone
            ]
        ]);
    }

    public function myAccountInfo()
    {
        $this->api('get_account_info', []);
    }

    public function sendMessageWithKeyboard($text, $keyboard, $chat_id = null)
    {

        error_log('here');
        $this->api('send_message', [
            'text' => $text,
            'receiver' => $chat_id ?: $this->chat_id,
            'type' => 'text',
            'sender' => [
                'name' => 'X-Mother'
            ],
            "keyboard" => [
                'Type' => 'keyboard',
                'Buttons' => $keyboard
            ]
        ]);
    }

    public function sendFile($file_url, $file_name, $chat_id = null, $local_file_name = null)
    {
        $this->api('send_message', [
            'receiver' => $chat_id ?: $this->chat_id,
            'type' => 'file',
            'sender' => [
                'name' => 'X-Mother'
            ],
            "media" => $file_url,
            "size" => filesize(__DIR__ . '/../../' . $local_file_name ?: $file_name),
            "file_name" => $file_name,
        ]);
    }

    public function __destruct()
    {
        $this->curl = curl_close($this->curl);
    }

}