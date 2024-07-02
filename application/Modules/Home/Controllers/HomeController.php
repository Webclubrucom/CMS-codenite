<?php

declare(strict_types=1);

namespace Application\Modules\Home\Controllers;

use System\Core\Abstracts\AbstractController;

class HomeController extends AbstractController
{

    public function index()
    {
        $data = ['int' => '123456789'];

        return $this->render('Modules/Home/templates/home', $data);
    }
}
