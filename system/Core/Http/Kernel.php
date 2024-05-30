<?php

declare(strict_types=1);

namespace System\Core\Http;

use System\Core\Exceptions\HttpException;
use System\Core\Router\Interfaces\RouterInterface;
use Throwable;

readonly class Kernel
{
    public function __construct(
        private RouterInterface $router
    ) {
    }

    public function handle(Request $request)
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request);
            $response = call_user_func_array($routeHandler, $vars);
        } catch (HttpException $e) {
            $response = new Response($e->getMessage(), $e->getStatusCode());
        } catch (Throwable $e) {
            $response = new Response($e->getMessage(), 500);
        }

        return $response;
    }
}
