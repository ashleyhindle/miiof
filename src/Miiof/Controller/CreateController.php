<?php

namespace Miiof\Controller;

use \Flint\Application;

class CreateController extends Controller
{
	public function indexAction()
	{
		return $this->render('create.html.twig');
	}
}
