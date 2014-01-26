<?php

namespace Applistic\Http;

/**
 * A class to create and modify URLs.
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
class Url
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================

    /**
     * Transforms an array of parameters into a url encoded query string.
     *
     * @param  array  $parameters
     * @return string
     */
    public static function makeQueryString(array $parameters)
    {
        $p = array();
        foreach ($parameters as $key => $value) {
            $encoded = urlencode($value);
            $p[] = "{$key}={$encoded}";
        }
        return implode("&", $p);
    }

// ===== PROPERTIES ============================================================

    /**
     * The URL components.
     *
     * @var array
     */
    protected $components = array();

    /**
     * The query string parameters.
     *
     * @var array
     */
    protected $parameters = array();

    /**
     * The path components.
     *
     * @var array
     */
    protected $path = array();


// ===== ACCESSORS =============================================================
// ===== CONSTRUCTOR ===========================================================

    public function __construct($url = null)
    {
        if (is_string($url)) {
            $this->setUrl($url);
        }
    }

// ===== PUBLIC METHODS ========================================================

    /**
     * Returns the built URL.
     *
     * @return string
     */
    public function build()
    {
        if (function_exists('http_build_url')) {

            return http_build_url($this->components);

        } else {

            $url = array();

            // Here come the ugly checks...

            if (array_key_exists('scheme', $this->components)) {
                $url[] = $this->components['scheme']."://";
            }

            if (array_key_exists('user', $this->components)) {
                $url[] = $this->components['user'];
                if (array_key_exists('pass', $this->components)) {
                    $url[] = ":".$this->components['pass'];
                }
                $url[] = "@";
            }

            if (array_key_exists('host', $this->components)) {
                $url[] = $this->components['host'];
                if (array_key_exists('port', $this->components)) {
                    $url[] = ":".$this->components['port'];
                }
            }

            if (array_key_exists('path', $this->components)) {
                $url[] = $this->components['path'];
            } else {
                $url[] = "/";
            }

            $queryString = $this->queryString();
            if (!empty($queryString)) {
                $url[] = "?".$queryString;
            }

            if (array_key_exists('fragment', $this->components)) {
                $url[] = "#".$this->components['fragment'];
            }

            return implode("", $url);
        }
    }

    /**
     * Sets the URL.
     *
     * @param string $url The new URL.
     * @return  boolean
     */
    public function setUrl($url)
    {
        if (!is_string($url)) {
            $message = "The \$url must be a string.";
            throw new \InvalidArgumentArgument($message);
        }

        $components = parse_url($url);

        if (is_array($components)) {

            $this->components = $components;

            if (array_key_exists('query', $this->components)) {
                $this->setQueryString($this->components['query']);
            }

            if (array_key_exists('path', $this->components)) {
                $this->setPath($this->components['path']);
            }

            return true;

        } else {

            return false;

        }
    }

    /**
     * Returns the assembled query string.
     *
     * @return string
     */
    public function queryString()
    {
        return static::makeQueryString($this->parameters);
    }

    /**
     * Sets all the parameters of the query string.
     *
     * @param string $queryString
     * @return  boolean
     */
    public function setQueryString($queryString)
    {
        if (is_string($queryString)) {

            $keyValues  = explode("&", $queryString);
            $parameters = array();

            foreach ($keyValues as $keyValue) {
                $kv = explode("=", $keyValue);
                if (count($kv) == 2) {
                    $parameters[$kv[0]] = $kv[1];
                } elseif (count($kv) == 1) {
                    $parameters[$kv[0]] = null;
                }
            }

            $this->parameters = $parameters;
            $this->updateComponentsQueryString();

            return true;

        } else {

            return false;

        }
    }

    /**
     * Returns the query string parameters.
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
     */
    public function parameter($name)
    {
        if (array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        } else {
            return null;
        }
    }

    /**
     * Sets a named parameter to include in the query string.
     *
     * @param string $name
     * @param string|numeric $value
     * @return  boolean
     */
    public function setParameter($name, $value)
    {
        if (is_string($name) && (is_string($value) || is_numeric($value))) {
            $this->parameters[$name] = $value;
            $this->updateComponentsQueryString();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sets many parameters to include in the query string.
     *
     * @param array $parameters
     * @return  boolean
     */
    public function setParameters(array $parameters = null)
    {
        if (is_null($parameters)) {

            $this->parameters = array();

        } else {

            $p = array();

            foreach ($parameters as $name => $value) {
                if (is_string($name) && (is_string($value) || is_numeric($value))) {
                    $p[$name] = $value;
                } else {
                    return false;
                }
            }

            $this->parameters = array_merge($this->parameters, $p);

        }

        $this->updateComponentsQueryString();

        return true;
    }

    /**
     * Returns true if the protocol used is https.
     *
     * @return boolean
     */
    public function isSecure()
    {
        if (array_key_exists('scheme', $this->components)) {
            return (strtolower($this->components['scheme']) == 'https');
        } else {
            return false;
        }
    }

    /**
     * Returns true if the user name/id is set.
     *
     * Since the password alone doesn't represent a user's credentials, we only
     * check for the presence of the user name/id.
     *
     * @return boolean
     */
    public function hasCredentials()
    {
        return array_key_exists('user', $this->components);
    }

    /**
     * Returns the user credentials.
     *
     * This method always returns an array with the following keys:
     * - user : the user name/id
     * - password : the password
     *
     * When user or password is not set, the value is `null`.
     *
     * If you want to check if the credentials exist, you can use
     * the hasCredentials() method.
     *
     * @return array
     */
    public function credentials()
    {
        $c = $this->components;

        $credentials = array();
        $credentials['user']     = (array_key_exists('user', $c) ? $c['user'] : null);
        $credentials['password'] = (array_key_exists('pass', $c) ? $c['pass'] : null);

        return $credentials;
    }

    /**
     * Sets the user credentials.
     *
     * @param string $user
     * @param string $password
     * @return  boolean
     */
    public function setCredentials($user, $password = null)
    {
        if (is_string($user)) {

            $this->components['user'] = urlencode($user);

            if (is_string($password)) {
                $this->components['pass'] = urlencode($password);
            }

            return true;

        } else {

            return false;

        }
    }

    /**
     * Returns the path component.
     *
     * @return string
     */
    public function path()
    {
        return "/".implode("/", $this->path);
    }

    /**
     * Returns the path segment at the provided $index.
     *
     * The first index is 0 (zero).
     * Returns `null` is $index is out of bounds.
     *
     * @param  int $index
     * @return string
     */
    public function pathSegment($index)
    {
        if (!is_int($index)) {
            $message = "\$index must be a positive integer.";
            throw new \InvalidArgumentException($message);
        }

        if (($index >= 0) && (count($this->path) > $index)) {
            return $this->path[$index];
        } else {
            return null;
        }
    }

    /**
     * Sets the path.
     *
     * If $path is `null`, the path becomes empty.
     *
     * @param string|array $path
     * @return  boolean
     */
    public function setPath($path = null)
    {
        if (is_string($path)) {

            $path = explode("/", $this->trimmedPathSegment($path));

        } else if (is_null($path)) {

            $this->path = array();
            $this->updateComponentsPath();

        }

        if (is_array($path)) {

            $this->path = array();
            foreach ($path as $segment) {
                $this->addPathSegment($segment);
            }
            return true;

         } else {

            return false;

        }
    }

    /**
     * Appends a path segment.
     *
     * @param mixed $segment
     * @return  boolean
     */
    public function addPathSegment($segment)
    {
        if (is_string($segment) || is_numeric($segment)) {
            $s = $this->trimmedPathSegment($segment);
            if (!empty($s)) {
                $this->path[] = $segment;
                $this->updateComponentsPath();
                return true;
            }
        }

        return false;
    }

    /**
     * Removes a segment from the path.
     *
     * @param int $index
     * @return  boolean
     */
    public function removePathSegment($index)
    {
        if (is_int($index) && ($index >= 0) && ($index < count($this->path))) {

            array_splice($this->path, $index, 1);
            // $path = array();
            // for ($i = 0; $i < count($this->path); $i++) {
            //     if ($i != $index) {
            //         $path[] = $this->path[$i];
            //     }
            // }

            // $this->path = $path;
            $this->updateComponentsPath();

            return true;

        } else {

            return false;

        }
    }

    /**
     * Returns the anchor.
     *
     * @return string
     */
    public function anchor()
    {
        if (array_key_exists('fragment', $this->components)) {
            return $this->components['fragment'];
        } else {
            return null;
        }
    }

    /**
     * Sets the anchor.
     *
     * Pass `null` values to remove the anchor.
     *
     * @param string $value
     * @return  boolean
     */
    public function setAnchor($value = null)
    {
        if (is_string($value)) {

            $trimmed = trim($value, " 　\t\n\r\0\x0B");
            if (!empty($trimmed)) {
                $this->components['fragment'] = $trimmed;
            } else {
                unset($this->components['fragment']);
            }

            return true;

        } elseif (is_null($value)) {

            unset($this->components['fragment']);
            return true;

        } else {

            return false;

        }
    }

// ===== PROTECTED METHODS =====================================================

    /**
     * Updates the query string to the components array.
     *
     * @return void
     */
    protected function updateComponentsQueryString()
    {
        $this->components['query'] = $this->queryString();
    }

    /**
     * Updates the path to the components array.
     *
     * @return void
     */
    protected function updateComponentsPath()
    {
        $this->components['path'] = $this->path();
    }

    /**
     * Returns a trimmed path segment (or full path).
     *
     * The method also trim slashes.
     *
     * @return string
     */
    protected function trimmedPathSegment($segment)
    {
        return trim($segment, " 　\t\n\r\0\x0B\/");
    }

// ===== PRIVATE METHODS =======================================================
}