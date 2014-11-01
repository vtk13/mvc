<?php
namespace Vtk13\Mvc\Template;

abstract class AbstractTemplate implements ITemplate
{
    protected $params = array();

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    public function setParams(array $params)
    {
        foreach ($params as $name => $value) {
            $this->params[$name] = $value;
        }
    }
}
