<?php
namespace Miiof\Controller;

use Flint\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class SaveController extends Controller
{
	public function indexAction(Request $request, Application $app)
	{
		$invoiceKey = $request->get('invoiceKey');
		$loggedInToDropbox = (!empty($app['session']->get('dropbox')));
		
		return $this->render('save.html.twig', [
				'invoiceKey' => $invoiceKey,
				'loggedInToDropbox' => $loggedInToDropbox
		]);
	}
}
