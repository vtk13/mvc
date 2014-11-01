<?php
namespace Vtk13\Mvc\Handlers;

use Vtk13\Mvc\Http\IRequest;

class ControllerRouter implements IHandler
{
    protected $namespace, $path;

    /**
     * @var string
     */
    protected $defaultController;

    public function __construct($namespace = '', $path = '/', $defaultController = 'index')
    {
        $this->namespace = $namespace;
        $this->path = $path;
        $this->defaultController = $defaultController;
    }

    protected function parsePath($path)
    {
        // trim path prefix
        $path = preg_replace("~^{$this->path}~", '', $path);
        $path = trim($path, '/');
        $parts = explode('/', $path);
        foreach ($parts as $k => $v) {
            // decode UTF-8 chars
            $parts[$k] = urldecode($v);
        }
        $parts[0] = empty($parts[0]) ? $this->defaultController : strtolower(str_replace('-', '_', $parts[0]));
        return $parts;
    }

    /**
     * @param $namespace
     * @param $controller
     * @return AbstractController
     */
    protected function controllerFactory($namespace, $controller)
    {
        $className = ($namespace ? $namespace . '\\' : '') . ucfirst($controller) . 'Controller';
        if (class_exists($className)) {
            return new $className();
        } else {
            $className = ($namespace ? $namespace . '\\' : '') . ucfirst($this->defaultController) . 'Controller';
            return new $className();
        }
    }

    public function handle(IRequest $request)
    {
        if (strpos($request->getPath(), $this->path) !== 0) {
            return null;
        }
        $params = $this->parsePath($request->getPath());
        $controller = $this->controllerFactory($this->namespace, $params[0]);
        return $controller->handle($request, $params, $this->path);
    }
}
