<?php

declare(strict_types=1);

namespace System\Core\Router\Interfaces;

use System\Core\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request);
}
