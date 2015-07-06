<?php
namespace Vtk13\Mvc\Http;

interface IResponse
{
    public function getStatus();
    public function getStatusLine();
    public function getHeader($name, $default = null);
    public function getHeaders();
    public function getBody();
}
