<?php
require_once realpath(dirname(__FILE__) . '/../autoload.php');
date_default_timezone_set('UTC');





/**
 * Captions API
 * Caption Listing
 *
 * Path: https://dotsub.com/api/media/$UUID/captions?language=$languageCode or
 * https://dotsub.com/api/user/$username/media/$externalIdentifier/captions?language=$languageCode
 *
 * Method: GET
 *
 * You can retrieve the captions of a given media in a selected language via this call. The captions will be returned if
 * they exist. If there are no captions in the selected language an error will be returned.
 */
function captions_listing(){

	$UUID = "";
	$language = "";
	
	$client = new DotSUB_Client();
	$service = new DotSUB_Service_Caption($client, false);
	$service->setUUID($UUID);
	
	$request = $service->captionsListing($language);
	$response = $client->execute($request);
	
	echo "<pre>";
	print_r($response);
	echo "</pre>";

}

/**
 * Transcription or translation Upload
 *
 * Path: https://dotsub.com/api/media/$UUID/transcription or
 * https://dotsub.com/api/user/$username/media/$externalIdentifier/transcription
 *
 * Method: POST
 *
 * You can upload an existing caption file as the video transcription. Most standard subtitle formats are supported (ex:
 * SRT, WebVTT, Timed Text).
 */
function transcription_upload(){
	// insert DotSUB username here
	$clientUsername = "";
	// insert DotSUB password here
	$clientPassword = "";
	// insert UUID of the video to manipulate
	$UUID = "";
	
	$client = new DotSUB_Client();
	$client->setClientCredentials($clientUsername, $clientPassword);
	$service = new DotSUB_Service_Caption($client, false);
	$service->setUUID($UUID);
	
	$request = $service->subtitleUpload("path/file", "language code, see language table", "transcription or translation");
	$response = $client->execute($request);
	
	echo "<pre>";
	print_r($response);
	echo "</pre>";

}

/**
 * Transcription or translation Quality Level
 *
 * Path: https://dotsub.com/api/media/$UUID/transcription or
 * https://dotsub.com/api/user/$username/media/$externalIdentifier/transcription
 *
 * Method: POST
 *
 * You can tag the quality level of a transcription using this API. This value will be returned in all metadata calls
 * for future use.
 */
function transcription_quality(){
	// insert DotSUB username here
	$clientUsername = "";
	// insert DotSUB password here
	$clientPassword = "";
	// insert UUID of the video to manipulate
	$UUID = "";
	
	$client = new DotSUB_Client();
	$client->setClientCredentials($clientUsername, $clientPassword);
	$service = new DotSUB_Service_Caption($client, false);
	$service->setUUID($UUID);
	
	$request = $service->setQualityLevel("quality level", "language", "translation or transcription");
	$response = $client->execute($request);
	
	echo "<pre>";
	print_r($response);
	echo "</pre>";

}

/**
 * Translation/Transcription File Download
 *
 * Path: https://dotsub.com/media/$UUID/c/$languageCode/$format or
 * https://dotsub.com/media/u/$username/$externalIdentifier/c/$languageCode/$format
 *
 * Method: POST
 *
 * Translation and transcription files can be downloaded from Dotsub using the following requests. Any private videos
 * will require you to be authenticated to download.
 */
function subtitle_download(){
	// insert DotSUB username here
	$clientUsername = "";
	// insert DotSUB password here
	$clientPassword = "";
	// insert UUID of the video to manipulate
	$UUID = "";
	
	$client = new DotSUB_Client();
	$client->setClientCredentials($clientUsername, $clientPassword);
	$service = new DotSUB_Service_Caption($client, false);
	$service->setUUID($UUID);
	
	$request = $service->subtitleDownload("language", "format");
	$request->setFileName("<name of file>");
	$response = $client->execute($request);
	
	echo "<pre>";
	print_r($response);
	echo "</pre>";

}
