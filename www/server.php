#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once(__DIR__ . "/vendor/autoload.php");
include(__DIR__ . "/conf.php");

defined('SERVICE_PROTOCOL') or define('SERVICE_PROTOCOL', 'http');
defined('SERVICE_HOST') or define('SERVICE_HOST', '0.0.0.0');
defined('SERVICE_PORT') or define('SERVICE_PORT', 9501);
defined('SERVICE_DEMO_MODE') or define('SERVICE_DEMO_MODE', false);

if (SERVICE_DEMO_MODE) {
    # Se il servizio è in demo-mode viene applicato un postprocessore
    # che trasformerà il response nella pagina hello world di Mikro
    $helloworld = new Service\Callable\Process\HelloWorld;
    Service\Callable\HttpRequestHendler::addPostprocess($helloworld);
}

Mikro\Settings::yaml(__DIR__ . '/.mikrorc.yml');

use Swoole\Http\Server;
use Service\Callable\StartServiceHendler;
use Service\Callable\HttpRequestHendler;

$server = new Server(SERVICE_HOST, SERVICE_PORT, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);
$server->on("start", new StartServiceHendler());
$server->on("request", new HttpRequestHendler);
$server->start();