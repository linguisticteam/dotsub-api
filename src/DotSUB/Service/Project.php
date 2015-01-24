<?php namespace Lti\DotsubAPI\Service;

use Lti\DotsubAPI\DotSUB_Client;
use Lti\DotsubAPI\DotSUB_Exception;
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

    /**
     * Constructor for the Project API. The client has to be initialized with both the user's credentials and project
     * before instantiating this class.
     *
     * @param DotSUB_Client $client
     * @throws DotSUB_Service_Exception
     * @throws DotSUB_Service_Exception_Project
     */
    public function __construct(DotSUB_Client $client)
    {
        self::$SERVICE_URL = "https://dotSUB.com/api/project";
        parent::__construct($client);
        $this->projectID = $this->client->getClientProject();
        if (!$this->projectID) {
            throw new DotSUB_Service_Exception_Project();
        }
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
        $this->httpRequest->appendToUrl("/" . $this->projectID . "/media");
        $this->httpRequest->setQueryParam("limit", $limit);
        $this->httpRequest->setQueryParam("start", $start);
        return $this->requestWithAuthentication();
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
     * @return DotSUB_Http_Request
     * @throws DotSUB_Exception
     */
    public function addMediaToProject()
    {
        return $this->toggleProjectMedia();
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
     * @return DotSUB_Http_Request
     * @throws DotSUB_Exception
     */
    public function removeMediaFromProject()
    {
        return $this->toggleProjectMedia("DELETE");
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
     * @param int $start index from which to start
     * @throws DotSUB_Service_Exception
     */
    public function getProjectUsers($type, $limit = 20, $start = 0)
    {
        switch ($type) {
            case 'transcribers':
            case 'translators':
                break;
            default:
                throw new DotSUB_Service_Exception('The user parameter must be set to either "transcribers" or "translators"');
        }
        $this->httpRequest->appendToUrl("/" . $this->projectID . "/" . $type);
        return $this->requestWithAuthentication();
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
     * @return DotSUB_Http_Request
     * @throws DotSUB_Service_Exception
     */
    public function listProjectUserDetails($type, $username)
    {
        switch ($type) {
            case 'transcribers':
            case 'translators':
                break;
            default:
                throw new DotSUB_Service_Exception('The user parameter must be set to either "transcribers" or "translators"');
        }
        $this->httpRequest->appendToUrl("/" . $this->projectID . "/" . $type . "/" . $username);
        return $this->requestWithAuthentication();
    }


    /**
     *
     * Add/Update Project Users
     *
     * Path: https://dotsub.com/api/project/$projectId/users
     *
     * Method: POST
     *
     * This call adds or updates a user. If a user already exists in a project this API call will update their list of languages, appending any new values. If they do not exist in the project they will be added.
     * Request Parameters
     * Name    Description    Required
     * username    The username of the user to be added to the project.    Yes
     * language    The language code the user can work in. You can append multiple languages at a time by posting the parameter more than once in a request.    Yes
     *
     * @param $username
     * @param array $languages
     * @return DotSUB_Http_Request
     * @throws DotSUB_Service_Exception
     */
    public function addProjectUser($username, array $languages)
    {
        $this->httpRequest->setRequestMethod('POST');
        $this->httpRequest->appendToUrl("/" . $this->projectID . "/users");

        if (!$username) {
            throw new DotSUB_Service_Exception('The user to add to the project is empty');
        }
        $url = $this->httpRequest->getUrlToString().'?username='.$username;
        $url.= "&language=".implode('&language=',$languages);
        $this->httpRequest->setUrl($url);

        return $this->requestWithAuthentication();
    }

    /**
     *
     * Remove Project User
     *
     * Path: https://dotsub.com/api/project/$projectId/users
     *
     * Method: DELETE
     *
     * This call removes a user from a project.
     * Request Parameters
     * Name    Description                                              Required
     * username    The username of the user to be added to the project    Yes
     * @param $username
     * @return DotSUB_Http_Request
     * @throws DotSUB_Service_Exception
     */
    public function removeProjectUser($username)
    {
        $this->httpRequest->setRequestMethod('DELETE');
        if ($username) {
            $this->httpRequest->setQueryParam('username', $username);
        } else {
            throw new DotSUB_Service_Exception('The user to remove from the project is empty');
        }
        $this->httpRequest->appendToUrl("/" . $this->projectID . "/users");

        return $this->requestWithAuthentication();
    }

    /**
     *
     * Listing Project Users
     *
     * Path: https://dotsub.com/api/project/$projectId/users
     *
     * Method: GET
     *
     * This API allows you to get the list of users and languages you have added to a project.
     * Request Parameters
     * Name    Description    Required
     * limit    The number of results per page. This defaults to 20.    No
     * start    The first result to return. This defaults to 0.    No
     *
     * @param int $limit
     * @param int $start
     * @return DotSUB_Http_Request
     */
    public function listProjectUsers($limit = 20, $start = 0)
    {
        $this->httpRequest->appendToUrl("/" . $this->projectID . "/users");
        $this->httpRequest->setQueryParam("limit", $limit);
        $this->httpRequest->setQueryParam("start", $start);

        return $this->requestWithAuthentication();
    }

    /**
     * Adds or deletes a media from a project, using the media's UUID.
     * A media UUID can be set using DotSUB_Service::setUUID();
     *
     *
     * @see Lti\DotsubAPI\DotSUB_Service::setUUID();
     * @param string $action
     * @return DotSUB_Http_Request
     * @throws DotSUB_Exception
     */
    private function toggleProjectMedia($action = 'PUT')
    {
        $this->httpRequest->appendToUrl("/" . $this->projectID . "/media");
        $this->httpRequest->setRequestMethod(($action == 'PUT') ? $action : 'DELETE');
        if ($this->UUID) {
            $this->httpRequest->setQueryParam('uuid', $this->UUID);
        } else {
            throw new DotSUB_Exception('The UUID of the media to add/remove from a project is missing.');
        }
        return $this->requestWithAuthentication();
    }


}