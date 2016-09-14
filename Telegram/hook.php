<?php
set_time_limit(0);
$loader = require __DIR__.'/vendor/autoload.php';
echo "Niall Service starting...\n";

$environment = array_merge($_ENV, $_SERVER);
ksort($environment);

define("TELEGRAM_API_KEY", $environment['TELEGRAM_BOT_API_KEY']);
define("TELEGRAM_BOT_NAME", $environment['TELEGRAM_BOT_NAME']);
define("NIALL_INSTANCE", $environment['NIALL_INSTANCE']);

// Database Settings
if (isset($environment['DB_PORT'])) {
    $host = parse_url($environment['DB_PORT']);
    $credentials = array(
    'host'=>$host['host'],
    'user'=>$environment['DB_ENV_MYSQL_USER'],
    'password'=>$environment['DB_ENV_MYSQL_PASSWORD'],
    'database'=>$environment['DB_ENV_MYSQL_DATABASE']
    );
} else {
    die("no mysql config");
}

while (true) {
    try {
        // create Telegram API object
        $telegram = new Longman\TelegramBot\Telegram(TELEGRAM_API_KEY, TELEGRAM_BOT_NAME);
        $telegram->enableMySQL($credentials);
        $telegram->addCommandsPath(__DIR__ . "/commands");

        // handle telegram getUpdate request
        $telegram->handleGetUpdates();
    } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        // log telegram errors
        echo $e;
    }
}
