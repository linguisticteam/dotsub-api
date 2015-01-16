<?php namespace Lti\DotsubAPI\Service;

use Lti\DotsubAPI\DotSUB_Client;
use Lti\DotsubAPI\DotSUB_Service;
use Lti\DotsubAPI\Http\DotSUB_Http_Request;

/**
 *
 * Handling the Project part of the dotSUB API.
 * Authentication is required in most cases, except when retrieving metadata.
 *
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Service_Project extends DotSUB_Service
{

    public function __construct(DotSUB_Client $client)
    {

        self::$SERVICE_URL = "https://dotSUB.com/api/project";
        parent::__construct($client);
    }

    /**
     * Project Listing
     *
     * Path: https://dotsub.com/api/project
     *
     * Method: GET
     *
     * The project listing API will list all projects a user currently manages.
     *
     * @return DotSUB_Http_Request
     */
    public function projectListing()
    {
        return $this->requestWithAuthentication();
    }

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
     * @return DotSUB_Http_Request
     * @throws DotSUB_Service_Exception_Project
     */
    public function projectMediaListing($limit = 20, $start = 0)
    {
        $projectID = $this->client->getClientProject();
        if (!$projectID) {
            throw new DotSUB_Service_Exception_Project();
        }
        $this->httpRequest->appendToUrl("/" . $projectID . "/media");
        $this->httpRequest->setQueryParam("limit", $limit);
        $this->httpRequest->setQueryParam("start", $start);
        return $this->requestWithAuthentication();
    }
}