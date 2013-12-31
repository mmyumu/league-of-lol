<?php

namespace Lolol\SuperAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;

class SuperAdminController extends Controller {
	public function indexAction($name) {
		return $this->render('LololSuperAdminBundle:Default:index.html.twig', array(
				'name' => $name
		));
	}
	
	/**
	 * Retrieve the list of champions managed by the app and renders it
	 */
	public function retrieveChampionsAction() {
		// Get the service
		$championNames = $this->container->get('lolol_team.champions');
		
		// Get the parameters
		$folder = $this->container->getParameter('champions.folder');
		$extension = $this->container->getParameter('champions.file.extension');
		
		$fs = new Filesystem();
		
		$championInfos = array();
		foreach($championNames->getList() as $championName) {
			$championInfo['name'] = $championName;
			$filename = $folder . '/' . $championName . $extension;
			
			if ($fs->exists($filename)) {
				$championInfo['lastRetrieve'] = date("Y-m-d H:i:s", filemtime($filename));
			} else {
				$championInfo['lastRetrieve'] = 'never retrieved';
			}
			
			array_push($championInfos, $championInfo);
		}
		
		return $this->render('LololSuperAdminBundle:SuperAdmin:retrieveChampions.html.twig', array(
				'championInfos' => $championInfos
		));
	}
	
	/**
	 * Retrieve the champions page from wiki to local system.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function retrieveProcessAction() {
		$params = array();
		$request = $this->getRequest();
		
		$championNames = $request->request->get('champions');
		
		if (!empty($championNames)) {
			$folder = $this->container->getParameter('champions.folder');
			$extension = $this->container->getParameter('champions.file.extension');
			
			$retrieveChampions = $this->container->get('lolol_superAdmin.retrieveChampions');
			$retrieveChampions->retrieve($championNames, $folder, $extension);
		}
		return $this->redirect($this->generateUrl('lolol_super_admin_retrieveChampions'));
	}
}
