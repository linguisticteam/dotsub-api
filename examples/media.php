<?php require_once realpath(dirname(__FILE__) . '/../autoload.php');
date_default_timezone_set('UTC');

use Lti\DotsubAPI\DotSUB_Client;
use Lti\DotsubAPI\Service\DotSUB_Service_Media;
use Lti\DotsubAPI\DotSUB_Config;



/**
 * Upload
 *
 * Path: https://dotsub.com/api/media
 *
 * Method: POST
 *
 * You can upload media to Dotsub from your system using a HTTP Post to our
 * media upload API. This form does require you to be authenticated.
 */
function upload_media(){
	// insert DotSUB username here
	$clientUsername = "";
	// insert DotSUB password here
	$clientPassword = "";
	// insert DotSUB project UUID here (optional)
	$clientProject = "";
	
	$client = new DotSUB_Client();
	$client->setClientCredentials($clientUsername, $clientPassword);
	$client->setClientProject($clientProject);
	$service = new DotSUB_Service_Media($client, false);
	
	$v = new stdClass();
	$v->title = "video title";
	$v->description = "video description";
	$v->language = DotSUB_Config::DS_LANG_ISO_CODE;
	// Default DotSUB license for new videos:
	// CC-Attribution Non-Commercial No Derivatives 3.0
	$v->license = DotSUB_Config::DS_LICENSE;
	$v->project = "uuid of the project";
	$v->director = "director";
	$v->producer = "producer";
	$v->language = "language";
	$v->file = "file";
	
	$request = $service->mediaUpload($v);
	$response = $client->execute($request);
	
	echo "<pre>";
	print_r($response);
	echo "</pre>";

}

/**
 * Metadata
 *
 * Path: https://dotsub.com/api/media/$UUID or
 * https://dotsub.com/api/user/$username/media/$externalIdentifier
 *
 * Method: GET
 *
 * You can retrieve the metadata of a given media via this call.
 */
function display_media_metadata(){
	// insert UUID of the video to manipulate
	$UUID = "";
	
	$client = new DotSUB_Client();
	$service = new DotSUB_Service_Media($client);
	$service->setUUID($UUID);
	$request = $service->mediaMetadata(true);
	$response = $client->execute($request);
	
	echo "<pre>";
	print_r($response);
	echo "</pre>";

}

/**
 * Metadata Editing
 *
 * Path: https://dotsub.com/api/media/$UUID or
 * https://dotsub.com/api/user/$username/media/$externalIdentifier
 *
 * Method: POST
 *
 * You can update the metadata for a media via this call. Any omitted fields
 * will retain their existing values.
 */
function update_metadata(){
	// insert DotSUB username here
	$clientUsername = "";
	// insert DotSUB password here
	$clientPassword = "";
	// insert UUID of the video to manipulate
	$UUID = "";
	
	$client = new DotSUB_Client();
	$client->setClientCredentials($clientUsername, $clientPassword);
	$service = new DotSUB_Service_Media($client);
	$service->setUUID($UUID);
	
	$v = new stdClass();
	$v->title = "";
	// $v->description = "description";
	// $v->language = "language";
	// $v->transcriptionStatus = "COMPLETED";
	
	$request = $service->mediaDelete();
	$response = $client->execute($request);
	
	echo "<pre>";
	print_r($response);
	echo "</pre>";

}

/**
 * Media Delete
 *
 * Path: https://dotsub.com/api/media/$UUID or
 * https://dotsub.com/api/user/$username/media/$externalIdentifier
 *
 * Method: POST
 *
 * A media can be removed from the system using this API call.
 */
function delete_media(){
	// insert DotSUB username here
	$clientUsername = "";
	// insert DotSUB password here
	$clientPassword = "";
	// insert UUID of the video to manipulate
	$UUID = "";
	
	$client = new DotSUB_Client();
	$client->setClientCredentials($clientUsername, $clientPassword);
	$service = new DotSUB_Service_Media($client, false);
	$service->setUUID($UUID);
	
	$request = $service->mediaDelete();
	$response = $client->execute($request);
	
	echo "<pre>";
	print_r($response);
	echo "</pre>";

}

/**
 * Add Media Permissions
 *
 * Path: https://dotsub.com/api/media/$UUID/permissions
 *
 * Method: PUT
 * The media permission API allows you to add permissions to videos on Dotsub.
 * Permissions can be added per user or as general settings on a video.
 *
 * Method: DELETE
 * The media permission API allows you to delete permissions from videos on
 * Dotsub.
 */
function manage_permissions(){
	// insert DotSUB username here
	$clientUsername = "";
	// insert DotSUB password here
	$clientPassword = "";
	// insert UUID of the video to manipulate
	$UUID = "";
	
	$client = new DotSUB_Client();
	$client->setClientCredentials($clientUsername, $clientPassword);
	$service = new DotSUB_Service_Media($client, false);
	
	$action = array(
		"action" => "READ/WRITE/UPDATE_TRANSCRIPTION/CREATE_TRANSLATION", "username" => "if needed"
	);
	$service->setUUID($UUID);
	
	// the default behavior adds permissions
	$request = $service->manageMediaPermissions($action);
	// third parameter is set to false for deletions
	$request = $service->manageMediaPermissions($action, false);
	$response = $client->execute($request);
	
	echo "<pre>";
	print_r($response);
	echo "</pre>";

}

/**
 * Media Query
 *
 * Path: https://dotsub.com/api/media
 *
 * Method: GET
 *
 * The Media Query API allows you to query public videos in Dotsub. This API
 * returns any videos that are public and publicly listed.
 */
function media_query(){

	$client = new DotSUB_Client();
	$service = new DotSUB_Service_Media($client, false);
	
	/**
	 * Examples of searches:
	 * "title:pig and title:blanket and language:eng"
	 * start with: "title:elephant*"
	 * containing: "title:elephant~
	 * ranges:			"year:[2008 TO 2012]
	 */
	
	$queryString = "";
	$limit = 20;
	$start = 0;
	
	$request = $service->mediaQuery($queryString, $limit, $start);
	
	$response = $client->execute($request);
	echo "<pre>";
	print_r($response);
	echo "</pre>";

}


