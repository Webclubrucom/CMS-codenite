<?php

declare(strict_types=1);

namespace System\Core\Abstracts;

use JetBrains\PhpStorm\NoReturn;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use System\Core\Http\RedirectResponse;
use System\Core\Session\SessionInterface;


abstract class AbstractController
{
    protected ?ContainerInterface $container = null;
    protected Request $request;
    protected SessionInterface $session;

    public function setContainer(?ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function setSession(?SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;

    }

    public function get(): array
    {
        return $this->request->query->all();
    }

    public function post(): array
    {
        return $this->request->request->all();
    }

    public function files(): array
    {
        return $this->request->files->all();
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
