#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once(__DIR__ . "/vendor/autoload.php");
include(__DIR__ . "/conf.php");

defined('SERVICE_PROTOCOL') or define('SERVICE_PROTOCOL', 'http');
defined('SERVICE_HOST') or define('SERVICE_HOST', '0.0.0.0');
defined('SERVICE_PORT') or define('SERVICE_PORT', 9501);

Mikro\Settings::yaml(__DIR__ . '/.mikrorc.yml');

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Mikro\Response\FileResponseInterface;
use Mikro\Factory\ControllerFactory;
use Mikro\Exceptions\MikroException;
use Mikro\Factory\HttpResponseFactory;
use Mikro\Factory\FormatterFactory;
use Mikro\Adapter\Request\Swoole\HttpRequest;

$server = new Server(SERVICE_HOST, SERVICE_PORT, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

$server->on(
    "start", 
    function (Server $server) {
        printf("Swoole http server is started at %s://%s:%s\n", SERVICE_PROTOCOL, SERVICE_HOST, SERVICE_PORT);
    }
);

$server->on(
    "request",
    function (Request $request, Response $response) {
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Headers', 'Access-Control-Allow-Origin, Crossdomain, Content-Type, Origin');
        try {
            $serviceRequestHTTP = new HttpRequest($request);
            $controller = ControllerFactory::create($serviceRequestHTTP);
            $serviceResponse = $controller->run();
        } catch (\Exception $e) {
            $serviceResponse = HttpResponseFactory::create($e, SERVER_ERROR, FormatterFactory::create(TYPE_JSON));
            unset($e);
        }
        foreach ($serviceResponse->getHeaders() as $key => $value) {
            $response->header($key, $value);
        }
        $response->status($serviceResponse->getStatusCode(), $serviceResponse->getReasonPhrase());
        ($response instanceof FileResponseInterface) ? $response->sendfile($response->getFilePath()) : $response->end($serviceResponse);
    }
);

$server->start();