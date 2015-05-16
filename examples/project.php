<?php require_once '../vendor/autoload.php';
date_default_timezone_set('UTC');

use Lti\DotsubAPI\Client;
use Lti\DotsubAPI\Service\Service_Project;





/**
 *
 * Project Media Listing
 *
 * Path: https://dotsub.com/api/project/$projectId/media
 *
 * Method: GET
 *
 * The project media API will list all media in a given project.
 * Request Parameters
 * Name     Description                                             Required
 * limit    The number of results per page. This defaults to 20.    No
 * start    The first result to return. This defaults to 0.         No
 *
 * @param int $limit
 * @param int $start
 */
function projectMediaListing($limit = 20, $start = 0)
{
    $clientUsername = '';
    $clientPassword = '';
    $clientProject = '';

    $client = new Client();
    $client->setClientCredentials($clientUsername, $clientPassword);
    $client->setClientProject($clientProject);

    $service = new Service_Project($client);
    $request = $service->projectMediaListing($limit, $start);
    $response = $client->execute($request);

    echo "<pre>";
    print_r($response);
    echo "</pre>";
}


/**
 * Add Media to Project
 *
 * Path: https://dotsub.com/api/project/$projectId/media
 *
 * Method: PUT
 *
 * The project add media API allows you to add videos from your project on Dotsub.
 * Request Parameters
 * Name    Description                                          Required
 * uuid    The uuid of the media to be added to the project.    Yes
 *
 */
function addMediaToProject($UUID)
{
    $clientUsername = '';
    $clientPassword = '';
    $clientProject = '';

    $client = new Client();
    $client->setClientCredentials($clientUsername, $clientPassword);
    $client->setClientProject($clientProject);

    $service = new Service_Project($client);
    $service->setUUID($UUID);
    $request = $service->addMediaToProject();
    $response = $client->execute($request);

    echo "<pre>";
    print_r($response);
    echo "</pre>";
}

/**
 *
 * Remove Media from Project
 *
 * Path: https://dotsub.com/api/project/$projectId/media
 *
 * Method: DELETE
 *
 * The project remove media API allows you to remove videos from your project on Dotsub.
 * Request Parameters
 * Name    Description                                          Required
 * uuid    The uuid of the media to be added to the project.    Yes
 *
 * @param $UUID
 */
function removeMediaFromProject($UUID)
{
    $clientUsername = '';
    $clientPassword = '';
    $clientProject = '';

    $client = new Client();
    $client->setClientCredentials($clientUsername, $clientPassword);
    $client->setClientProject($clientProject);

    $service = new Service_Project($client);
    $service->setUUID($UUID);
    $request = $service->removeMediaFromProject();
    $response = $client->execute($request);

    echo "<pre>";
    print_r($response);
    echo "</pre>";
}

/**
 * Project Transcribers/Translators
 *
 * Path: https://dotsub.com/api/project/$projectId/transcribers and
 * https://dotsub.com/api/project/$projectId/translators
 *
 * Method: GET
 *
 * This API allows you to get the list of transcribers and translators working on your Dotsub project.
 * Request Parameters
 * Name    Description                                              Required
 * limit    The number of results per page. This defaults to 20.    No
 * start    The first result to return. This defaults to 0.         No
 *
 * @param string $type trancribers or translators
 * @param int $limit limit of users per page
 */
function getProjectUsers($type, $limit = 20, $start = 0)
{
    $clientUsername = '';
    $clientPassword = '';
    $clientProject = '';

    $client = new Client();
    $client->setClientCredentials($clientUsername, $clientPassword);
    $client->setClientProject($clientProject);

    $service = new Service_Project($client);
    $request = $service->getProjectUsers($type, $limit, $start);
    $response = $client->execute($request);

    echo "<pre>";
    print_r($response);
    echo "</pre>";
}

/**
 * Project Transcribers/Translators Details
 *
 * Path: https://dotsub.com/api/project/$projectId/transcribers/$username and
 * https://dotsub.com/api/project/$projectId/translators/$username
 *
 * Method: GET
 *
 * This API allows you to get the listing of the work for a single user in your project.
 *
 * @param $type
 * @param $username
 */
function listProjectUserDetails($type, $username)
{
    $clientUsername = '';
    $clientPassword = '';
    $clientProject = '';

    $client = new Client();
    $client->setClientCredentials($clientUsername, $clientPassword);
    $client->setClientProject($clientProject);

    $service = new Service_Project($client);
    $request = $service->listProjectUserDetails($type, $username);
    $response = $client->execute($request);

    echo "<pre>";
    print_r($response);
    echo "</pre>";
}


