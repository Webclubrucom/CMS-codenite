<?php

declare(strict_types=1);

namespace System\Core\Session\Middleware;

use Symfony\Component\HttpFoundation\Request;
use System\Core\Http\RequestHandlerInterface;
use System\Core\Middleware\MiddlewareInterface;
use System\Core\Session\SessionInterface;

readonly class StartSession implements MiddlewareInterface
{
    public function __construct(
        private SessionInterface $session
    )
    {}

    public function process(Request $request, RequestHandlerInterface $handler)
    {
        $this->session->start();

        return $handler->handle($request);
    }
}