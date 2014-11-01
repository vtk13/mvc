<?php
namespace Vtk13\Mvc\Http;

class Request implements IRequest
{
    protected $host;
    protected $path;
    protected $query;
    protected $method;

    public function __construct($host = null, $path = '/', array $query = array(), $method = 'GET')
    {
        $this->host = $host;
        $this->path = $path;
        $this->query = $query;
        $this->method = $method;
    }

    public static function createFromGlobals()
    {
        $uri = parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null);
        $request = new self(
            isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null,
            $uri['path'],
            $_REQUEST);
        $request->setMethod(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null);
        return $request;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getParam($key = null, $default = null)
    {
        if ($key) {
            return isset($this->query[$key]) ? $this->query[$key] : $default;
        } else {
            return $this->query;
        }
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($value)
    {
        $this->method = $value;
    }

    public function uri(array $addons = array())
    {
        if ($this->query || $addons) {
            $query = '?' . http_build_query(array_merge($this->query, $addons));
        } else {
            $query = '';
        }
        return $this->path . $query;
    }
}
