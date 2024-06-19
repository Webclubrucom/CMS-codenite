<?php

declare(strict_types=1);

namespace System\Abstracts;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use System\Core\Http\Response;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;

    public function setContainer(?ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        $response ??= new Response();
        $content = $this->container->get('twig')->render($view.'.html.twig', $parameters);
        $response->setContent($content);

        return $response;
    }
}
