<?php

namespace Lolol\SuperAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;

class SuperAdminController extends Controller {
	public function indexAction($name) {
		return $this->render('LololSuperAdminBundle:Default:index.html.twig', array (
				'name' => $name ));
	}
	
	/**
	 * Retrieve the list of champions managed by the app and renders it
	 */
	public function retrieveChampionsAction() {
		// Get the service
		$championNames = $this->container->get('lolol_team.champions');
		$stringHelper = $this->container->get('lolol_app.stringHelper');
		
		// Get the parameters
		$folder = $this->container->getParameter('champions_folder');
		$statsExtension = $this->container->getParameter('champions_stats_extension');
		$prefixIcons48 = $this->container->getParameter('champions_icons48_prefix');
		$suffixIcons48 = $this->container->getParameter('champions_icons48_suffix');
		
		$fs = new Filesystem();
		
		$championInfos = array ();
		foreach ( $championNames->getList() as $championName ) {
			$championInfo ['name'] = $championName;
			$championInfo ['trClass'] = '';
			
			// Stats
			$tmpName = str_replace(' ', '_', $championName);
			$statsFilename = $folder . '/' . $tmpName . $statsExtension;
			if ($fs->exists($statsFilename)) {
				$championInfo ['lastStatsRetrieve'] = date("Y-m-d H:i:s", filemtime($statsFilename));
			}
			else {
				$championInfo ['lastStatsRetrieve'] = 'Never retrieved';
				$championInfo ['trClass'] = 'danger';
			}
			
			// Icons: 48px
			$icon48Filename = $stringHelper->getIcon48Path($championName);
			if ($fs->exists($icon48Filename)) {
				$championInfo ['lastIcons48Retrieve'] = date("Y-m-d H:i:s", filemtime($icon48Filename));
			}
			else {
				$championInfo ['lastIcons48Retrieve'] = 'Never retrieved';
				$championInfo ['trClass'] = 'danger';
			}
			
			array_push($championInfos, $championInfo);
		}
		
		return $this->render('LololSuperAdminBundle:SuperAdmin:retrieveChampions.html.twig', array (
				'championInfos' => $championInfos ));
	}
	
	/**
	 * Retrieve the champions page from wiki to local system.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function retrieveProcessAction() {
		$params = array ();
		$request = $this->getRequest();
		
		// Get the clicked submit button
		$postRetrieve = $request->request->get('btnRetrieve');
		
		// Get the type of data to retrieve on the selected champions
		$postRetrieveTypes = $request->request->get('retrieveTypes');
		if (! empty($postRetrieveTypes)) {
			foreach ( $postRetrieveTypes as $retrieveType ) {
				$retrieveTypes [$retrieveType] = true;
			}
		}
		
		// Get the selected champions
		$championNames = $request->request->get('champions');
		
		if (! empty($championNames)) {
			$folder = $this->container->getParameter('champions_folder');
			$statsExtension = $this->container->getParameter('champions_stats_extension');
				
			$retrieveChampions = $this->container->get('lolol_superAdmin.retrieveChampions');
			
			// Manage the stats
			if (isset($retrieveTypes ['stats']) && $retrieveTypes ['stats'] == true) {
				if($postRetrieve == 'retrieve') {
					$retrievedStats = $retrieveChampions->retrieveStats($championNames, $folder, $statsExtension);
				} elseif($postRetrieve == 'clear') {
					$retrievedStats = $retrieveChampions->clearStats($championNames, $folder, $statsExtension);
				}
			}
			
			// Manage the icons
			if (isset($retrieveTypes ['icons48']) && $retrieveTypes ['icons48'] == true) {
				if($postRetrieve == 'retrieve') {
					$retrievedIcons48 = $retrieveChampions->retrieveIcons48($championNames);
				} elseif($postRetrieve == 'clear') {
					$retrievedIcons48 = $retrieveChampions->clearIcons48($championNames);
				}
			}
		}
		
		if($postRetrieve == 'retrieve') {
			$action = 'retrieved';
		} elseif($postRetrieve == 'clear') {
			$action = 'cleared';
		}
		$this->get('session')->getFlashBag()->add('info', 'champions successfully '.$action);
		
		return $this->redirect($this->generateUrl('lolol_super_admin_retrieveChampions'));
	}
	
	/**
	 * Populate the DB with the champions retrieved previously.
	 */
	public function populateChampionsAction() {
		$populateChampions = $this->container->get('lolol_superAdmin.populateChampions');
		
		$em = $this->getDoctrine()->getManager();
		
		$result = $populateChampions->populate($em);
		
		// Add msg to flash bag to display an alert
		$this->get('session')->getFlashBag()->add('info-detail', 'populateChampions');
		$this->get('session')->getFlashBag()->add('populateChampions-title', count($result ['added']) . ' champions added, ' . count($result ['updated']) . ' updated.');
		
		foreach ( $result ['added'] as $added ) {
			$this->get('session')->getFlashBag()->add('populateChampions-detail', $added . ' was added to DB.');
		}
		
		foreach ( $result ['updated'] as $updated ) {
			$this->get('session')->getFlashBag()->add('populateChampions-detail', $updated . ' was updated.');
		}
		
		return $this->redirect($this->getRequest()->headers->get('referer'));
	}
}
