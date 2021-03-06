<?php
namespace Miiof\Controller;

use Flint\Controller\Controller;
use \Dropbox as dbx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class CreateController extends Controller
{
		public function indexAction(Application $app)
		{
				return $this->render('create.html.twig');
		}

		// @TODO: THIS REALLY SHOULD NOT BE IN HERE, BUT ONCE IT IS WORKING I WILL TIDY IT UP, I PROMISE
		public function listInvoicesAction(Request $request, Application $app)
		{
				if($app['session']->get('dropbox') == null) {
						return $app->json([]);
				}

				$duplicate = $request->get('duplicateInvoiceKey');
				$duplicateInvoice = [];
				$invoices = [];

				$members = $app['predis']->lrange('invoices:dropbox:userid:'.$app['session']->get('dropbox')['dropboxUserId'], 0, 10);
				if(!empty($members)) {
						foreach($members as $invoiceKey) {
								$invoice = json_decode($app['predis']->get('invoice:'.$invoiceKey), true);
								$invoices[] = $invoice;

								if($invoice['invoiceid'] > $lastInvoiceId) {
										$lastInvoiceId = $invoice['invoiceid'];
										$lastInvoice = $invoice;
								}	

								if($duplicate == $invoiceKey) {
										$duplicateInvoice = $invoice;
								}
						}
				}

				return $app->json([
						'invoices' => $invoices,
						'lastInvoice' => $lastInvoice,
						'lastInvoiceId' => $lastInvoiceId,
						'oldInvoiceCount' => count($invoices),
						'duplicateInvoice' => $duplicateInvoice
				]);
		}

		public function generateAction(Request $request, Application $app)
		{
				$contentTypes = $request->getAcceptableContentTypes();
				$invoiceKey = substr(md5(rand()), 0, 14);
				$_POST['invoiceKey'] = $invoiceKey;

				$invoiceHtml = $this->render('invoice.html.twig', [
						'invoice' => $_POST
				]);


				$baseDir = '/tmp/' . $invoiceKey . '/';
				mkdir($baseDir);

				$tmpFileHtml = $baseDir . 'INVOICE_' . $invoiceKey . '.html';
				$tmpFilePdf = $baseDir . 'INVOICE_' . $invoiceKey . '.pdf';

				touch($tmpFileHtml);
				chmod($tmpFileHtml, 0777);

				touch($tmpFilePdf);
				chmod($tmpFilePdf, 0777);

				file_put_contents(
						$tmpFileHtml,
						$invoiceHtml
				);

				$command = "/usr/local/bin/wkhtmltopdf " . escapeshellarg($tmpFileHtml) . " " . escapeshellarg($tmpFilePdf);
				shell_exec($command);
		
				$app['predis']->set('invoice:'.$invoiceKey, json_encode($_POST));
				if($app['session']->get('dropbox') !== null) {
						$app['predis']->lpush('invoices:dropbox:userid:'.$app['session']->get('dropbox')['dropboxUserId'], $invoiceKey);
				}

				if(in_array('application/json', $contentTypes)) {
						return $app->json([
								'pdflocation' => 'https://miiof.smellynose.com/invoice/'.$invoiceKey.'/download'
						]);
				} else {
						return $app->redirect('/save/' . urlencode($invoiceKey));
				}
		}
}
