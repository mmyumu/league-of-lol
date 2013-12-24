<?php

namespace Lolol\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController {
	public function loginAction(Request $request) {
		$securityContext = $this->container->get('security.context');
		$router = $this->container->get('router');
		
		if ($securityContext->isGranted('ROLE_ADMIN')) {
			return new RedirectResponse($router->generate('lolol_app_homepage'), 307);
		}
		
		if ($securityContext->isGranted('ROLE_USER')) {
			return new RedirectResponse($router->generate('lolol_app_homepage'), 307);
		}
		
		return parent::loginAction($request);
	}
} 
