<?php
namespace Lti\DotsubAPI\Service;

use Lti\DotsubAPI\DotSUB_Client;
use Lti\DotsubAPI\DotSUB_Service;

/**
 *
 * Handling the Language part of the dotSUB API.
 * No Authentication required.
 *
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Service_Language extends DotSUB_Service {

	public function __construct(DotSUB_Client $client){

		self::$SERVICE_URL = "https://dotSUB.com/api/language";
		parent::__construct($client);
	
	}

	public function languageListing(){

		return $this->httpRequest;
	
	}

	public function languageMapping($language){

		if(!empty($language)) {
			$this->httpRequest->setQueryParam("code", $language);
		} else {
			throw new DotSUB_Service_Exception("A language must be specified.");
		}
		return $this->httpRequest;
	
	}

}
