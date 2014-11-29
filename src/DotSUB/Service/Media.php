<?php

/**
 * 
 * Handling the Media part of the dotSUB API.
 * Authentication is required in most cases, except when retrieving metadata.
 * 
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Service_Media extends DotSUB_Service {
	const MEDIA_UUID = "https://dotSUB.com/api/media";
	const MEDIA_EXT_ID = "https://dotSUB.com/api/user/username/media";
	/**
	 * dotSUB videos have an external_id field that can be used to query the dS
	 * database with Ids other than the default dS UUID
	 *
	 * @var boolean $usingExtId
	 */
	private $usingExtId = false;
	private $httpRequest;
	private $client;

	/**
	 * Constructor for DotSUB_Service_Media
	 *
	 * @param DotSUB_Client $client        	
	 * @param string $usingExtId        	
	 * @throws DotSUB_Service_Exception
	 */
	public function __construct(DotSUB_Client $client, $usingExtId = false){
		if($usingExtId) {
			// When using an external ID, the request url contains both the
			// external ID and the username.
			if($client->getClientUsername() == "") {
				throw new DotSUB_Service_Exception("<h2>You must give your username as a parameter to DotSUB_Service_Media, the username is part of the request URL for retrieving data using an external id</h2>", "N/A");
			}
			$url = self::MEDIA_EXT_ID;
			$this->usingExtId = true;
		} else {
			$url = self::MEDIA_UUID;
		}
		$this->httpRequest = new DotSUB_Http_Request($url);
		$this->client = $client;
	}

	/**
	 * Path: https://dotSUB.com/api/media
	 *
	 * Method: POST - Requires authentication
	 *
	 * You can upload media to DotSUB from your system using a HTTP Post to our
	 * media upload API. This form does require you to be authenticated.
	 *
	 * @param stdClass $videoData
	 *        	The video data to be sent in the upload
	 */
	public function mediaUpload($videoData){
		if(!$this->client->hasCredentials()) {
			throw new DotSUB_Service_Exception_Authentication();
		}
		$this->httpRequest->setRequestMethod("POST");
		$this->createVideo($videoData, true);
		$this->addVideoProjectInfo($this->client->getClientProject());
		
		$this->httpRequest->setPostBody($this->getVideoInfo());
		
		$this->auth = new DotSUB_Auth_Simple($this->client);
		return $this->auth->sendCredentials($this->httpRequest);
	}

	/**
	 * Path: https://dotSUB.com/api/media/$UUID or
	 * https://dotSUB.com/api/user/$username/media/$externalIdentifier
	 *
	 * Method: GET - No authentication required
	 *
	 * You can retrieve the metadata of a given media via this call.
	 *
	 * @param string $UUID
	 *        	36 character dS UUID
	 * @param string $includeEmptyTranslations
	 *        	If true, also returns the data for translations with a 0%
	 *        	completion.
	 * @return DotSUB_Http_Request
	 */
	public function mediaMetadata($UUID, $includeEmptyTranslations = false){
		if($this->usingExtId) {
			$this->httpRequest->setUrl(str_replace("username", $this->client->getClientUsername(), $this->httpRequest->getUrlToString()));
		}
		$this->httpRequest->appendToUrl("/$UUID");
		
		if($includeEmptyTranslations) {
			$this->httpRequest->setQueryParam("includeEmptyTranslations", "true");
		}
		
		return $this->httpRequest;
	}

	/**
	 * Metadata Editing
	 *
	 * Path: https://dotSUB.com/api/media/$UUID or
	 * https://dotSUB.com/api/user/$username/media/$externalIdentifier
	 *
	 * Method: POST - Authentication required.
	 *
	 * @param string $UUID
	 *        	36 character dS UUID
	 * @param stdClass $videoData
	 *        	The video data to be sent in the upload
	 * @return DotSUB_Http_Request
	 */
	public function metaDataEditing($UUID, $videoData){
		if($this->usingExtId) {
			$this->httpRequest->setUrl(str_replace("username", $this->client->getClientUsername(), $this->httpRequest->getUrlToString()));
		}
		$this->httpRequest->appendToUrl("/$UUID");
		$this->httpRequest->setRequestMethod("POST");
		$this->createVideo($videoData);
		$this->httpRequest->setPostBody($this->getVideoInfo());
		$this->auth = new DotSUB_Auth_Simple($this->client);
		return $this->auth->sendCredentials($this->httpRequest);
	}

	public function mediaDelete(){
		return $this->httpRequest;
	}

	/**
	 * Add Media Permissions
	 *
	 * Path: https://dotSUB.com/api/media/$UUID/permissions
	 *
	 * Method: PUT - Requires authentication.
	 *
	 * The media permission API allows you to add permissions to videos on
	 * DotSUB. Permissions can be added per user or as general settings on a
	 * video.
	 *
	 * @param string $UUID
	 *        	36 character dS UUID
	 * @param array $action
	 *        	The permission to be set/unset
	 * @param string $isAdding
	 *        	Do we set or unset the permission?
	 * @throws DotSUB_Service_Exception_Authentication
	 * @return DotSUB_Http_Request
	 */
	public function manageMediaPermissions($UUID, $action, $isAdding = true){
		if(!$this->client->hasCredentials()) {
			throw new DotSUB_Service_Exception_Authentication();
		}
		$this->httpRequest->appendToUrl("/$UUID/permissions");
		if($isAdding) {
			$this->httpRequest->setRequestMethod("PUT");
		} else {
			$this->httpRequest->setRequestMethod("DELETE");
		}
		
		foreach($action as $k => $v) {
			$this->httpRequest->setQueryParam($k, $v);
		}
		$this->httpRequest->setRequestHeaders(array("Content-Length" => 0));
		
		$this->auth = new DotSUB_Auth_Simple($this->client);
		return $this->auth->sendCredentials($this->httpRequest);
	}

	public function mediaQuery(){
		return $this->httpRequest;
	}

	/**
	 * Transcription Upload
	 *
	 * Path: https://dotsub.com/api/media/$UUID/transcription or
	 * https://dotsub.com/api/user/$username/media/$externalIdentifier/transcription
	 *
	 * Method: POST - Requires authentication
	 *
	 * You can upload an existing caption file as the video transcription. Most
	 * standard subtitle formats are supported (ex: SRT, WebVTT, Timed Text).
	 *
	 * @param string $UUID
	 *        	36 character dS UUID
	 * @param string $filename
	 *        	The file to be uploaded
	 * @param string $language
	 *        	The file language's language code
	 * @throws DotSUB_Service_Exception_Authentication
	 * @return DotSUB_Http_Request
	 */
	public function translationUpload($UUID, $filename, $language){
		if(!$this->client->hasCredentials()) {
			throw new DotSUB_Service_Exception_Authentication();
		}
		$this->httpRequest->appendToUrl("/$UUID/translation");
		$this->httpRequest->setRequestMethod("POST");
		$this->httpRequest->setPostBody(array("file" => "@" . $filename, "language" => $language));
		
		$this->auth = new DotSUB_Auth_Simple($this->client);
		return $this->auth->sendCredentials($this->httpRequest);
	}

	/**
	 * Creating the object that will hold the video information (title, description, filename...)
	 * 
	 * @param stdClass $videoInfo
	 * @param boolean $isUpload Are we going to upload a video?
	 */
	public function createVideo($videoInfo, $isUpload = false){
		$this->video = new DotSUB_Service_Video($videoInfo, $isUpload);
	}
	
	/**
	 * Get the information out of the video object
	 */
	public function getVideoInfo(){
		return $this->video->getVars();
	}
	
	/**
	 * Setting the dotSUB project id (UUID)
	 * 
	 * @param string $projectInfo
	 */
	public function addVideoProjectInfo($projectInfo){
		$this->video->setProject($projectInfo);
	} 
}
