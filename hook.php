<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';
$bot_api_key  = '1845276413:AAG0BJt1zIxMy18OvvH_c31NOB2asnCQ7XY';
$bot_username = 'pathePolivBot';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    //dump($telegram);exit;
    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    // echo $e->getMessage();
}