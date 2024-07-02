<?php

declare(strict_types=1);

namespace System\Core\Http;

use Symfony\Component\HttpFoundation\Request;

interface RequestHandlerInterface
{
    public function handle(Request $request);
}