<?php
require __DIR__ . '/vendor/autoload.php';
$banel1ng_termostat_bot_bot_api_key ="1918038811:AAHR8inopkXWmXQdKqVbZTQATMpAUdAQTX8";
$banel1ng_termostat_bot_username = 'banel1ng_termostat_bot';

try {
    $telegram = new Longman\TelegramBot\Telegram($banel1ng_termostat_bot_bot_api_key, $banel1ng_termostat_bot_username);
    $rs = file_get_contents('php://input');
    $jsResponse = file_get_contents('./termCommand.json');
    $jsonRs = json_decode($rs,true);
    if (empty($jsResponse)) {
        $jsResponse = [
            'debug'=> '',
            'tmax'=> '',
            'tmin'=> '',
        ];
    }
    $rsFile = json_decode($jsResponse);
    if (empty($rsFile)) {
        $jsResponse = [
            'debug'=> '',
            'tmax'=> '',
            'tmin'=> '',
        ];
    }


    if ($jsonRs['message']['text'] == "debug") {
        $jsResponse['debug'] = true;
        file_put_contents('./termCommand.json', json_encode($jsResponse));
        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-411683583",
            'text'    => 'установлен дебаг мод' ,
        ]);
    }

    if (false != strstr($jsonRs['message']['text'] , 'tmax-')
        && strlen ($jsonRs['message']['text']) >= 5
        && strlen ($jsonRs['message']['text']) <= 7) {

        $jsResponse['tmax'] = explode('-', $jsonRs['message']['text'] )[1];
        file_put_contents('./termCommand.json', json_encode($jsResponse));

        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-411683583",
            'text'    => 'максимальная температура отправлена на термостат ' . $jsResponse['tmax'] ,
        ]);
    }

    if (false != strstr($jsonRs['message']['text'] , 'tmin-')
        && strlen ($jsonRs['message']['text']) >= 5
        && strlen ($jsonRs['message']['text']) <= 7) {

        $jsResponse['tmin'] = explode('-', $jsonRs['message']['text'] )[1];
        file_put_contents('./termCommand.json', json_encode($jsResponse));

        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-411683583",
            'text'    => 'минимальная температура отправлена на термостат ' .  $jsResponse['tmin'],
        ]);
    }

    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}