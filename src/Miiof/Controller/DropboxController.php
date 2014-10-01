<?php
namespace Miiof\Controller;

use \Dropbox as dbx;
use Flint\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class DropboxController extends Controller
{
	public function startAction(Application $app)
	{
		$authorizeUrl = $app['dropbox.webAuth']->start();
		if(!empty($authorizeUrl)) {
				return $app->redirect($authorizeUrl);
		}

		return $this->render('index.html.twig', []);
	}

	public function finishAction(Application $app)
	{
		list($accessToken, $dropboxUserId, $urlState) = $app['dropbox.webAuth']->finish($_GET);

		$app['session']->set('dropbox', [
				'accessToken' => $accessToken,
				'dropboxUserId' => $dropboxUserId
		]);

		return $app->redirect('/create');
	}

	public function saveAction(Request $request, Application $app)
	{
		$loggedInToDropbox = (!empty($app['session']->get('dropbox')));
		if(!$loggedInToDropbox) {
				return 'You\'re not logged in via Dropbox, and I don\'t have the mental capacity right now to enable saving it in a cookie of some sort, then doing dropbox login, then retrieving/saving it - I am sorry';
		}

		$invoiceKey = $request->get('invoiceKey');

		$dbxClient = new dbx\Client($app['session']->get('dropbox')['accessToken'], "Miiof");
		$fp = fopen('/tmp/' . $invoiceKey . '/INVOICE_' . $invoiceKey . '.pdf', 'rb');
		$md1 = $dbxClient->uploadFile("/INVOICE_{$invoiceKey}.pdf", dbx\WriteMode::add(), $fp);
		fclose($fp);

		if(is_array($md1) && $md1['revision']) {
				$app['session']->set('flashbag', ['type'=>'success', 'message'=>'Successfully uploaded INVOICE_'.$invoiceKey.'.pdf']);
		} else {
				$app['session']->set('flashbag', ['type'=>'warning', 'message'=>'Failed to upload INVOICE_'.$invoiceKey.'.pdf - I do\'nt know why right now, but I am very sorry! :-(']);
		}
		return $app->redirect('/save/'.$invoiceKey);
	}
}
