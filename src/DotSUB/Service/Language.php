<?php
/**
 * 
 * Handling the Language part of the dotSUB API.
 * No Authentication required.
 * 
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Service_Language extends DotSUB_Service {
	const LANGUAGE = "https://dotSUB.com/api/language";
	private $httpRequest;

	public function __construct(DotSUB_Client $client, $language = ""){
		$this->httpRequest = new DotSUB_Http_Request(self::LANGUAGE, "GET");
	}

	public function languageListing(){
		return $this->httpRequest;
	}

	public function languageMapping($language){
		if(!empty($language)) {
			$this->httpRequest->setQueryParam("code", $language);
		}
		return $this->httpRequest;
	}
}
