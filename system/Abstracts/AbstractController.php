<?php

declare(strict_types=1);

namespace System\Abstracts;

use JetBrains\PhpStorm\NoReturn;
use Psr\Container\ContainerInterface;
use System\Core\Http\RedirectResponse;


abstract class AbstractController
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

    #[NoReturn]
    public function redirect(string $url): RedirectResponse
    {
        return (new RedirectResponse($url))->send();
    }
}
