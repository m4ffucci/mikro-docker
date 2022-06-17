<?php

/**
 * StartServiceHendler.php
 * Service\Callable\StartServiceHendler
 *
 * PHP version 7.4
 *
 * @category  Class
 * @package   Service
 * @author    Federico Maffucci <m4ffucci@gmail.com>
 */

namespace Service\Callable;

use Swoole\Http\Server;

/**
 * Gestore evento start servizio
 *
 * @category  Class
 * @package   Service
 * @author    Federico Maffucci <m4ffucci@gmail.com>
 */
class StartServiceHendler
{
    /**
     * Metodo callable
     * 
     * @param Server $server Istanza server Swoole
     * 
     * @return void
     */
    public function __invoke(Server $server): void
    {
        $message = "Mikro http server is started at %s://%s:%s\n";
        printf($message, SERVICE_PROTOCOL, SERVICE_HOST, SERVICE_PORT);
    }
}
