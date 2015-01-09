<?php
require_once realpath(dirname(__FILE__) . '/../autoload.php');
date_default_timezone_set('UTC');

$clientUsername = "";
// insert DotSUB password here
$clientPassword = "";
// insert DotSUB project UUID here (optional)
$clientProject = "";


$client = new DotSUB_Client();
$client->setClientCredentials($clientUsername, $clientPassword);
$client->setClientProject($clientProject);
$service = new DotSUB_Service_Project($client, false);


$request = $service->projectMediaListing(10);
$response = $client->execute($request);

echo "<pre>";
print_r($response);
echo "</pre>";