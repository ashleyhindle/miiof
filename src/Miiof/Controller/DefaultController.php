<?php
namespace Miiof\Controller;

use Flint\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class DefaultController extends Controller
{
	public function indexAction(Application $app)
	{
		$loggedInToDropbox = (!empty($app['session']->get('dropbox')));
		if($loggedInToDropbox) {
				return $app->redirect('/create');
		}

		return $this->render('index.html.twig', [
				'loggedInToDropbox' => $loggedInToDropbox
		]);
	}

    public function invoiceAction(Request $request)
    {
		$invoiceKey = $request->get('invoiceKey');
		$download = $request->get('download');
		if(empty($invoiceKey)) {
				die("Sorry, invalid key, and I don't have time at the minute to add proper error messages because it's past midnight and my laptop is burning my legs");
		}

		$baseDir = '/tmp/' . $invoiceKey . '/';
		$tmpFilePdf = $baseDir . 'INVOICE_' . $invoiceKey . '.pdf';

		$response = new Response();
		$response->headers->set('Cache-Control', 'private');
		$response->headers->set('Content-type', mime_content_type($tmpFilePdf));

		if($download == 'download') {
				$response->headers->set('Content-Disposition', 'attachment; filename="' . basename($tmpFilePdf) . '";');
		} else {
				$response->headers->set('Content-Disposition', 'filename="' . basename($tmpFilePdf) . '";');
		}

		$response->headers->set('Content-length', filesize($tmpFilePdf));

		$response->sendHeaders();
		$response->setContent(readfile($tmpFilePdf));

		return $response->send();
    }
}
