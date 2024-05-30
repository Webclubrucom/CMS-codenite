<?php

namespace application\modules\Error\Controllers;

use system\Abstracts\AbstractController;

class ErrorController extends AbstractController
{
    public function page404(): void
    {
        header('HTTP/1.0 404 Not Found');
        echo 'Страница не найдена';
    }
}
