<?php

declare(strict_types=1);

namespace System\Core\Http;

readonly class Response
{
    public function __construct(
        private string $content = '',
        private int $statusCode = 200,
        private array $headers = []
    ) {
    }

    public function send(): void
    {
        echo $this->content;
    }
}
