<?php

namespace Application\Modules\Login\Controllers;

use System\Abstracts\AbstractController;
use System\Core\Http\Response;
use Twig\Environment;

final class LoginController extends AbstractController
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
