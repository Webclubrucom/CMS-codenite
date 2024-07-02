<?php

declare(strict_types=1);

namespace System\Core\Http\Middleware;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use System\Core\Abstracts\AbstractController;
use System\Core\Http\RequestHandlerInterface;
use System\Core\Middleware\MiddlewareInterface;
use System\Core\Router\Interfaces\RouterInterface;

readonly class RouterDispatcher implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface    $router,
        private ContainerInterface $container
    )
    {}

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function process(Request $request, RequestHandlerInterface $handler): void
    {
        $params = $this->router->match($request->getPathInfo(), $request->getMethod());

        $controller = $this->container->get($params['handler']);
        $action = $params['action'];

        if (is_subclass_of($controller, AbstractController::class)) {
            $controller->setRequest($request);
        }
        $response = new Response();
        $content = $controller->$action();

        $statusCode = $response->getStatusCode();

        $response->setContent($content)->setStatusCode($statusCode)->send();
    }
}