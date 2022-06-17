<?php

/**
 * HtmlHttpResponse.php
 * Service\Response\HtmlHttpResponse
 *
 * PHP version 7.4
 *
 * @category  Class
 * @package   Service
 * @author    Federico Maffucci <m4ffucci@gmail.com>
 */

namespace Service\Response;

use Mikro\Response\StringHttpResponse;

/**
 * Implementazione concreta risposta HTTP formato stringa
 *
 * @category  Class
 * @package   Service
 * @author    Federico Maffucci <m4ffucci@gmail.com>
 */
class HtmlHttpResponse extends StringHttpResponse
{
    /**
     * Assegnazione valori Headers di default
     * Metodo dedicato alla definizione degli headers di risposta.
     * Questi valori verranno direttamnete implementati nelle estensioni
     * concrete della classe in oggetto.
     *
     * @return void
     */
    protected function setHeaders(): void
    {
        parent::setHeaders();
        $this->headers['Content-Type'] = 'text/html; charset=utf-8';
    }
}
