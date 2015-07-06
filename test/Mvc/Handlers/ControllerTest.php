<?php
use Vtk13\Mvc\Handlers\IHandler;
use Vtk13\Mvc\Http\JsonpResponse;
use Vtk13\Mvc\Http\JsonResponse;
use Vtk13\Mvc\Http\RedirectResponse;
use Vtk13\Mvc\Http\Request;
use Vtk13\Mvc\Handlers\AbstractController;
use Vtk13\Mvc\Handlers\ControllerRouter;

class IndexController extends AbstractController
{
    public function __construct()
    {
        parent::__construct('index');
    }

    public function indexGET()
    {
        return 'index';
    }

    public function templateGET()
    {
        // draw default template
    }

    public function redirectGET()
    {
        return new RedirectResponse('newurl');
    }

    public function jsonGET()
    {
        return new JsonResponse(array(1, 2));
    }

    public function jsonpGET()
    {
        return new JsonpResponse('callback', array(1, 2));
    }

    public function invalidGET()
    {
        return new stdClass();
    }
}

class ControllerTestClass extends PHPUnit_Framework_TestCase
{
    /**
     * @var IHandler
     */
    protected $router;

    protected function setUp()
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $builder->addDefinitions([
            Twig_Environment::class => function() {
                $loader = new Twig_Loader_Filesystem(realpath(__DIR__ . '/../../templates'));
                return new Twig_Environment($loader, array(
                    'cache' => 'test/template_cache',
                    'auto_reload' => true,
                ));
            }
        ]);

        $this->router = new ControllerRouter($builder->build());
    }

    public function testIndexAction()
    {
        $request = new Request();
        $response = $this->router->handle($request);
        $this->assertEquals('index', $response->getBody());
    }

    public function testTemplateAction()
    {
        $request = new Request(null, '/index/template');
        $response = $this->router->handle($request);
        $this->assertEquals("header\ntemplate\n", $response->getBody());
    }

    public function testRedirectResponse()
    {
        $request = new Request(null, '/index/redirect');
        $response = $this->router->handle($request);
        $this->assertEquals('newurl', $response->getHeader('Location'));
        $this->assertEquals(302, $response->getStatus());
    }

    public function testJsonResponse()
    {
        $request = new Request(null, '/index/json');
        $response = $this->router->handle($request);
        $this->assertEquals('[1,2]', $response->getBody());
    }

    public function testJsonpResponse()
    {
        $request = new Request(null, '/index/jsonp');
        $response = $this->router->handle($request);
        $this->assertEquals('callback([1,2])', $response->getBody());
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidResponse()
    {
        $request = new Request(null, '/index/invalid');
        $this->router->handle($request);
    }
}
