<?php

declare(strict_types=1);

namespace System\Core\Http;

use Exception;
use System\Core\Exceptions\HttpException;
use System\Core\Router\Interfaces\RouterInterface;
use Throwable;

readonly class Kernel
{
    private Request $request;

    public function __construct(
        private RouterInterface $router
    ) {
        $this->request = Request::createFromGlobals();
    }

    public function handle()
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($this->request);
            $response = call_user_func_array($routeHandler, $vars);
        } catch (Exception $e) {
            $response = $this->createExceptionResponse($e);
        }

        return $response->send();
    }

    private function createExceptionResponse(Exception $e):Response
    {
        if ($e instanceof HttpException){
            return new Response($e->getMessage(), $e->getStatusCode());
        }

        return new Response('Server error', 500);
    }
}
