<?php
namespace Vtk13\Mvc\Handlers;

use Exception;
use Interop\Container\ContainerInterface;
use Vtk13\Mvc\Exception\RouteNotFoundException;
use Vtk13\Mvc\Http\IRequest;

class ControllerRouter implements IHandler
{
    protected $namespace, $pathPrefix;

    /**
     * @var string
     */
    protected $defaultController;

    /**
     * @var ContainerInterface
     */
    protected $di;

    public function __construct(
        ContainerInterface $di,
        $namespace = '',
        $pathPrefix = '/',
        $defaultController = 'index'
    ) {
        if ($namespace && substr($namespace, -1) != '\\') {
            throw new Exception("Namespace {$namespace} must end with '\\'");
        }
        $this->di = $di;
        $this->namespace = $namespace;
        $this->pathPrefix = $pathPrefix;
        $this->defaultController = $defaultController;
    }

    protected function parsePath($path)
    {
        // trim path prefix
        $path = preg_replace("~^{$this->pathPrefix}~", '', $path);
        $path = trim($path, '/');
        if ($path === '') {
            return [];
        } else {
            $parts = explode('/', $path);
            foreach ($parts as $k => $v) {
                // decode UTF-8 chars
                $parts[$k] = urldecode($v);
            }
            return $parts;
        }
    }

    /**
     * @param string $namespace
     * @param string $controller
     * @return AbstractController|null
     */
    protected function controllerFactory($namespace, $controller)
    {
        // translate some-name to SomeName
        $controller = implode('', array_map('ucfirst', explode('-', $controller)));

        $className = $namespace . $controller . 'Controller';
        if (class_exists($className)) {
            return $this->di->get($className);
        } else {
            return null;
        }
    }

    public function handle(IRequest $request)
    {
        if (strpos($request->getPath(), $this->pathPrefix) !== 0) {
            return null;
        }

        $params = $this->parsePath($request->getPath());
        // if no controller specified - use default controller.
        // This help distinct default controller and invalid controller
        $controllerName = isset($params[0]) ? array_shift($params) : $this->defaultController;

        $controller = $this->controllerFactory($this->namespace, $controllerName);
        if ($controller) {
            return $controller->handle($request, $params, $this->pathPrefix);
        } else {
            // no controller found, use default controller to handle 404 error
            $controller = $this->controllerFactory($this->namespace, $this->defaultController);
            return $controller->handleError(404, new RouteNotFoundException("Controller '{$controllerName}' not found"));
        }
    }
}
