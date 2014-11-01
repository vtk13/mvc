<?php
namespace Vtk13\Mvc\Template;

class LayoutTemplate extends AbstractTemplate
{
    protected $layout, $template;

    public function __construct($layout, $template)
    {
        $this->layout = $layout;
        $this->template = $template;

        $this->setParam('view', $this);
    }

    /**
     *
     * @param $filename
     * @param array $params
     * @return string
     */
    public function includeTemplate($filename, $params = array())
    {
        $tpl = new FileTemplate($filename);
        return $tpl->render(array_merge($this->params, $params));
    }

    public function render(array $params = array())
    {
        $params['content'] = $this->includeTemplate($this->template, $params);
        return $this->includeTemplate($this->layout, $params);
    }
}
