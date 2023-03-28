<?php
require __DIR__ . '/vendor/autoload.php';
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\ContextProvider\CliContextProvider;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Dumper\ServerDumper;
use Symfony\Component\VarDumper\VarDumper;

$cloner = new VarCloner();
$fallbackDumper = \in_array(\PHP_SAPI, ['cli', 'phpdbg']) ? new CliDumper() : new HtmlDumper();
$dumper = new ServerDumper('tcp://127.0.0.1:9912', $fallbackDumper, [
    'cli' => new CliContextProvider(),
    'source' => new SourceContextProvider(),
]);

$banel1ng_termostat_bot_bot_api_key ="1918038811:AAHR8inopkXWmXQdKqVbZTQATMpAUdAQTX8";
$banel1ng_termostat_bot_username = 'banel1ng_termostat_bot';

try {
    $telegram = new Longman\TelegramBot\Telegram($banel1ng_termostat_bot_bot_api_key, $banel1ng_termostat_bot_username);

    $rs = file_get_contents('php://input');

    $jsonRs = json_decode($rs,true);

    $jsResponse = file_get_contents('./termCommand.json');
    $jsResponse = json_decode($jsResponse,true);
    file_put_contents('./test', json_encode($jsResponse));
    if ($jsonRs['message']['text'] == "debug") {
        $jsResponse['debug'] = !$jsResponse['debug'];
        file_put_contents('./termCommand.json', json_encode($jsResponse));
        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-1001753476179",
            'text'    => $jsResponse['debug'] ? 'установлен дебаг мод': 'дебаг мод отключен',
        ]);
    }

    if (false != strstr($jsonRs['message']['text'] , 'tmax-')
        && strlen ($jsonRs['message']['text']) >= 5
        && strlen ($jsonRs['message']['text']) <= 7) {

        $jsResponse['tmax'] = explode('-', $jsonRs['message']['text'] )[1];
        file_put_contents('./termCommand.json', json_encode($jsResponse));

        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-1001753476179",
            'text'    => 'максимальная температура отправлена на термостат ' . $jsResponse['tmax'] ,
        ]);
    }

    if (false != strstr($jsonRs['message']['text'] , 'tmin-')
        && strlen ($jsonRs['message']['text']) >= 5
        && strlen ($jsonRs['message']['text']) <= 7) {

        $jsResponse['tmin'] = explode('-', $jsonRs['message']['text'] )[1];
        file_put_contents('./termCommand.json', json_encode($jsResponse));

        $result = \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => "-1001753476179",
            'text'    => 'минимальная температура отправлена на термостат ' .  $jsResponse['tmin'],
        ]);
    }

    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    file_put_contents('./test', $e->getMessage());
}