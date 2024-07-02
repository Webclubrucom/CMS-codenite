<?php

declare(strict_types=1);

namespace System;

use Symfony\Component\HttpFoundation\Request;
use System\Core\Http\RequestHandlerInterface;
use System\Core\Session\Session;

class Application
{
    public function __construct(
        private Request                          $request,
        private readonly RequestHandlerInterface $handler,
        private readonly Session $session
    ) {
        $this->request = Request::createFromGlobals();
    }

    public function handle(): void
    {
        $this->handler->handle($this->request);
        $this->terminate();
    }

    private function terminate(): void
    {
        $this->session->clearFlash();
    }
}
