<?php
namespace Miiof\Controller;

use \Dropbox as dbx;
use Flint\Controller\Controller;
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
}
