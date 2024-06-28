<?php

namespace Application\Modules\Login\Controllers;

use System\Abstracts\AbstractController;

final class LoginController extends AbstractController
{
    public function index()
    {

        $data = ['int' => '123456789'];

        return $this->render('Modules/Login/templates/login', $data);
    }

    public function auth()
    {

        dd('Форма отправлена');
    }



}
