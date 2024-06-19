<?php

namespace application\Modules\Error\Controllers;

use System\Core\Http\Response;

class ErrorController
{
    public function page404(): Response
    {
        $content = 'Страница не найдена 404';

        return new Response($content, 404);
    }
}
