<?php
namespace Vtk13\Mvc\Template;

interface ITemplate
{
    public function setParam($name, $value);
    public function setParams(array $params);
    public function render(array $params = array());
}
