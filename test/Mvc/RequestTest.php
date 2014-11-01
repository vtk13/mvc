<?php

use Vtk13\Mvc\Http\Request;

class RequestTestClass extends PHPUnit_Framework_TestCase
{
    public function testCreateFromGlobals()
    {
        $_REQUEST['a'] = 1;

        $old = $_SERVER;
        $_SERVER['REQUEST_URI']     = '/index.php';
        $_SERVER['HTTP_HOST']       = 'awesome-host.com';
        $_SERVER['REQUEST_METHOD']  = 'POST';

        $requests = Request::createFromGlobals();
        $this->assertEquals($_SERVER['HTTP_HOST'], $requests->getHost());
        $this->assertEquals('/index.php', $requests->getPath());
        $this->assertEquals('1', $requests->getParam('a'));
        $this->assertEquals($_SERVER['REQUEST_METHOD'], $requests->getMethod());
        $_SERVER = $old;
    }
}
