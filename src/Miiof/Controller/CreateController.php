<?php
namespace Miiof\Controller;

use Flint\Controller\Controller;
use \Dropbox as dbx;
use Symfony\Component\HttpFoundation\Request;

class CreateController extends Controller
{
		public function indexAction()
		{
				if(null == $this->pimple['predis']->get('test')) {
						$this->pimple['predis']->set('test', 'uwotm8');
						$this->pimple['predis']->hmset('invoice:18', ['invoiceid' => 1000, 'subject' => 'For Tits', 'from' => 'Batman', 'to' => 'Ironman']);
				}

				return $this->render('create.html.twig');
		}

		public function generateAction(Request $request)
		{
				return $this->render('invoice.html.twig', [
						'invoice' => $_POST
				]);
		}
}
