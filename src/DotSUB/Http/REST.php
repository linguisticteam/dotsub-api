<?php

/**
 * Trying to make the request as RESTful as possible,
 * handles the actual execution of the request. 
 * 
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Http_REST {

	/**
	 * Uses the IO method configured to execute the request.
	 *
	 * @param DotSUB_Client $client        	
	 * @param DotSUB_Http_Request $req        	
	 * @return array The JSON response tranformed into an array.
	 */
	public static function execute(DotSUB_Client $client, DotSUB_Http_Request $req){
		try {
			$httpRequest = $client->getIo()->makeRequest($req);
			return self::decodeHttpResponse($httpRequest);
		} catch(DotSUB_Service_Exception $e) {
		}
	}

	/**
	 * Does some error handling after execution of the request.
	 *
	 * dotSUB usually responds in a JSON format, like this:
	 * <code>
	 * [status] => Array
	 * (
	 * [message] => Permission removed from media
	 * [error] =>
	 * [code] => 200
	 * )
	 * </code>
	 *
	 * @param DotSUB_Http_Request $response        	
	 * @throws DotSUB_Service_Exception
	 * @return array The JSON response tranformed into an array.
	 */
	public static function decodeHttpResponse(DotSUB_Http_Request $response){
		$code = $response->getResponseHttpCode();
		$body = $response->getResponseBody();
		$decoded = null;
		$msg = null;
		
		if((intVal($code)) >= 300) {
			$decoded = json_decode($body, true);
			if(!empty($decoded) && isset($decoded['status']['message'])) {
				$msg = $decoded['status']['message'];
			}
			
			$err = 'The ' . $response->getRequestMethod() . ' request to "' . $response->getUrl() . '" failed.';
			$err .= " Error Code: ($code)<br/>$msg<br/> $body";
			
			throw new DotSUB_Service_Exception($err, $code);
		}
		
		$decoded = json_decode($body, true);
		//If dotSUB returns an error in JSON format
		if(isset($decoded['status']['error']) && $decoded['status']['error'] == "true") {
			$err = 'The ' . $response->getRequestMethod() . ' request to "' . $response->getUrl() . '" failed.';
			$err .= " Error Code: (" . $decoded['status']['code'] . ")<br/>" . $decoded['status']['message'] . "<br/> $body";
			
			throw new DotSUB_Service_Exception($err, $code);
		}
		if($decoded === null || $decoded === "") {
			throw new DotSUB_Service_Exception("Invalid json in service response: $body");
		}
		
		return $decoded;
	}
}