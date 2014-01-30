<?php

namespace Applistic\Http\Client;

use Applistic\Http\Request;
use Applistic\Http\Response;

/**
 * An Http client that sends REST requests.
 *
 * This is basically a request that embed a sender.
 *
 * @author Frederic Filosa <fred@applistic.com>
 * @copyright (c) 2014, Frederic Filosa
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Client extends Request
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================

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
        parent::__construct($baseUrl);
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
        $this->setMethod(Request::METHOD_GET);
        return $this->executeRequest($this, $parameters);
    }

    /**
     * Load the request by POST method.
     *
     * @return Applistic\Http\Response
     */
    public function post(array $parameters = null)
    {
        $this->setMethod(Request::METHOD_POST);
        return $this->executeRequest($this, $parameters);
    }

    /**
     * Load the request by PUT method.
     *
     * @return Applistic\Http\Response
     */
    public function put(array $parameters = null)
    {
        $this->setMethod(Request::METHOD_PUT);
        return $this->executeRequest($this, $parameters);
    }

    /**
     * Load the request by DELETE method.
     *
     * @return Applistic\Http\Response
     */
    public function delete(array $parameters = null)
    {
        $this->setMethod(Request::METHOD_DELETE);
        return $this->executeRequest($this, $parameters);
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
            $request->setParameters($parameters);
        }

        $response = $this->sender()->sendRequest($request);
        return $this->makeResponse($response, $request);
    }

// ===== PROTECTED METHODS =====================================================

    protected function makeResponse($response, Request $request = null)
    {
        if (is_array($response)) {

            $r = new Response(null, $request);

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

            $r = new Response(null, $request);
            $r->body = $response;
            return $r;

        } else {

            return false;

        }
    }

// ===== PRIVATE METHODS =======================================================
}