<?php namespace Lti\DotsubAPI;

use Lti\DotsubAPI\Auth\DotSUB_Auth_Simple;
use Lti\DotsubAPI\Http\DotSUB_Http_Request;
use Lti\DotsubAPI\Service\DotSUB_Service_Exception;

class DotSUB_Service
{
    protected static $SERVICE_URL = "https://dotSUB.com/api/media";
    protected static $MEDIA_EXT_ID = "https://dotSUB.com/api/user/username/media";
    /**
     * dotSUB videos have an external_id field that can be used to query the dS
     * database with Ids other than the default dS UUID
     *
     * @var boolean $usingExtId
     */
    protected $usingExtId = false;
    protected $httpRequest;
    protected $client;
    protected $UUID;
    protected $auth;

    /**
     * Constructor for DotSUB_Service
     *
     * @param DotSUB_Client $client
     * @param string $usingExtId
     * @throws DotSUB_Service_Exception
     */
    public function __construct(DotSUB_Client $client, $usingExtId = false)
    {

        if ($usingExtId) {
            // When using an external ID, the request url contains both the
            // external ID and the username.
            if ($client->getClientUsername() == "") {
                throw new DotSUB_Service_Exception("No username found in config, the username is part of the request URL for retrieving data using an external id");
            }
            $url = self::$MEDIA_EXT_ID;
            $this->usingExtId = true;
        } else {
            $url = self::$SERVICE_URL;
        }

        $this->client = $client;
        $this->httpRequest = new DotSUB_Http_Request($url);

        if ($usingExtId) {
            $this->httpRequest->setUrl(str_replace("username", $this->client->getClientUsername(),
                $this->httpRequest->getUrlToString()));
        }

    }

    public function getClient()
    {

        return $this->client;

    }

    /**
     * Checks the 36 character UUID
     *
     * @param string $UUID
     * @throws DotSUB_Service_Exception
     */
    public function setUUID($UUID)
    {

        if (!empty($UUID) || !isset($UUID) || strlen($UUID) != 36) {
            $this->UUID = $UUID;
        } else {
            throw new DotSUB_Service_Exception("The video UUID is incorrect.");
        }

    }

    protected function requestWithAuthentication()
    {
        $this->auth = new DotSUB_Auth_Simple($this->client);
        return $this->auth->sendCredentials($this->httpRequest);
    }

}