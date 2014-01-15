<?php

namespace Applistic\Http;

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
     * @var array
     */
    protected $headers = array();

    /**
     * The request parameters.
     *
     * @var array
     */
    protected $parameters = array();

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
     * Checks if a header is set.
     *
     * @return boolean
     */
    public function hasHeader($name)
    {
        return array_key_exists($name, $this->headers);
    }

    /**
     * Returns the headers.
     *
     * @return array
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
        if (array_key_exists($name, $this->headers)) {
            return $this->headers[$name];
        } else {
            return null;
        }
    }

    /**
     * Sets all headers.
     *
     * @param array $headers The new headers.
     * @return  Applistic\Http\Request
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array();

        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value);
        }

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
        if (!is_string($name)) {
            $message = "The header \$name must be a string.";
            throw new \InvalidArgumentException($message);
        }

        if (!is_string($value)) {
            $message = "The header \$value must be a string.";
            throw new \InvalidArgumentException($message);
        }

        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Checks if a parameter is set.
     *
     * @return boolean
     */
    public function hasParameter($name)
    {
        return array_key_exists($name, $this->parameters);
    }

    /**
     * Returns the parameters.
     *
     * @return array
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
        if (!is_string($name)) {
            $message = "The parameter \$name must be a string.";
            throw new \InvalidArgumentException($name);
        }

        if (array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        } else {
            return null;
        }
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
        if (!is_string($name)) {
            $message = "The parameter \$name must be a string.";
            throw new \InvalidArgumentException($name);
        }

        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * Sets all parameters.
     *
     * @param array $parameters The parameters.
     * @return  Applistic\Http\Request
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = array();

        foreach ($parameters as $key => $value) {
            $this->setParameter($key, $value);
        }

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
    public function setUrl($url)
    {
        if (is_null($this->url)) {
            $this->url = new Url($url);
        } else {
            $this->url->setUrl($url);
        }
    }

// ===== CONSTRUCTOR ===========================================================

    public function __construct($baseUrl = null)
    {
        $this->setUrl($baseUrl);
    }

// ===== PUBLIC METHODS ========================================================
// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}