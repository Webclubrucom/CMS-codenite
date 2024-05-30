<?php

namespace Application\Modules\Login\Controllers;

use System\Core\Http\Response;

final class LoginController
{
    public function index(): Response
    {
        $content = 'Авторизация';

        return new Response($content);
    }

    public function show(int $id): Response
    {
        $content = 'DDDDD - '.$id;

        return new Response($content);
    }
}
