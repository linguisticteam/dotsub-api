<?php
namespace Lti\DotsubAPI\Service;

use Lti\DotsubAPI\Client;
use Lti\DotsubAPI\Service;

/**
 *
 * Handling the Language part of the dotSUB API.
 * No Authentication required.
 *
 */
class Service_Language extends Service {

	public function __construct(Client $client){

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
			throw new Service_Exception("A language must be specified.");
		}
		return $this->httpRequest;
	
	}

}
