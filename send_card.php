<?php
require_once(__DIR__ . '/bootstrap.php');

$user_list = \App\Models\User::where('viber_chat_id', '!=', '')->where('club_expectation', '!=', '')->get();

$viber = new \App\ViberHelpers\ViberApi();
$maker = new \App\Services\ImageMakers\ImageMakerMain();
$maker->viber = $viber;

foreach ($user_list as $user) {
    $viber->sendMessage('Привіт, дорога учаснице клубу X-Mothers!💜
Ми дублюємо тобі твою карту. Перевір, будь-ласка, чи відкривається вона☺️

Нагадуємо, що з картою учасниці ти маєш доступ до привілеїв клубу: зустрічей, знижок, розіграшів🎁!

Для твоєї зручності, надсилаємо тобі посилання на наших партнерів, які підготували для тебе знижки!

https://www.xmothers.com/nashu-parters/', $user->viber_chat_id);
    $maker->setImage(__DIR__ . '/app/Services/ImageMakers/template.png');
    $maker->setData([
        'name' => $user->card_name,
        'id' => $user->id
    ]);
    $maker->constructImage();
    $maker->sendImage($user->viber_chat_id, $user->id);
}