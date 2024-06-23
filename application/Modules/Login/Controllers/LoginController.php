<?php

namespace Application\Modules\Login\Controllers;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use System\Abstracts\AbstractHandler;

final class LoginController extends AbstractHandler
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index()
    {

        $data = ['int' => '123456789'];

        return $this->render('Modules/Login/Views/login', $data);
    }

}
