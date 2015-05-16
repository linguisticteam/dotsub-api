<?php namespace Lti\DotsubAPI\Auth;

use Lti\DotsubAPI\Client;
use Lti\DotsubAPI\Http\Http_Request;

/**
 * Class Auth_Simple
 * The authentication scheme is pretty simple now, but that might change
 * so we added basic support for it, expecting more sophisticated auth mechanisms in the future.
 *
 * @package Lti\DotsubAPI\Auth
 */
class Auth_Simple
{
    private $client;

    public function __construct( Client $client, $config = null )
    {
        $this->client = $client;
    }

    public function sendCredentials( Http_Request $request )
    {
        $this->client->getIo()->setCredentials( $this->client->getClientCredentials() );

        return $request;
    }
}