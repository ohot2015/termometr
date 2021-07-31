<?php
require __DIR__ . '/vendor/autoload.php';
$banel1ng_termostat_bot_bot_api_key ="1918038811:AAHR8inopkXWmXQdKqVbZTQATMpAUdAQTX8";
$banel1ng_termostat_bot_username = 'banel1ng_termostat_bot';

try {
    $telegram = new Longman\TelegramBot\Telegram($banel1ng_termostat_bot_bot_api_key, $banel1ng_termostat_bot_username);
    $data = json_decode(file_get_contents('./termCommand.json'));
    $log = file_get_contents('php://input');

    $result = \Longman\TelegramBot\Request::sendMessage([
        'chat_id' => "-411683583",
        'text'    => $log,
    ]);

    echo file_get_contents("termCommand.json");

    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}