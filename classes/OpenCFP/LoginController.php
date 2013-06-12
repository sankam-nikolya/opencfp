<?php
namespace OpenCFP;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class LoginController
{
    public function indexAction(Request $req, Application $app)
    {
        $template = $app['twig']->loadTemplate('login.twig');

        return $template->render(array());
    }

    public function processAction(Request $req, Application $app)
    {
        $template = $app['twig']->loadTemplate('login.twig');
        $templateData = array();

        try {
            $page = new \OpenCFP\Login($app['sentry']);

            if ($page->authenticate($req->get('email'), $req->get('passwd'))) {
                return $app->redirect('/dashboard');
            }
            
            $templateData = array(
                'user' => $app['sentry']->getUser(),
                'email' => $req->get('email'),
                'errorMessage' => $page->getAuthenticationMessage()
            );
        } catch (Exception $e) {
            $templateData = array(
                'user' => $app['sentry']->getUser(),
                'email' => $req->get('email'),
                'errorMessage' => $e->getMessage()
            );
        }
        
        return $template->render($templateData);
    }

    public function outAction(Request $req, Application $app)
    {
        $app['sentry']->logout();

        return $app->redirect('/');
    }
}