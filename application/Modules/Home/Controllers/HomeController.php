<?php

declare(strict_types=1);

namespace Application\Modules\Home\Controllers;

use System\Abstracts\AbstractHandler;

class HomeController extends AbstractHandler
{

    public function index()
    {
        $data = ['int' => '123456789'];

        return $this->render('Modules/Home/templates/home', $data);
    }
}
