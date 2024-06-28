<?php

declare(strict_types=1);

namespace System\Core\Http;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\Response;

class RedirectResponse extends Response
{
    public function __construct(?string $url)
    {
        parent::__construct('', 302, ['location' => $url]);
    }

    #[NoReturn]
    public function send(bool $flush = true): static
    {
        header("Location: {$this->getHeader()}", true, $this->getStatusCode());
        exit;
    }

    private function getHeader(): ?string
    {
        return $this->headers->get('location');
    }
}
