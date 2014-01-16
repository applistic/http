<?php

namespace Applistic\Http\Client;

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
     *  - headers : The response headers (key/value array)
     *  - body    : The response body
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

// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}