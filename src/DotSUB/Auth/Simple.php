<?php
require_once realpath(dirname(__FILE__) . '/../../../autoload.php');

/**
 * The authentication scheme is pretty simple now, but that might change
 * so we added basic support for it, expecting more sophisticated auth mechanisms in the future.
 * 
 * 
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Auth_Simple {
	private $client;

	public function __construct(DotSUB_Client $client, $config = null){
		$this->client = $client;
	}

	public function sendCredentials(DotSUB_Http_Request $request){
		$this->client->getIo()->setCredentials($this->client->getClientCredentials());
		return $request;
	}
}