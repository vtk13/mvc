<?php
namespace Vtk13\Mvc\Template;

class FileTemplate extends AbstractTemplate
{
    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function render(array $params = array())
    {
        extract(array_merge($this->params, $params));
        ob_start();
        /** @noinspection PhpIncludeInspection */
        include $this->filename;
        return ob_get_clean();
    }
}
