<?php

declare(strict_types=1);

namespace Application\Modules\Error;

use System\Abstracts\AbstractHandler;

final class Error extends AbstractHandler
{
    public function index()
    {
        $data = ['int' => '123456789'];

        return $this->render('Modules/Error/Views/404', $data);
    }
}