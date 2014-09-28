<?php
namespace Miiof\Controller;

use Flint\Controller\Controller;
use \Dropbox as dbx;

class CreateController extends Controller
{
	public function indexAction()
	{
		if(null == $this->pimple['predis']->get('test')) {
			$this->pimple['predis']->set('test', 'uwotm8');
			$this->pimple['predis']->hmset('invoice:18', ['invoiceid' => 1000, 'subject' => 'For Tits', 'from' => 'Batman', 'to' => 'Ironman']);
		}

		//$this->pimple['dropbox.client']->uploadFileFromString('/myInvoiceTest1.txt', dbx\WriteMode::add(), 'Not really, just testing');
		return $this->render('create.html.twig');
	}
}
