<?php
error_reporting(E_ALL);
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = '1845276413:AAG0BJt1zIxMy18OvvH_c31NOB2asnCQ7XY';
$bot_username = 'pathePolivBot';
$hook_url     = 'https://cccxxx.ml/hook.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}