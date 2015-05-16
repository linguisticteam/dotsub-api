<?php
namespace Lti\DotsubAPI\Http;

use Lti\DotsubAPI\Client;
use Lti\DotsubAPI\Service\Service_Exception;
use Lti\DotsubAPI\Service\Service_Exception_Bad_Gateway;
use Lti\DotsubAPI\Service\Service_Exception_Forbidden;
use Lti\DotsubAPI\Service\Service_Exception_Invalid_Credentials;
use Lti\DotsubAPI\Service\Service_Exception_Server_Error;

/**
 * Trying to make the request as RESTful as possible,
 * handles the actual execution of the request.
 *
 *
 *
 */
class Http_REST
{
    public static $httpResponse;

    /**
     * Uses the IO method configured to execute the request.
     *
     * @param Client $client
     * @param Http_Request $req
     * @param boolean $format
     * @return \stdClass The JSON response.
     */
    public static function execute(Client $client, Http_Request $req, $format = true)
    {

        static::$httpResponse = $client->getIo()->makeRequest($req);
        return self::decodeHttpResponse(static::$httpResponse, $format);

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
     * @param Http_Request $response
     * @throws Service_Exception
     * @return array The JSON response tranformed into an array.
     */
    public static function decodeHttpResponse(Http_Request $response, $format)
    {

        $code = $response->getResponseHttpCode();
        $body = $response->getResponseBody();
        $decoded = null;
        $msg = null;

        if ((intVal($code)) >= 300) {
            $decoded = json_decode($body);
            if (!empty($decoded) && isset($decoded->status->message)) {
                $msg = $decoded->status->message;
            }

            $err = 'The ' . $response->getRequestMethod() . ' request to "' . $response->getUrl() . '" failed.';
            switch ($code) {
                case 502:
                    throw new Service_Exception_Bad_Gateway($err, $body, $code);
                    break;
                case 401:
                    throw new Service_Exception_Invalid_Credentials($err, $body, $code);
                    break;
                case 403:
                    throw new Service_Exception_Forbidden($err, $msg, $code);
                    break;
                case 500:
                    throw new Service_Exception_Server_Error($err, $msg, $code);
                    break;
                default:
                    throw new Service_Exception($err, $msg, $code);
                    break;

            }

        }

        if ($response->isDownload()) {
            return null;
        }

        $decoded = json_decode($body);
        // If dotSUB returns an error in JSON format
        if (isset($decoded->status->error) && $decoded->status->error == "true") {
            $err = 'The ' . $response->getRequestMethod() . ' request to "' . $response->getUrl() . '" failed.';
            throw new Service_Exception($err, $decoded->status->message, $decoded->status->code);
        }
        if ($decoded === null || $decoded === "") {
            throw new Service_Exception("The JSON formatting of the response is invalid.", $body);
        }

        if ($format) {
            return $decoded;
        }

        return $body;

    }

}