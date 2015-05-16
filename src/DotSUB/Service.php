<?php namespace Lti\DotsubAPI;

use Lti\DotsubAPI\Auth\Auth_Simple;
use Lti\DotsubAPI\Http\Http_Request;
use Lti\DotsubAPI\Service\Service_Exception;

class Service
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
     * Constructor for Service
     *
     * @param Client $client
     * @param string $usingExtId
     * @throws Service_Exception
     */
    public function __construct(Client $client, $usingExtId = false)
    {

        if ($usingExtId) {
            // When using an external ID, the request url contains both the
            // external ID and the username.
            if ($client->getClientUsername() == "") {
                throw new Service_Exception("No username found in config, the username is part of the request URL for retrieving data using an external id");
            }
            $url = self::$MEDIA_EXT_ID;
            $this->usingExtId = true;
        } else {
            $url = self::$SERVICE_URL;
        }

        $this->client = $client;
        $this->httpRequest = new Http_Request($url);

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
     * @throws Service_Exception
     */
    public function setUUID($UUID)
    {

        if (!empty($UUID) && isset($UUID) && preg_match('#[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89aAbB][a-f0-9]{3}-[a-f0-9]{12}#',$UUID)) {
            $this->UUID = $UUID;
        } else {
            throw new Service_Exception("The video UUID is incorrect.");
        }

    }

    protected function requestWithAuthentication()
    {
        $this->auth = new Auth_Simple($this->client);
        return $this->auth->sendCredentials($this->httpRequest);
    }

}