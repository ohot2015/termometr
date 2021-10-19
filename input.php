<?php
ini_set('display_errors', 1);
$data = file_get_contents('php://input');
$file = file_get_contents('./log.json');
require __DIR__ . '/vendor/autoload.php';
$banel1ng_termostat_bot_bot_api_key ="1918038811:AAHR8inopkXWmXQdKqVbZTQATMpAUdAQTX8";
$banel1ng_termostat_bot_username = 'banel1ng_termostat_bot';

if (!empty($file)) {
    $file = json_decode($file,true);
    if (count($file) > 4200) {
        $file = array_splice($file, 30);
    }
    $data = json_decode($data,true);

    $file = array_merge($file, $data);
    $lastElemLog = $file[count($file) - 1];
    $date = new DateTime();
    $dateFormat = $date->format('H:i:s');
    $dateMyFormat = explode(':',$dateFormat);
    $d = $dateMyFormat[2]*$dateMyFormat[1]*$dateMyFormat[0];
    $dateMyFormat2 = $lastElemLog['time'];
    $dlog = $dateMyFormat2[2]*$dateMyFormat2[1]*$dateMyFormat2[0];

    if ($d - $dlog > 60) {
        try {
            $telegram = new Longman\TelegramBot\Telegram($banel1ng_termostat_bot_bot_api_key, $banel1ng_termostat_bot_username);
            $result = \Longman\TelegramBot\Request::sendMessage([
                'chat_id' => "-411683583",
                'text'    => $d - $dlog. 'не приходили новые данные' ,
            ]);

            $telegram->handle();
        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            echo $e->getMessage();
        }
    }
    $file = json_encode($file);


    file_put_contents('./log.json', $file);

}else {

    file_put_contents('./log.json', $data);
}
?>
