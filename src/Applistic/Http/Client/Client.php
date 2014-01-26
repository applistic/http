<?php

namespace Applistic\Http\Client;

use Applistic\Http\Request;
use Applistic\Http\Response;

/**
 * An Http client that sends and parse REST requests.
 *
 * @author Frederic Filosa <fred@applistic.com>
 * @copyright (c) 2014, Frederic Filosa
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Client
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
     * Type of the request sender.
     *
     * @var string
     */
    protected $senderType;

    /**
     * The request sender.
     *
     * @var Applistic\Http\Client\SenderInterface
     */
    protected $sender;

// ===== ACCESSORS =============================================================

    /**
     * Returns the request.
     *
     * @return Applistic\Http\Request
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * Returns the request's headers.
     *
     * @return \Applistic\Common\KeyValue
     */
    public function headers()
    {
        return $this->request->headers();
    }

    /**
     * Sets the request's headers.
     *
     * @param array $headers
     * @return  \Applistic\Http\Client\Client
     */
    public function setHeaders(array $headers)
    {
        $this->request->setHeaders($headers);
        return $this;
    }

    /**
     * Returns the request's parameters.
     *
     * @return \Applistic\Common\KeyValue
     */
    public function parameters()
    {
        return $this->request->parameters();
    }

    /**
     * Sets the request's parameters.
     *
     * @param array $parameters
     * @return  \Applistic\Http\Client\Client
     */
    public function setParameters(array $parameters)
    {
        $this->request->setParameters($parameters);
        return $this;
    }

    /**
     * Returns the sender.
     *
     * @return Applistic\Http\Client\SenderInterface
     */
    public function sender()
    {
        if (is_null($this->sender)) {

            switch ($this->senderType) {

                case 'curl':
                    $this->sender = new CurlSender();
                    break;

                case 'stream':
                default:
                    throw new \Exception("Unable to create an Http sender of type {$this->senderType}");
                    break;

            }

        }

        return $this->sender;
    }

// ===== CONSTRUCTOR ===========================================================

    /**
     * Constructs the Http client.
     *
     * @param string $baseUrl
     * @param string $senderType
     */
    public function __construct($baseUrl = null, $senderType = 'curl')
    {
        $this->request = new Request($baseUrl);
        $this->senderType = $senderType;
    }

// ===== PUBLIC METHODS ========================================================

    /**
     * Load the request by GET method.
     *
     * @return Applistic\Http\Response
     */
    public function get(array $parameters = null)
    {
        $this->request->setMethod(Request::METHOD_GET);
        return $this->executeRequest($this->request, $parameters);
    }

    /**
     * Load the request by POST method.
     *
     * @return Applistic\Http\Response
     */
    public function post(array $parameters = null)
    {
        $this->request->setMethod(Request::METHOD_POST);
        return $this->executeRequest($this->request, $parameters);
    }

    /**
     * Load the request by PUT method.
     *
     * @return Applistic\Http\Response
     */
    public function put(array $parameters = null)
    {
        $this->request->setMethod(Request::METHOD_PUT);
        return $this->executeRequest($this->request, $parameters);
    }

    /**
     * Load the request by DELETE method.
     *
     * @return Applistic\Http\Response
     */
    public function delete(array $parameters = null)
    {
        $this->request->setMethod(Request::METHOD_DELETE);
        return $this->executeRequest($this->request, $parameters);
    }

    /**
     * Executes a request and returns the response.
     *
     * @param  Request $request
     * @return Applistic\Http\Response
     */
    public function executeRequest(Request $request, array $parameters = null)
    {
        if (is_array($parameters)) {
            $this->request->setParameters($parameters);
        }

        $response = $this->sender()->sendRequest($this->request);
        return $this->makeResponse($response);
    }

// ===== PROTECTED METHODS =====================================================

    protected function makeResponse($response)
    {
        if (is_array($response)) {

            $r = new Response(null, $this->request);

            if (array_key_exists('body', $response)) {
                $r->body = $response['body'];
            }

            if (array_key_exists('headers', $response)) {
                $r->setHeaders($response['headers']);
            }

            if (array_key_exists('info', $response)) {
                $info = $response['info'];
                if ($info->has('http_code')) {
                    $r->setHttpStatus($info['http_code']);
                }
            }

            return $r;

        } elseif (is_string($response)) {

            $r = new Response();
            $r->body = $response;
            return $r;

        } else {

            return false;

        }
    }

// ===== PRIVATE METHODS =======================================================
}