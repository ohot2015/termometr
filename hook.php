<?php
 ;
// Load composer
require __DIR__ . '/vendor/autoload.php';
$bot_api_key  = '1845276413:AAG0BJt1zIxMy18OvvH_c31NOB2asnCQ7XY';
$bot_username = 'pathePolivBot';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);


    $str = 'Начал поливать';
    $result = \Longman\TelegramBot\Request::sendMessage([
        'chat_id' => "-457457807",
        'text'    => $str,
    ]);
    dump($result);
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}