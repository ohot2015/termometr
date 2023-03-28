<?php
const POLLING_TIME = 20 * 60; // каждые 20 минут проверть
require __DIR__ . '/vendor/autoload.php';
$banel1ng_termostat_bot_bot_api_key ="1918038811:AAHR8inopkXWmXQdKqVbZTQATMpAUdAQTX8";
$banel1ng_termostat_bot_username = 'banel1ng_termostat_bot';

$file = file_get_contents('./log.json');
$file = json_decode($file,true);
//$file = json_decode('[{"t":{"t1":84.75},"r":{"n":1,"p":false},"time":"2-13:22:45"}]',true);

if (!empty($file)) {
    $lastElemLog = $file[count($file) - 1];
    $date = new DateTime();
    $dateFormat = $date->format('H:i:s');

    $dateMyFormat = explode(':',$dateFormat);
    $d = $dateMyFormat[2] + $dateMyFormat[1]*60 + $dateMyFormat[0]*60*60;
    $dateMyFormat2 = $lastElemLog['time'];

    $dateMyFormat2= explode(':', $dateMyFormat2);
    $dateMyFormat2[0] = explode('-', $dateMyFormat2[0] )[1];
    $dlog = $dateMyFormat2[2] + $dateMyFormat2[1] * 60 + $dateMyFormat2[0] * 60 * 60;
    file_put_contents('log3.json',  $d - $dlog);
    if ($d - $dlog > POLLING_TIME) {
        try {
            $telegram = new Longman\TelegramBot\Telegram($banel1ng_termostat_bot_bot_api_key, $banel1ng_termostat_bot_username);
            $result = \Longman\TelegramBot\Request::sendMessage([
                'chat_id' => "-1001753476179",
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
            'chat_id' => "-1001753476179",
            'text'    => 'лог пустой' ,
        ]);
        file_put_contents('log3.json', ' лог пустой' );
        $telegram->handle();
    } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        echo $e->getMessage();
    }
}

?>