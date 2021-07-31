<?php
require __DIR__ . '/vendor/autoload.php';
$banel1ng_termostat_bot_bot_api_key ="1918038811:AAHR8inopkXWmXQdKqVbZTQATMpAUdAQTX8";
$banel1ng_termostat_bot_username = 'banel1ng_termostat_bot';


try {
    $telegram = new Longman\TelegramBot\Telegram($banel1ng_termostat_bot_bot_api_key, $banel1ng_termostat_bot_username);
    $rs = file_get_contents('php://input');
    $jsonRs = json_decode($rs,true);
    file_put_contents('./test.log',$rs);
    $result = \Longman\TelegramBot\Request::sendMessage([
        'chat_id' => "-411683583",
        'text'    =>$jsonRs['message']['text'] ,
    ]);
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}