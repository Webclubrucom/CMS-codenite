<?php

declare(strict_types=1);

namespace Application\Modules\Error;

use System\Core\Abstracts\AbstractController;

final class Error extends AbstractController
{
    public function index()
    {
        $data = ['int' => '123456789'];

        return $this->render('Modules/Error/templates/404', $data);
    }
}
