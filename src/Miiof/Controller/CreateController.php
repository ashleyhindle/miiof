<?php
namespace Miiof\Controller;

use Flint\Controller\Controller;
use \Dropbox as dbx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
				$invoiceHtml = $this->render('invoice.html.twig', [
						'invoice' => $_POST
				]);

				$baseDir = '/tmp/' . substr(md5(rand()), 0, 14) . '/';
				mkdir($baseDir);

				$tmpFileHtml = $baseDir . '/miiof_HTMLinvoice_' . substr(md5(rand()), 0, 14) . '.html';
				touch($tmpFileHtml);
				chmod($tmpFileHtml, 0777);

				file_put_contents(
						$tmpFileHtml,
						$invoiceHtml
				);

				$tmpFilePdf = $baseDir . '/Miiof_Invoice_' . $_POST['invoiceid'] . '_' . substr(md5(rand()), 0, 7) . '.pdf';
				touch($tmpFilePdf);
				chmod($tmpFilePdf, 0777);

				$command = "/usr/local/bin/wkhtmltopdf " . escapeshellarg($tmpFileHtml) . " " . escapeshellarg($tmpFilePdf);
				shell_exec($command);

				$response = new Response();
				$response->headers->set('Cache-Control', 'private');
				$response->headers->set('Content-type', mime_content_type($tmpFilePdf));
				//$response->headers->set('Content-Disposition', 'attachment; filename="' . basename($tmpFilePdf) . '";');
				$response->headers->set('Content-length', filesize($tmpFilePdf));

				$response->sendHeaders();
				$response->setContent(readfile($tmpFilePdf));

				return $response->send();
		}
}
