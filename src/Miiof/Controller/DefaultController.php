<?php
namespace Miiof\Controller;

use Flint\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction()
	{
		return $this->render('index.html.twig', []);
	}
}
