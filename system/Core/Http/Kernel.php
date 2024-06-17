<?php

declare(strict_types=1);

namespace System\Core\Http;

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
        } catch (HttpException $e) {
            $response = new Response($e->getMessage(), $e->getStatusCode());
        } catch (Throwable $e) {
            $response = new Response($e->getMessage(), 500);
        }

        return $response;
    }
}
