<?php

declare(strict_types=1);

namespace System\Abstracts;

use Psr\Container\ContainerInterface;

abstract class AbstractHandler
{
    protected ?ContainerInterface $container = null;

    public function setContainer(?ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function render(string $view, array $parameters = [])
    {
        return $this->container->get('twig')->render($view.'.html.twig', $parameters);
    }
}
