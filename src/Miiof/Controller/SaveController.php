<?php
namespace Miiof\Controller;

use Flint\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SaveController extends Controller
{
	public function indexAction(Request $request)
	{
		$invoiceKey = $request->get('invoiceKey');
		return $this->render('save.html.twig', [
				'invoiceKey' => $invoiceKey
		]);
	}
}
