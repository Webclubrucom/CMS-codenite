<?php

declare(strict_types=1);

namespace Application\Modules\Home\Controllers;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use System\Abstracts\AbstractController;
use System\Core\Http\Response;

class HomeController extends AbstractController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): Response
    {
        $data = ['int' => '123456789'];

        return $this->render('/Home/templates/home', $data);
    }
}
