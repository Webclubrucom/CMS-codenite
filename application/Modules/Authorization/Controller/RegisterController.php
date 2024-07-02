<?php

namespace Application\Modules\Authorization\Controller;

use System\Core\Abstracts\AbstractController;

class RegisterController extends AbstractController
{

    public function index()
    {

        $data = ['int' => '123456789'];

        return $this->render('Modules/Authorization/templates/register', $data);
    }

    public function auth()
    {
        $this->session->setFlash('error', 'Ошибка ёлки-палки!');
        $this->redirect('/register');
    }



}
