<?php

namespace Application\Modules\Users\Controller;

use System\Core\Abstracts\AbstractController;

class UsersController extends AbstractController
{
	public function index()
	{
		$data = ['title' => 'Users'];

		return $this->render('Modules/Users/templates/users', $data);
	}
        
}