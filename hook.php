<?php
 ;
// Load composer
require __DIR__ . '/vendor/autoload.php';
$bot_api_key  = '1845276413:AAG0BJt1zIxMy18OvvH_c31NOB2asnCQ7XY';
$bot_username = 'pathePolivBot';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    $rs = file_get_contents('php://input');
    $jsonRs = json_decode($rs,true);
    if ($jsonRs['message']['text'] === "включить полив") {
        file_put_contents('./tg.logs', 1);
        $str = 'Начал поливать';
        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-457457807",
            'text'    => $str,
        ]);
    }
    else if ($jsonRs['message']['text'] === "выключить полив") {
        file_put_contents('./tg.logs', 0);
        $str = 'Закончил полив';
        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-457457807",
            'text'    => $str,
        ]);
    }
    //dump($result);
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}