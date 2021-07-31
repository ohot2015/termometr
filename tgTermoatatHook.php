<?php
require __DIR__ . '/vendor/autoload.php';
$banel1ng_termostat_bot_bot_api_key ="1918038811:AAHR8inopkXWmXQdKqVbZTQATMpAUdAQTX8";
$banel1ng_termostat_bot_username = 'banel1ng_termostat_bot';


try {
    $telegram = new Longman\TelegramBot\Telegram($banel1ng_termostat_bot_bot_api_key, $banel1ng_termostat_bot_username);
    $rs = file_get_contents('php://input');
    $jsonRs = json_decode($rs,true);

    if ($jsonRs['message']['text'] == "debug") {
        file_put_contents('./commandTermostat.log', 'debug');
        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-411683583",
            'text'    => 'установлен дебаг мод' ,
        ]);
    }
    $result = \Longman\TelegramBot\Request::sendMessage([
        'chat_id' => "-411683583",
        'text'    => count($jsonRs['message']['text']) ,
    ]);
    if (false != strstr($jsonRs['message']['text'] , 'tmax-')
        && count($jsonRs['message']['text']) >= 5
        && count($jsonRs['message']['text']) <= 7) {
        file_put_contents('./commandTermostat.log', $jsonRs['message']['text'] );
        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-411683583",
            'text'    => 'максимальная температура отправлена на термостат' ,
        ]);
    }

    if (false != strstr($jsonRs['message']['text'] , 'tmin-')
        && count($jsonRs['message']['text']) >= 5
        && count($jsonRs['message']['text']) <= 7) {
        file_put_contents('./commandTermostat.log', $jsonRs['message']['text'] );
        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-411683583",
            'text'    => 'минимальная температура отправлена на термостат' ,
        ]);
    }

    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}