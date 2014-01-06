<?php

namespace Lolol\SuperAdminBundle\RetrieveFile;

class LololRetrieveFile {
	private $httpProxy;
	private $wikiUrl;
	
	public function __construct($httpProxy, $wikiUrl) {
		$this->httpProxy = $httpProxy;
		$this->wikiUrl = $wikiUrl;
	}
	
	/**
	 * Gets the file at the specified URL
	 * @param string $url
	 * @return the file
	 */
	public function get($url) {
		$file = null;
		if(!empty($this->httpProxy)) {
			$aContext = array(
					'http' => array(
							'proxy' => $this->httpProxy,
							'request_fulluri' => true,
					),
			);
			$cxContext = stream_context_create($aContext);
			
			$file = file_get_contents($url, False, $cxContext);
		} else {
			$file = file_get_contents($url);
		}
		
		return $file;
	}
	
	/**
	 * Gets the file from the wiki lol at the specified path
	 * @param string $path
	 */
	public function getFromWiki($path) {
		return $this->get($this->wikiUrl . $path);
	}
}