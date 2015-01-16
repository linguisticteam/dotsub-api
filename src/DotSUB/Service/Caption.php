<?php
namespace Lti\DotsubAPI\Service;

use Lti\DotsubAPI\Auth\DotSUB_Auth_Simple;
use Lti\DotsubAPI\DotSUB_Service;
use Lti\DotsubAPI\Http\DotSUB_Http_Request;

/**
 *
 * Handling the Caption part of the dotSUB API.
 * Authentication is required in most cases, except when retrieving metadata.
 *
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Service_Caption extends DotSUB_Service {

	/**
	 * Caption Listing
	 *
	 * Path: https://dotsub.com/api/media/$UUID/captions?language=$languageCode or
	 * https://dotsub.com/api/user/$username/media/$externalIdentifier/captions?language=$languageCode
	 *
	 * Method: GET
	 *
	 * You can retrieve the captions of a given media in a selected language via this call. The captions will be
	 * returned if they exist. If there are no captions in the selected language an error will be returned.
	 *
	 * @param string $language The language for which the captions should be listed
	 * @return DotSUB_Http_Request
	 */
	public function captionsListing($language){

		$this->httpRequest->appendToUrl("/" . $this->UUID . "/captions?language=$language");
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
	 * You can upload an existing caption file as the video transcription. Most standard subtitle formats are supported
	 * (ex: SRT, WebVTT, Timed Text).
	 *
	 * @param string $filename The file to be uploaded
	 * @param string $language The file language's language code
	 * @param string $type The type of file (transcription/translation)
	 * @throws DotSUB_Service_Exception_Authentication
	 * @return DotSUB_Http_Request
	 */
	public function subtitleUpload($filename, $language = "", $type = "translation"){

		if(!$this->client->hasCredentials()) {
			throw new DotSUB_Service_Exception_Authentication();
		}
		
		if($type != 'translation' && $type != "transcription") {
			throw new DotSUB_Service_Exception("Subtitle type incorrect (translation/transcription).");
		}
		$this->httpRequest->appendToUrl("/" . $this->UUID . "/" . $type);
		$this->httpRequest->setRequestMethod("POST");
		$postItems = array(
			"file" => "@" . $filename
		);
		if($type == "translation") {
			if(empty($language)) {
				throw new DotSUB_Service_Exception("The language of the uploaded file cannot be empty.");
			}
			$postItems["language"] = $language;
		}
		$this->httpRequest->setPostBody($postItems);
		
		$this->auth = new DotSUB_Auth_Simple($this->client);
		return $this->auth->sendCredentials($this->httpRequest);
	
	}

	/**
	 * Transcription/Translation Quality Level
	 *
	 * Path: https://dotsub.com/api/media/$UUID/transcription or
	 * https://dotsub.com/api/user/$username/media/$externalIdentifier/transcription
	 *
	 * Method: POST
	 *
	 * You can tag the quality level of a transcription using this API. This value will be returned in all metadata
	 * calls for future use.
	 *
	 * @param string $level
	 * @param string $language
	 * @param string $type
	 * @throws DotSUB_Service_Exception_Authentication
	 * @throws DotSUB_Service_Exception
	 * @return DotSUB_Http_Request
	 */
	public function setQualityLevel($level, $language = "", $type = "translation"){

		if(!$this->client->hasCredentials()) {
			throw new DotSUB_Service_Exception_Authentication();
		}
		
		if($type != 'translation' && $type != "transcription") {
			throw new DotSUB_Service_Exception("Subtitle type incorrect (translation/transcription).");
		}
		$this->httpRequest->appendToUrl("/" . $this->UUID . "/" . $type);
		$this->httpRequest->setRequestMethod("POST");
		$postItems = array(
			"level" => $level
		);
		
		if($type == "translation") {
			if(empty($language)) {
				throw new DotSUB_Service_Exception("The language of the uploaded file cannot be empty.");
			}
			$postItems["language"] = $language;
		}
		$this->httpRequest->setPostBody($postItems);
		
		$this->auth = new DotSUB_Auth_Simple($this->client);
		return $this->auth->sendCredentials($this->httpRequest);
	
	}

	/**
	 * Translation/Transcription File Download
	 *
	 * Path: https://dotsub.com/media/$UUID/c/$languageCode/$format or
	 * https://dotsub.com/media/u/$username/$externalIdentifier/c/$languageCode/$format
	 *
	 * Method: POST
	 *
	 * Translation and transcription files can be downloaded from Dotsub using the following requests. Any private
	 * videos will require you to be authenticated to download.
	 *
	 * @param string $language The language of the subtitle file to download
	 * @param string $format The format of the file (srt,tt,vtt)
	 * @throws DotSUB_Service_Exception_Authentication
	 * @throws DotSUB_Service_Exception
	 */
	public function subtitleDownload($language, $format = "srt"){

		if(!$this->client->hasCredentials()) {
			throw new DotSUB_Service_Exception_Authentication();
		}
		
		if($format != 'srt' && $format != "tt" && $format != "vtt") {
			throw new DotSUB_Service_Exception("Subtitle format/file extension not recognized.");
		}
		
		$this->httpRequest->setUrl("https://dotsub.com/media/");
		$this->httpRequest->appendToUrl($this->UUID . "/c/" . $language . "/" . $format);
		$this->httpRequest->setRequestMethod("POST");
		
		$postItems = array(
			"language" => $language, "format" => $format
		);
		
		$this->httpRequest->setPostBody($postItems);
		
		$this->auth = new DotSUB_Auth_Simple($this->client);
		return $this->auth->sendCredentials($this->httpRequest);
	
	}

}
