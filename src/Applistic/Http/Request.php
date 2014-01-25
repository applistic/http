<?php

namespace Applistic\Http;

use Applistic\Common\KeyValue;

/**
 * An Http request.
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
class Request
{
// ===== CONSTANTS =============================================================

    const METHOD_HEAD   = "HEAD";
    const METHOD_GET    = "GET";
    const METHOD_POST   = "POST";
    const METHOD_PUT    = "PUT";
    const METHOD_PATCH  = "PATCH";
    const METHOD_DELETE = "DELETE";

// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================

    /**
     * Returns an array of valid methods.
     *
     * @return array
     */
    public static function validMethods()
    {
        return array(
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_DELETE,
            self::METHOD_PATCH,
            self::METHOD_HEAD,
        );
    }

// ===== PROPERTIES ============================================================

    /**
     * The request headers.
     *
     * @var Applistic\Common\KeyValue
     */
    protected $headers;

    /**
     * The request parameters.
     *
     * @var Applistic\Common\KeyValue
     */
    protected $parameters;

    /**
     * The request method.
     *
     * @var string
     */
    protected $method;

    /**
     * The destination url object.
     *
     * @var Applistic\Http\Url
     */
    protected $url;


// ===== ACCESSORS =============================================================

    /**
     * Returns the method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the method.
     *
     * @param string $method One of the methods defined in METHOD_* constants.
     * @return  Applistic\Http\Request
     */
    public function setMethod($method)
    {
        $validMethods = static::validMethods();

        if (!in_array($method, $validMethods)) {
            $message = "\$method must be one of the following: ".implode(",", $validMethods);
            throw new \InvalidArgumentException($message);
        }

        $this->method = $method;

        return $this;
    }

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
     * Returns a named header.
     *
     * @param  string $name The header name.
     * @return string
     */
    public function header($name)
    {
        return $this->headers->get($name);
    }

    /**
     * Sets all headers.
     *
     * @param array $headers The new headers.
     * @return  Applistic\Http\Request
     */
    public function setHeaders(array $headers = null)
    {
        $this->headers = new KeyValue($headers);
        return $this;
    }

    /**
     * Sets one header.
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     * @return  Applistic\Http\Request
     */
    public function setHeader($name, $value)
    {
        $this->headers->set($name, $value);
        return $this;
    }

    /**
     * Returns the parameters.
     *
     * @return Applistic\Common\KeyValue
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * Returns a named parameter.
     *
     * @param  string $name The parameter name.
     * @return mixed
     * @throws \InvalidArgumentException If $name is not a string.
     */
    public function parameter($name)
    {
        return $this->parameters->get($name);
    }

    /**
     * Sets a parameter.
     *
     * @param string $name  The parameter name.
     * @param mixed  $value The parameter value.
     * @return  Applistic\Http\Request
     * @throws \InvalidArgumentException If $name is not a string.
     */
    public function setParameter($name, $value)
    {
        $this->parameters->set($name, $value);
        return $this;
    }

    /**
     * Sets all parameters.
     *
     * @param array $parameters The parameters.
     * @return  Applistic\Http\Request
     */
    public function setParameters(array $parameters = null)
    {
        $this->parameters = new KeyValue($parameters);
        return $this;
    }

    /**
     * Returns the url object.
     *
     * Call the build() method to get the URL string:
     *
     *     $url = $request->url()->build();
     *
     * @return Applistic\Http\Url
     */
    public function url()
    {
        if (!is_null($this->url)) {
            return $this->url;
        } else {
            return null;
        }
    }

    /**
     * Sets the url.
     *
     * @param string $url
     */
    public function setUrl($url = null)
    {
        if (is_null($this->url)) {
            $this->url = new Url($url);
        } else {
            $this->url->setUrl($url);
        }

        return $this;
    }

// ===== CONSTRUCTOR ===========================================================

    public function __construct($baseUrl = null)
    {
        $this->setUrl($baseUrl);
        $this->headers = new KeyValue();
        $this->parameters = new KeyValue();
    }

// ===== PUBLIC METHODS ========================================================
// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}