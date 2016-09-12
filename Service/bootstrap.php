<?php

define("APP_ROOT", __DIR__);
define("APP_NAME", "Niall");
define("APP_CORE_NAME", "Niall\\Niall");
define("APP_START_MICROTIME", microtime(true));

require_once("vendor/autoload.php");

$environment = array_merge($_ENV, $_SERVER);
ksort($environment);
$host = parse_url($environment['MYSQL_PORT']);

$database = new \Thru\ActiveRecord\DatabaseLayer([
    'db_type' => 'Mysql',
    'db_hostname' => $host['host'],
    'db_port' => $host['port'],
    'db_username' => $environment['MYSQL_USERNAME'],
    'db_password' => $environment['MYSQL_PASSWORD'],
    'db_database' => $environment['MYSQL_DATABASE'],
]);

