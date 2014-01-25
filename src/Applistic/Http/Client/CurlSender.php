<?php

namespace Applistic\Http\Client;

use Applistic\Http\Request;
use Applistic\Http\Url;

/**
 * An Http request sender using cUrl.
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
class CurlSender extends AbstractSender
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================

    /**
     * The cURL resource.
     *
     * @var resource
     */
    protected $curl;

// ===== ACCESSORS =============================================================
// ===== CONSTRUCTOR ===========================================================
// ===== PUBLIC METHODS ========================================================

    /**
     * Prepares the sender.
     *
     * @return void
     */
    public function prepare()
    {
        $this->curl = curl_init();

        $url = $this->request->url();
        $parameters = $this->request->parameters()->toArray();
        $method = $this->request->method();

        switch ($method) {

            case Request::METHOD_GET:
            case Request::METHOD_DELETE:
            case Request::METHOD_HEAD:
                $url->setParameters($parameters);
                break;

            case Request::METHOD_POST:
                curl_setopt($this->curl, CURLOPT_POST, 1);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $parameters);
                break;

            case Request::METHOD_PUT:
                curl_setopt($this->curl, CURLOPT_POST, 1);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, Url::makeQueryString($parameters));
                break;

        }

        curl_setopt($this->curl, CURLOPT_URL, $url->build());
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->curl, CURLOPT_HEADER, true);
        curl_setopt($this->curl, CURLOPT_VERBOSE, true);

        $headers = $this->request->headers()->toArray();
        foreach ($headers as $key => $value) {
            $h = $key.": ".$value;
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $h);
        }
    }

    /**
     * Executes the request and returns the raw response.
     *
     * @return mixed
     */
    public function execute()
    {
        if (is_resource($this->curl)) {

            $response = curl_exec($this->curl);

            $info = curl_getinfo($this->curl);
            $headerSize = $info['header_size'];

            $this->finalize();

            if (!empty($response)) {

                return array(
                    'headers' => substr($response, 0, $headerSize),
                    'body'    => substr($response, $headerSize),
                    'info'    => $info,
                );

            }

        }
        return false;
    }

    /**
     * Finalize the execution by releasing any previously created resource.
     *
     * @return void
     */
    public function finalize()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
            $this->curl = null;
        }
    }

// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}