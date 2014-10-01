<?php
namespace Miiof\Controller;

use Flint\Controller\Controller;
use \Dropbox as dbx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Silex\Application;

class CreateController extends Controller
{
		public function indexAction(Application $app)
		{
				$invoices = [];
				$lastInvoiceId = 999;

				if($app['session']->get('dropbox') !== null) {
						$members = $app['predis']->smembers('invoices:dropbox:userid:'.$app['session']->get('dropbox')['dropboxUserId']);
						if(!empty($members)) {
								foreach($members as $invoiceKey) {
										$invoices[$invoiceKey] = json_decode($app['predis']->get('invoice:'.$invoiceKey), true);
										if($invoices[$invoiceKey]['invoiceid'] > $lastInvoiceId) {
												$lastInvoiceId = $invoices[$invoiceKey]['invoiceid'];
										}	
								}
						}
				}
				return $this->render('create.html.twig', [
						'invoices' => $invoices,
						'lastInvoiceId' => $lastInvoiceId
				]);
		}

		public function generateAction(Request $request, Application $app)
		{
				$invoiceHtml = $this->render('invoice.html.twig', [
						'invoice' => $_POST
				]);

				$invoiceKey = substr(md5(rand()), 0, 14);

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
						$app['predis']->sadd('invoices:dropbox:userid:'.$app['session']->get('dropbox')['dropboxUserId'], [$invoiceKey]);
				}

				return $app->redirect('/save/' . urlencode($invoiceKey));
		}
}
