<?php

declare(strict_types=1);

namespace System\Core\Http;

class Kernel
{
    public function handle(Request $request): Response
    {
        $content = 'Всё будет Кока-Кола';

        return new Response($content);
    }
}
