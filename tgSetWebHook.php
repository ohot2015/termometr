<?php
error_reporting(E_ALL);
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = '1845276413:AAG0BJt1zIxMy18OvvH_c31NOB2asnCQ7XY';
$bot_username = 'pathePolivBot';

$banel1ng_termostat_bot_bot_api_key ="1918038811:AAHR8inopkXWmXQdKqVbZTQATMpAUdAQTX8";
$banel1ng_termostat_bot_username = 'banel1ng_termostat_bot';

$hook_url     = 'https://cccxxx.ml/tgTermoatatHook.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($banel1ng_termostat_bot_bot_api_key, $banel1ng_termostat_bot_username);
    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}