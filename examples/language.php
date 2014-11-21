<?php
require_once realpath(dirname(__FILE__) . '/../autoload.php');






/**
 * Language Mapping
 *
 * Path: https://dotsub.com/api/language?code=$languageCode
 *
 * Method: GET
 *
 * Some sites use ISO-693-2 others use ISO-693-3 language codes. This method
 * provides an API method to map these codes to Dotsub's language codes. You can
 * send any code to Dotsub and it will reply with the code that should be used
 * to load that language on Dotsub.com
 *
 * @param string $language
 *        	The language code
 */
function display_one_language($language){
	$client = new DotSUB_Client();
	$service = new DotSUB_Service_Language($client);
	$request = $service->languageMapping($language);
	$response = $client->execute($request);
	echo "<pre>";
	print_r($response);
	echo "</pre>";
}

/**
 * Language Listing
 *
 * Path: https://dotsub.com/api/language
 *
 * Method: GET
 *
 * Shows a listing of the languages supported by Dotsub.
 */
function display_all_languages(){
	$client = new DotSUB_Client();
	$service = new DotSUB_Service_Language($client);
	$request = $service->languageListing();
	$response = $client->execute($request);
	displayTable(array("Code", "Language"), $response['languages']);
}


function displayTable($columns, $data){
	if(is_array($columns) && !empty($columns) && !empty($data)) {
		
		echo "<table><thead>
			<tr>";
		foreach($columns as $value) {
			echo "<th scope=\"col\">" . $value . "</th>\n";
		}
		echo "</tr>
	  		</thead>
	  		<tbody>";
		foreach($data as $key => $val) {
			echo "<tr><td>$key</td><td>$val</td></tr>\n";
		}
		echo "</tbody>
	  	</table><br/><br/>";
	}
}