<?php
require_once(__DIR__ . '/bootstrap.php');

use App\Models\User;
use App\ViberHelpers\ViberParser;

$update = \json_decode(file_get_contents('php://input'), TRUE);
$viber_parser = new ViberParser($update);
$viber = new \App\ViberHelpers\ViberApi();
$handlers = include(__DIR__ . '/app/config/keyboard_сommands.php');

if (isset($handlers[$viber_parser::getMessage()])) {
    (new $handlers[$viber_parser::getMessage()]($update))->handle($update);
} else {
    $user = User::where('viber_chat_id', $viber_parser::getChatId())->first();
    if (!$user) {
        $user = User::create([
            'viber_chat_id' => $viber_parser::getChatId(),
            'user_name' => $viber_parser::getUserName(),
            'status' => \App\Services\Status\UserStatusService::NEW
        ]);
    }
    $handlers = include(__DIR__ . '/app/config/status_сommands.php');
    if ($handlers[$user->status]) {
        (new $handlers[$user->status]($update))->handle($update);
    } else {
        (new \App\Commands\MainMenu($update))->handle();
    }
}
