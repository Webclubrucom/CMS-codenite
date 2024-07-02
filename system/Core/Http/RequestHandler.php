<?php

declare(strict_types=1);

namespace System\Core\Http;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use System\Core\Http\Middleware\Authentication;
use System\Core\Http\Middleware\RouterDispatcher;
use System\Core\Session\Middleware\StartSession;

class RequestHandler implements RequestHandlerInterface
{
    private array $middlewares = [
        StartSession::class,
        Authentication::class,
        RouterDispatcher::class
    ];

    public function __construct(
        private readonly ContainerInterface $container
    )
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request)
    {
        if (empty($this->middlewares)) {
            return new Response('Server Error', 500);
        }

        $middlewareClass = array_shift($this->middlewares);
        $middleware = $this->container->get($middlewareClass);

        return $middleware->process($request, $this);
    }

}