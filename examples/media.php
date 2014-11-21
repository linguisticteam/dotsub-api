<?php
require_once realpath(dirname(__FILE__) . '/../autoload.php');






/**
 * Translation Upload
 *
 * Path: https://dotsub.com/api/media/$UUID/translation or
 * https://dotsub.com/api/user/$username/media/$externalIdentifier/translation
 *
 * Method: POST
 *
 * You can upload an existing caption file as a video translation. Most standard
 * subtitle formats are supported (ex: SRT, WebVTT, Timed Text).
 */
function upload_subtitle(){
	// insert DotSUB username here
	$clientUsername = "";
	// insert DotSUB password here
	$clientPassword = "";
	// insert DotSUB project UUID here (optional)
	$clientProject = "";
	// insert UUID of the video to manipulate
	$UUID = "";
	
	$client = new DotSUB_Client();
	$client->setClientCredentials($clientUsername, $clientPassword);
	$client->setClientProject($clientProject);
	$service = new DotSUB_Service_Media($client, false);
	
	$request = $service->translationUpload($UUID, "path/file", "language code, see language table");
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
	// insert DotSUB project UUID here (optional)
	$clientProject = "";
	// insert UUID of the video to manipulate
	$UUID = "";
	
	$client = new DotSUB_Client();
	$client->setClientCredentials($clientUsername, $clientPassword);
	$client->setClientProject($clientProject);
	$service = new DotSUB_Service_Media($client, false);
	
	$action = array("action" => "READ/WRITE/UPDATE_TRANSCRIPTION/CREATE_TRANSLATION", "username" => "if needed");
	
	// the default behavior adds permissions
	$request = $service->manageMediaPermissions($UUID, $action);
	// third parameter is set to false for deletions
	$request = $service->manageMediaPermissions($UUID, $action, false);
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
	$request = $service->mediaMetadata($UUID, true);
	$response = $client->execute($request);
	
	echo "<pre>";
	print_r($response);
	echo "</pre>";
}

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
	// insert DotSUB project UUID here (optional)
	$clientProject = "";
	// insert UUID of the video to manipulate
	$UUID = "";
	
	$client = new DotSUB_Client();
	$client->setClientCredentials($clientUsername, $clientPassword);
	$client->setClientProject($clientProject);
	$service = new DotSUB_Service_Media($client);
	
	$v = new stdClass();
	$v->title = "title";
	$v->description = "description";
	$v->language = "language";
	$request = $service->metaDataEditing($UUID, $v);
	$response = $client->execute($request);
	
	echo "<pre>";
	print_r($response);
	echo "</pre>";
}
