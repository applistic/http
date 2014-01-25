<?php

namespace Applistic\Http\Client;

use Applistic\Http\Request;

/**
 * Interface for Http request senders.
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
interface SenderInterface
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================
// ===== ACCESSORS =============================================================
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
     * @param  string $url        The url.
     * @param  string $method     The method (HEAD|GET|POST|PUT|PATCH|DELETE)
     * @param  array  $parameters Optional key/value array of parameters.
     * @param  array  $headers    Optional key/value array of headers.
     * @return array
     */
    public function send($url, $method = 'GET', array $parameters = null, array $headers = null);

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
    public function sendRequest(Request $request);

// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}