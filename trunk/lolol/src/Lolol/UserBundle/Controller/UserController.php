<?php

namespace Lolol\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller {
	/**
	 * Display the user settings
	 */
	public function settingsAction() {	
		return $this->render('LololUserBundle:User:settings.html.twig');
	}
	
	/**
	 * Save the user settings
	 */
	public function settingsSaveAction() {
		// Get the services
		$translator = $this->get('translator');
		
		// Get the posted parameters
		$request = $this->getRequest();
		$displayHelp = $request->request->get('displayHelp');
		
		// Update the user settings
		$this->getUser()->setDisplayHelp(!empty($displayHelp) && $displayHelp == 'on');
		
		// Persist the user
		$em = $this->getDoctrine()->getManager();
		$em->persist($this->getUser());
		$em->flush();
		
		$message = $translator->trans('user.settings.save');
		$this->get('session')->getFlashBag()->add('info', $message);
		
		return $this->redirect($this->generateUrl('lolol_user_settings'));
	}
}
