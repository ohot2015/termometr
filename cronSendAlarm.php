<?php
require __DIR__ . '/vendor/autoload.php';
$banel1ng_termostat_bot_bot_api_key ="1918038811:AAHR8inopkXWmXQdKqVbZTQATMpAUdAQTX8";
$banel1ng_termostat_bot_username = 'banel1ng_termostat_bot';

$file = file_get_contents('./log.json');
$file = json_decode($file,true);

if (!empty($file)) {
    $lastElemLog = $file[count($file) - 1];
    $date = new DateTime();
    $dateFormat = $date->format('H:i:s');

    $dateMyFormat = explode(':',$dateFormat);
    $d = $dateMyFormat[2] + $dateMyFormat[1]*60 + $dateMyFormat[0]*60*60;
    $dateMyFormat2 = $lastElemLog['time'];

    $dateMyFormat2= explode(':', $dateMyFormat2);

    $dlog = $dateMyFormat2[2] + $dateMyFormat2[1]*60 + $dateMyFormat2[0]*60*60;
    file_put_contents('log3.json',  $d - $dlog);
    if ($d - $dlog < 100) {
        try {
            $telegram = new Longman\TelegramBot\Telegram($banel1ng_termostat_bot_bot_api_key, $banel1ng_termostat_bot_username);
            $result = \Longman\TelegramBot\Request::sendMessage([
                'chat_id' => "-411683583",
                'text'    => $d - $dlog. ' секунд не приходили новые данные' ,
            ]);
            file_put_contents('log3.json', $d - $dlog. ' секунд не приходили новые данные' );
            $telegram->handle();
        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            echo $e->getMessage();
        }
    }
}else {
    try {
        $telegram = new Longman\TelegramBot\Telegram($banel1ng_termostat_bot_bot_api_key, $banel1ng_termostat_bot_username);
        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-411683583",
            'text'    => 'лог пустой' ,
        ]);
        file_put_contents('log3.json', ' лог пустой' );
        $telegram->handle();
    } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        echo $e->getMessage();
    }
}

?>