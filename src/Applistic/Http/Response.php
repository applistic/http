<?php

namespace Applistic\Http;

use Applistic\Common\KeyValue;

/**
 * An Http response.
 *
 * @author Frederic Filosa <fred@applistic.com>
 * @copyright (c) 2014, Frederic Filosa
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Response
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================

    /**
     * The response body.
     *
     * @var string
     */
    public $body;

    /**
     * The succes flag.
     *
     * @var boolean
     */
    public $success = false;

    /**
     * The request.
     *
     * @var Applistic\Http\Request
     */
    protected $request;

    /**
     * The http status code.
     *
     * @var int
     */
    protected $httpStatus;

    /**
     * The response headers.
     *
     * @var Applistic\Common\KeyValue
     */
    protected $headers;



// ===== ACCESSORS =============================================================

    /**
     * Returns the headers.
     *
     * @return Applistic\Common\KeyValue
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * Sets the headers.
     *
     * @param mixed $headers The headers must be of one of the following types:
     *                       - Applistic\Common\KeyValue
     *                       - array
     *                       - null (resets the headers)
     * @return Applistic\Http\Response
     * @throws \InvalidArgumentException If $headers is of invalid type.
     */
    public function setHeaders($headers)
    {
        if ($headers instanceof \Applistic\Common\KeyValue) {
            $this->headers = $headers;
        } elseif (is_array($headers) || is_null($headers)) {
            $this->headers = new KeyValue($headers);
        } else {
            $message = "\$headers must be either a KeyValue, an array or null.";
            throw new \InvalidArgumentException($message);
        }

        return $this;
    }

    /**
     * Returns the Http status.
     *
     * @return int
     */
    public function httpStatus()
    {
        return $this->httpStatus;
    }

    /**
     * Sets the Http status code.
     *
     * If the $value is null, the Http status is set to `null` and
     * the success flag is reset to `false`.
     *
     * If the $value is an integer, the success flag is also set to true or false,
     * depending on the Http status code (true: between 200 and 299).
     *
     * @param int $value
     * @return Applistic\Http\Response
     * @throws \InvalidArgumentException If $value is neither an integer nor null.
     */
    public function setHttpStatus($value = null)
    {
        if (is_int($value) && ($value > 0)) {

            $this->httpStatus = $value;
            $this->success = (($value >= 200) && ($value < 300));

        } elseif (is_null($value)) {

            $this->httpStatus = null;
            $this->success = false;

        } else {

            $message = "The httpStatus must be a positive integer.";
            throw new \InvalidArgumentException($messge);

        }

        return $this;
    }

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
     * Sets the request.
     *
     * @param Applistic\Http\Request $request
     * @return Applistic\Http\Response
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
        return $this;
    }

// ===== CONSTRUCTOR ===========================================================

    /**
     * Constructs the response.
     *
     * @param int $httpStatus The response's Http status code.
     * @param Applistic\Http\Request $request Optional request corresponding to
     *                                        this response.
     */
    public function __construct($httpStatus = null, Request $request = null)
    {
        $this->setHttpStatus($httpStatus);
        $this->request = $request;
    }

// ===== PUBLIC METHODS ========================================================

    /**
     * Returns the response body as a JSON decoded object or array.
     *
     * @param boolean $asArray Converts objects into arrays. Default is true.
     * @return mixed
     */
    public function toJson($asArray = true)
    {
        if (is_string($this->body)) {
            return json_decode($this->body, $asArray);
        } else {
            return null;
        }
    }

// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}