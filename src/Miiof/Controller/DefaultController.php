<?php
namespace Miiof\Controller;

use Flint\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	public function indexAction()
	{
		return $this->render('index.html.twig', []);
	}

    public function invoiceAction(Request $request)
    {
		$key = $request->get('invoiceKey');
		if(empty($key)) {
				die("Sorry, invalid key, and I don't have time at the minute to add proper error messags because it's past midnight and my laptop is burning my legs");
		}
		
    }
}
