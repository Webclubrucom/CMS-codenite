<?php

declare(strict_types=1);

namespace System;

use League\Container\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use System\Core\Router\Interfaces\RouterInterface;

class Application
{
    public function __construct(
        private readonly RouterInterface $router,
        private Request $request,
        private readonly Response $response,
        private readonly Container $container,
    ) {
        $this->request = Request::createFromGlobals();
    }

    public function handle(): void
    {
        $params = $this->router->match($this->request->getPathInfo());

        $controller = $this->container->get($params['handler']);

        $action = $params['action'];
        $content = $controller->$action();

        $this->response->setContent($content)->setStatusCode(Response::HTTP_OK)->send();
    }
}
