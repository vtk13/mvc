<?php

class IncludeTestClass extends PHPUnit_Framework_TestCase
{
    public function testClasses()
    {
        $this->assertTrue(interface_exists(Vtk13\Mvc\Handlers\IHandler::class));
        $this->assertTrue(class_exists(Vtk13\Mvc\Handlers\AbstractController::class));
        $this->assertTrue(class_exists(Vtk13\Mvc\Handlers\ControllerRouter::class));
        $this->assertTrue(class_exists(Vtk13\Mvc\Exception\InvalidRouteException::class));
        $this->assertTrue(class_exists(Vtk13\Mvc\Exception\RouteNotFoundException::class));
    }
}
