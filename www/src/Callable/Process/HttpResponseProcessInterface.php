<?php

/**
 * ProcessInterface.php
 * Service\Callable\Process\HttpResponseProcessInterface
 *
 * PHP version 7.4
 *
 * @category  Interface
 * @package   Service
 * @author    Federico Maffucci <m4ffucci@gmail.com>
 */

namespace Service\Callable\Process;

use Service\Callable\Process\ProcessInterface;
use Mikro\Response\HttpResponseInterface;

/**
 * Interfaccia processo risposta http
 *
 * @category  Interface
 * @package   Service
 * @author    Federico Maffucci <m4ffucci@gmail.com>
 */
interface HttpResponseProcessInterface extends ProcessInterface
{
    /** 
     * Esecuzione processo su response http
     * 
     * @param HttpResponseInterface $response Response http
     * 
     * @return HttpResponseInterface
     */
    public function __invoke(HttpResponseInterface $response): HttpResponseInterface;
}
