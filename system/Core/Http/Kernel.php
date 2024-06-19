<?php

declare(strict_types=1);

namespace System\Core\Http;

use Exception;
use League\Container\Container;
use System\Core\Exceptions\HttpException;
use System\Core\Helpers\Config;
use System\Core\Router\Interfaces\RouterInterface;

readonly class Kernel
{
    private Request $request;

    public function __construct(
        private RouterInterface $router,
        private Container $container
    ) {
        $this->request = Request::createFromGlobals();
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {

        try {
            [$routeHandler, $vars] = $this->router->dispatch($this->request, $this->container);
            $response = call_user_func_array($routeHandler, $vars);
        } catch (Exception $e) {
            $response = $this->createExceptionResponse($e);
        }

        $response->send();
    }

    /**
     * @throws Exception
     */
    private function createExceptionResponse(Exception $e): Response
    {
        if (Config::get('APP_ENV') == 'local') {
            throw $e;
        }

        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getStatusCode());
        }

        return new Response('Ошибка сервера', 500);
    }
}
