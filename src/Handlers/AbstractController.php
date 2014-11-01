<?php
namespace Vtk13\Mvc\Handlers;

use Exception;
use Vtk13\Mvc\Http\IRequest;
use Vtk13\Mvc\Http\IResponse;
use Vtk13\Mvc\Http\Response;
use Vtk13\Mvc\Exception\RouteNotFoundException;
use Vtk13\Mvc\Template\LayoutTemplate;

class AbstractController implements IHandler
{
    protected $name;

    protected $defaultAction = 'index';

    /**
     * @var IRequest
     */
    protected $request;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function handle(IRequest $request, array $params = array(), $prefix = '')
    {
        $this->request = $request;

        $name = isset($params[0]) ? array_shift($params) : $this->name;
        try {
            if ($name != $this->name) {
                $res = $this->handleError(404, new RouteNotFoundException("Controller {$name} not found"));
                $action = '404';
            } else {
                $action = isset($params[0]) ? array_shift($params) : $this->defaultAction;
                $action = str_replace('-', '_', $action);

                $res = $this->beforeHandle($action, $params);
                if ($res === null) {
                    $call = array($this, $action . $request->getMethod());
                    if (is_callable($call)) {
                        $res = call_user_func_array($call, $params);
                    } else {
                        $ex = new RouteNotFoundException("Action {$name}::{$action} not found");
                        $res = $this->handleError(404, $ex);
                    }
                }
            }
        } catch (Exception $ex) {
            $res = $this->handleError(500, $ex);
            $action = '500';
        }

        return $this->actionResultToResponse($res, $prefix, $action);
    }

    protected function beforeHandle($action, $params)
    {
        return null;
    }

    /**
     * @param $code
     * @param Exception $ex
     * @return IResponse
     */
    protected function handleError($code, Exception $ex)
    {
        switch ($code) {
            case 404:
                return new Response('Not Found', 404);
            default:
                return new Response($ex->getMessage(), 500);
        }
    }

    public function actionResultToResponse($result, $templatePath, $action)
    {
        if ($result instanceof IResponse) {
            return $result;
        } else if (is_array($result) || is_null($result)) {
            $view = $this->getView($templatePath, $action);
            $view->setParams((array)$result);
            return new Response($view->render());
        } else if (is_string($result)) {
            return new Response($result);
        } else {
            throw new \Exception('Invalid result type:' . gettype($result));
        }
    }

    public function getView($templatePath, $action, $layout = 'site.layout')
    {
        $templateReference = trim($templatePath, '/') . "/{$this->name}/{$action}";
        return new LayoutTemplate(
            $this->getTemplateFile($layout),
            $this->getTemplateFile($templateReference)
        );
    }

    public function getTemplateFile($templateReference)
    {
        $templateReference = trim($templateReference, '/');
        $file = "templates/{$templateReference}.php";
        if (($abs = stream_resolve_include_path($file))) {
            return $abs;
        }
        throw new Exception("Template {$templateReference} not found");
    }
}
