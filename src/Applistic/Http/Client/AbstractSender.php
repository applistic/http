<?php

namespace Applistic\Http\Client;

use Applistic\Http\Request;
use Applistic\Common\KeyValue;

/**
 * An abstract class for senders.
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
abstract class AbstractSender implements SenderInterface
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================

    /**
     * The request.
     *
     * @var Applistic\Http\Request
     */
    protected $request;

    /**
     * The response body.
     *
     * @var string
     */
    protected $responseBody;

    /**
     * The response headers.
     *
     * @var array
     */
    protected $responseHeaders;

    /**
     * The information about the loading.
     *
     * @var array
     */
    protected $loadInfo;

// ===== ACCESSORS =============================================================

    /**
     * Returns the request object.
     *
     * @return Applistic\Http\Request
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * Sets the request object.
     *
     * @param Applistic\Http\Request $request
     * @return void
     */
    public function setRequest(Applistic\Http\Request $request)
    {
        $this->request = $request;
    }

    /**
     * Returns the response body.
     *
     * @return string
     */
    public function responseBody()
    {
        return $this->responseBody();
    }

    /**
     * Returns the response headers.
     *
     * @return Applistic\Common\KeyValue
     */
    public function responseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * Returns information about the execution.
     *
     * @return Applistic\Common\KeyValue
     */
    public function loadInfo()
    {
        return $this->loadInfo;
    }

// ===== CONSTRUCTOR ===========================================================
// ===== PUBLIC METHODS ========================================================

    /**
     * Sends a request.
     *
     * Returns an array containing the following keys:
     *  - body    : The response body (string)
     *  - headers : The response headers (Applistic\Common\KeyValue)
     *  - info    : Information about the loading (Applistic\Common\KeyValue)
     *
     * Returns `false` in case of failure.
     *
     * @see  Applistic\Http\Request for available HTTP methods.
     *
     * @param  string $url        The url.
     * @param  string $method     The method
     * @param  array  $parameters Optional key/value array of parameters.
     * @param  array  $headers    Optional key/value array of headers.
     * @return array|boolean
     */
    public function send($url, $method = 'GET', array $parameters = null, array $headers = null)
    {
        $request = new Request();

        $request->setUrl($url)
                ->setMethod($method)
                ->setParameters($parameters)
                ->setHeaders($headers);

        return $this->sendRequest($request);
    }

    /**
     * Sends a request.
     *
     * Returns an array containing the following keys:
     *  - body    : The response body (string)
     *  - headers : The response headers (Applistic\Common\KeyValue)
     *  - info    : Information about the loading (Applistic\Common\KeyValue)
     *
     * Returns `false` in case of failure.
     *
     * @param  Applistic\Http\Request $request
     * @return array
     */
    public function sendRequest(Request $request)
    {
        $this->request = $request;

        $this->prepare();
        $response = $this->execute();
        $this->finalize();

        if (is_array($response)) {

            $this->responseHeaders = $this->makeKeyValueHeaders($response['headers']);
            $this->responseBody    = $response['body'];
            $this->loadInfo        = new KeyValue($response['info']);

            return array(
                'body'    => $this->responseBody,
                'headers' => $this->responseHeaders,
                'info'    => $this->loadInfo,
            );

        } else {

            return false;

        }
    }

    /**
     * Prepares the sender.
     *
     * @return void
     */
    public abstract function prepare();

    /**
     * Executes the request and returns the raw response.
     *
     * @return mixed
     */
    public abstract function execute();

    /**
     * Finalize the execution by releasing any previously created resource.
     *
     * @return void
     */
    public abstract function finalize();


// ===== PROTECTED METHODS =====================================================

    /**
     * Transforms the headers string into a KeyValue object.
     *
     * @param  string $headers
     * @return Applistic\Common\KeyValue
     */
    protected function makeKeyValueHeaders($headers)
    {
        if (is_string($headers)) {

            $h = new KeyValue();
            $lines = explode("\n", $headers);

            foreach ($lines as $line) {
                $line = trim($line);
                $parts = explode(":", $line);
                if (count($parts) == 2) {
                    $key   = trim($parts[0]);
                    $value = trim($parts[1]);
                    $h->set($key, $value);
                }
            }

            return $h;

        } elseif (is_array($headers)) {

            return new KeyValue($headers);

        } else {

            throw new \InvalidArgumentException("Unable to parse \$headers.");

        }
    }

// ===== PRIVATE METHODS =======================================================
}