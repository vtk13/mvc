<?php
namespace Vtk13\Mvc\Http;

interface IRequest
{
    public function getHost();
    public function getPath();
    public function setPath($path);
    public function getParam($key = null); // GET params
    public function getMethod();
}
