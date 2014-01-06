<?php

namespace Lolol\SuperAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;

class SuperAdminController extends Controller {
	private $prefix48 = "48px-";
	private $suffix48 = "Square.png";
	
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
		$statsExtension = $this->container->getParameter('champions.stats.extension');
		
		$fs = new Filesystem();
		
		$championInfos = array();
		foreach($championNames->getList() as $championName) {
			$championInfo['name'] = $championName;
			$championInfo['trClass'] = '';
			
			// Stats
			$tmpName = str_replace(' ', '_', $championName);
			$statsFilename = $folder . '/' . $tmpName . $statsExtension;
			if ($fs->exists($statsFilename)) {
				$championInfo['lastStatsRetrieve'] = date("Y-m-d H:i:s", filemtime($statsFilename));
			} else {
				$championInfo['lastStatsRetrieve'] = 'Never retrieved';
				$championInfo['trClass'] = 'danger';
			}
			
			// Icons: 48px
			$tmpName = str_replace('\'', '', $championName);
			$tmpName = str_replace(' ', '', $tmpName);
			$tmpName = str_replace('.', '', $tmpName);
			$icons48Filename = $folder . '/img/' . $this->prefix48 . $tmpName . $this->suffix48;
			if ($fs->exists($icons48Filename)) {
				$championInfo['lastIcons48Retrieve'] = date("Y-m-d H:i:s", filemtime($icons48Filename));
			} else {
				$championInfo['lastIcons48Retrieve'] = 'Never retrieved';
				$championInfo['trClass'] = 'danger';
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
		
		$res = '';
		
		// Gets the type of data to retrieve on the selected champions
		$postRetrieveTypes = $request->request->get('retrieveTypes');
		foreach($postRetrieveTypes as $retrieveType) {
			$retrieveTypes[$retrieveType] = true;
		}
		
		$championNames = $request->request->get('champions');
		
		if (!empty($championNames)) {
			$folder = $this->container->getParameter('champions.folder');
			$statsExtension = $this->container->getParameter('champions.stats.extension');
			
			$retrieveChampions = $this->container->get('lolol_superAdmin.retrieveChampions');
			
			if (isset($retrieveTypes['stats']) && $retrieveTypes['stats'] == true) {
				$retrievedStats = $retrieveChampions->retrieveStats($championNames, $folder, $statsExtension);
			}
			if (isset($retrieveTypes['icons48']) && $retrieveTypes['icons48'] == true) {
				$retrievedIcons48 = $retrieveChampions->retrieveIcons48($championNames, $folder, $this->prefix48, $this->suffix48);
			}
		}
		
		// Add msg to flash bag to display an alert
		$this->get('session')->getFlashBag()->add('info', 'champions successfully retrieved');
		
		return $this->redirect($this->generateUrl('lolol_super_admin_retrieveChampions'));
	}
	
	/**
	 * Populate the DB with the champions retrieved previously.
	 */
	public function populateChampionsAction() {
		$populateChampions = $this->container->get('lolol_superAdmin.populateChampions');
		
		$em = $this->getDoctrine()->getManager();
		
		$folder = $this->container->getParameter('champions.folder');
		$statsExtension = $this->container->getParameter('champions.stats.extension');
		
		$result = $populateChampions->populate($em, $folder, $extension);
		
		// Add msg to flash bag to display an alert
		$this->get('session')->getFlashBag()->add('info-detail', 'populateChampions');
		$this->get('session')->getFlashBag()->add('populateChampions-title', count($result['added']) . ' champions added, ' . count($result['updated']) . ' updated.');
		
		foreach($result['added'] as $added) {
			$this->get('session')->getFlashBag()->add('populateChampions-detail', $added . ' was added to DB.');
		}
		
		foreach($result['updated'] as $updated) {
			$this->get('session')->getFlashBag()->add('populateChampions-detail', $updated . ' was updated.');
		}
		
		return $this->redirect($this->getRequest()->headers->get('referer'));
	}
}
