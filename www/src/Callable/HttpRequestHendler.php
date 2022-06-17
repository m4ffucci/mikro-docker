<?php

/**
 * HttpRequestHendler.php
 * Service\Callable\HttpRequestHendler
 *
 * PHP version 7.4
 *
 * @category  Class
 * @package   Service
 * @author    Federico Maffucci <m4ffucci@gmail.com>
 */

namespace Service\Callable;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Mikro\Adapter\Request\Swoole\HttpRequest;
use Mikro\Response\HttpResponseInterface;
use Mikro\Response\FileResponseInterface;
use Mikro\Factory\ControllerFactory;
use Mikro\Factory\FormatterFactory;
use Mikro\Factory\HttpResponseFactory;
use Service\Callable\Process\HttpRequestProcessInterface;
use Service\Callable\Process\HttpResponseProcessInterface;

/**
 * Gestore di richieste HTTP
 *
 * @category  Class
 * @package   Service
 * @author    Federico Maffucci <m4ffucci@gmail.com>
 */
class HttpRequestHendler
{
    /**
     * Questa proprietà può contenere delle istanze callable
     * di tipo HttpRequestProcessInterface. Le istanze di questo
     * tipo verranno eseguite uno dopo l'altra sulla richiesta in ingresso.
     * 
     * @var HttpRequestProcessInterface[] $preprocesses Collezione di processi
     */
    private static array $preprocesses = [];

    /**
     * Questa proprietà può contenere delle istanze callable
     * di tipo HttpResponseProcessInterface. Le istanze di questo
     * tipo verranno eseguite uno dopo l'altra sulla richiesta in ingresso.
     * 
     * @var HttpResponseProcessInterface[] $preprocesses Collezione di processi
     */
    private static array $postprocesses = [];

    /**
     * Assegnazione processo a elenco di preprocessi
     * 
     * @param HttpRequestProcessInterface $preprocess Preprocesso
     * 
     * @return void
     */
    public static function addPreprocess(HttpRequestProcessInterface $preprocess): void
    {
        self::$preprocesses[] = $preprocess;
    }

    /**
     * Assegnazione processo a elenco di postprocessi
     * 
     * @param HttpResponseProcessInterface $postprocess Postprocesso
     * 
     * @return void
     */
    public static function addPostprocess(HttpResponseProcessInterface $postprocess): void
    {
        self::$postprocesses[] = $postprocess;
    }

    /**
     * Metodo callable
     * 
     * @param Request  $request Istanza richiesta HTTP Swoole
     * @param Response $response Istanza response HTTP Swoole
     * 
     * @return void
     */
    public function __invoke(Request $request, Response $response)
    {
        $this->writeDefaultHeaders($response);
        try {
            $httpRequest = new HttpRequest($request);
            
            # ===========================================
            # ESECUZIONE PREPROCESSI
            # ===========================================
            foreach (self::$preprocesses as $process) {
                $httpRequest = $process($httpRequest);
            }
            # ===========================================
            $controller = ControllerFactory::create($httpRequest);
            $httpResponse = $controller->run();

        } catch (\Exception $error) {
            $formatter = FormatterFactory::create(TYPE_JSON);
            $httpResponse = HttpResponseFactory::create($error, SERVER_ERROR, $formatter);
            unset($error);
        }

        # ===========================================
        # ESECUZIONE POSTPROCESSI
        # ===========================================
        foreach (self::$postprocesses as $process) {
            $httpResponse = $process($httpResponse);
        }
        # ===========================================

        $this->outputResponse($httpResponse, $response);
    }

    /**
     * Assegnzione headers di default
     * 
     * @param Response $response Istanza Response Swoole
     * 
     * @return void
     */
    protected function writeDefaultHeaders(Response $response): void
    {
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Headers', 'Access-Control-Allow-Origin, Crossdomain, Content-Type, Origin');
    }

    /**
     * Processo di output
     * 
     * @param HttpResponseInterface $httpResponse Istanza Response Mikro
     * @param Response $response Istanza Response Swoole
     * 
     * @return void
     */
    protected function outputResponse(HttpResponseInterface $httpResponse, Response $response): void
    {
        foreach ($httpResponse->getHeaders() as $key => $value) {
            $response->header($key, $value);
        }
        $statusCode = $httpResponse->getStatusCode();
        $phrase = $httpResponse->getReasonPhrase();
        $response->status($statusCode, $phrase);
        if ($response instanceof FileResponseInterface) {
            $response->sendfile($response->getFilePath());
        }
        $response->end($httpResponse);
    }
}
