<?php

/**
 * ProcessInterface.php
 * Service\Callable\Process\HttpRequestProcessInterface
 *
 * PHP version 7.4
 *
 * @category  Interface
 * @package   Service
 * @author    Federico Maffucci <m4ffucci@gmail.com>
 */

namespace Service\Callable\Process;

use Service\Callable\Process\ProcessInterface;
use Mikro\Request\HttpRequestInterface;

/**
 * Interfaccia processo richiesta http
 *
 * @category  Interface
 * @package   Service
 * @author    Federico Maffucci <m4ffucci@gmail.com>
 */
interface HttpRequestProcessInterface extends ProcessInterface
{
    /** 
     * Esecuzione processo su richiesta http
     * 
     * @param HttpRequestInterface $request Richiesta http
     * 
     * @return HttpRequestInterface
     */
    public function __invoke(HttpRequestInterface $request): HttpRequestInterface;
}
