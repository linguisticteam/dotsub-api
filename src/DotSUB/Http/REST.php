<?php
namespace Lti\DotsubAPI\Http;

use Lti\DotsubAPI\DotSUB_Client;
use Lti\DotsubAPI\Service\DotSUB_Service_Exception;
use Lti\DotsubAPI\Service\DotSUB_Service_Exception_Bad_Gateway;
use Lti\DotsubAPI\Service\DotSUB_Service_Exception_Forbidden;
use Lti\DotsubAPI\Service\DotSUB_Service_Exception_Invalid_Credentials;

/**
 * Trying to make the request as RESTful as possible,
 * handles the actual execution of the request.
 *
 *
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Http_REST
{

    /**
     * Uses the IO method configured to execute the request.
     *
     * @param DotSUB_Client $client
     * @param DotSUB_Http_Request $req
     * @param boolean $format
     * @return \stdClass The JSON response.
     */
    public static function execute(DotSUB_Client $client, DotSUB_Http_Request $req, $format = true)
    {

        $httpRequest = $client->getIo()->makeRequest($req);
        return self::decodeHttpResponse($httpRequest, $format);

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
    public static function decodeHttpResponse(DotSUB_Http_Request $response, $format)
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
                    throw new DotSUB_Service_Exception_Bad_Gateway($err, $body, $code);
                    break;
                case 401:
                    throw new DotSUB_Service_Exception_Invalid_Credentials($err, $body, $code);
                    break;
                case 403:
                    throw new DotSUB_Service_Exception_Forbidden($err, $msg, $code);
                    break;
                default:
                    throw new DotSUB_Service_Exception($err, $msg, $code);
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
            throw new DotSUB_Service_Exception($err, $decoded->status->message, $decoded->status->code);
        }
        if ($decoded === null || $decoded === "") {
            throw new DotSUB_Service_Exception("The JSON formatting of the response is invalid.", $body);
        }

        if ($format) {
            return $decoded;
        }

        return $body;

    }

}