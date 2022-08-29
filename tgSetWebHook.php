<?php
error_reporting(E_ALL);
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = '1918038811:AAHR8inopkXWmXQdKqVbZTQATMpAUdAQTX8';
$bot_username = 'banel1ng_termostat_bot';

$banel1ng_termostat_bot_bot_api_key ="1918038811:AAHR8inopkXWmXQdKqVbZTQATMpAUdAQTX8";
$banel1ng_termostat_bot_username = 'banel1ng_termostat_bot';

$hook_url     = 'https://pribor-kotel.ru/tgTermoatatHook.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
         var_dump($result);
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}