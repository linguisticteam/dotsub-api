<?php
namespace Lti\DotsubAPI\IO;

use Lti\DotsubAPI\Http\DotSUB_Http_Request;
use Lti\DotsubAPI\IO\DotSUB_IO_ProgressMonitorInterface;

/**
 * Handling an HTTP request with Curl.
 *
 * @author Bruno@Linguistic Team International
 */
class DotSUB_IO_Curl
{
    /**
     *
     * @link http://www.php.net/manual/en/function.curl-setopt.php
     * @var array options Contains the Curl options to be set with curl_setopt
     */
    private $options = array();

    /**
     *
     * Constructor for the Curl class, takes in an implementation of the method that will allow us to track
     * downloaded/uploaded data with CURLOPT_PROGRESSFUNCTION
     * @param DotSUB_IO_ProgressMonitorInterface $progressMonitor
     */
    public function __construct(DotSUB_IO_ProgressMonitorInterface $progressMonitor = null)
    {
        $this->progressMonitor = $progressMonitor;
    }

    /**
     * Fills the HTTP request with the responses it got from executing the
     * request.
     *
     * @param DotSUB_Http_Request $request
     * @return DotSUB_Http_Request
     */
    public function makeRequest(DotSUB_Http_Request $request)
    {

        list($responseData, $responseHeaders, $respHttpCode) = $this->executeRequest($request);

        if (!isset($responseHeaders['Date']) && !isset($responseHeaders['date'])) {
            $responseHeaders['Date'] = date("r");
        }

        $request->setResponseHttpCode($respHttpCode);
        $request->setResponseHeaders($responseHeaders);
        $request->setResponseBody($responseData);

        return $request;

    }

    /**
     *
     * @param DotSUB_Http_Request $request
     * @throws DotSUB_IO_Exception
     * @return array
     */
    public function executeRequest(DotSUB_Http_Request $request)
    {
        $curl = curl_init();

        if ($request->getPostBody()) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getPostBody());
        }

        $requestHeaders = $request->getRequestHeaders();
        // Handling of extra headers to be set
        if ($requestHeaders && is_array($requestHeaders)) {
            $curlHeaders = array();
            foreach ($requestHeaders as $k => $v) {
                $curlHeaders[] = "$k: $v";
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $curlHeaders);
        }

        curl_setopt($curl, CURLOPT_URL, $request->getUrl());
        // GET, POST, PUT...
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getRequestMethod());
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // For testing purposes
        // curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HEADER, true);

        if ($this->progressMonitor) {
            //This parameter has to be activated so we can get our progress data
            curl_setopt($curl, CURLOPT_NOPROGRESS, false);

            // Set up the callback function that'll get the data
            curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, [$this->progressMonitor, 'handleProgress']);

            //@TODO : see the effect of the buffer on the amount of CURL output
            curl_setopt($curl, CURLOPT_BUFFERSIZE, 128);
        }

        foreach ($this->options as $key => $var) {
            curl_setopt($curl, $key, $var);
        }

        if ($request->isDownload()) {
            $fh = fopen($request->getFileName(), 'w');
            curl_setopt($curl, CURLOPT_FILE, $fh);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            $response = curl_exec($curl);
            fclose($fh);
        } else {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            $response = curl_exec($curl);
        }

        if ($response === false) {
            throw new DotSUB_IO_Exception("The request failed, and returned error code " . curl_errno($curl));
        }

        // For testing purposes
        // print_r(curl_getinfo($curl));

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        list($responseHeaders, $responseBody) = $this->parseHttpResponse($response, $headerSize);

        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        return array(
            $responseBody,
            $responseHeaders,
            $responseCode
        );

    }

    public function parseHttpResponse($respData, $headerSize)
    {

        if ($headerSize) {
            $responseBody = substr($respData, $headerSize);
            $responseHeaders = substr($respData, 0, $headerSize);
        } else {
            $responseSegments = explode("\r\n\r\n", $respData, 2);
            $responseHeaders = $responseSegments[0];
            $responseBody = isset($responseSegments[1]) ? $responseSegments[1] : null;
        }

        $responseHeaders = $this->getHttpResponseHeaders($responseHeaders);
        return array(
            $responseHeaders,
            $responseBody
        );

    }

    public function getHttpResponseHeaders($rawHeaders)
    {

        if (is_array($rawHeaders)) {
            return $this->parseArrayHeaders($rawHeaders);
        } else {
            return $this->parseStringHeaders($rawHeaders);
        }

    }

    private function parseStringHeaders($rawHeaders)
    {

        $headers = array();
        $responseHeaderLines = explode("\r\n", $rawHeaders);
        foreach ($responseHeaderLines as $headerLine) {
            if ($headerLine && strpos($headerLine, ':') !== false) {
                list($header, $value) = explode(': ', $headerLine, 2);
                $header = strtolower($header);
                if (isset($headers[$header])) {
                    $headers[$header] .= "\n" . $value;
                } else {
                    $headers[$header] = $value;
                }
            }
        }
        return $headers;

    }

    private function parseArrayHeaders($rawHeaders)
    {

        $header_count = count($rawHeaders);
        $headers = array();

        for ($i = 0; $i < $header_count; $i++) {
            $header = $rawHeaders[$i];
            // Times will have colons in - so we just want the first match.
            $header_parts = explode(': ', $header, 2);
            if (count($header_parts) == 2) {
                $headers[$header_parts[0]] = $header_parts[1];
            }
        }

        return $headers;

    }

    public function setOptions($options)
    {

        $this->options = $options + $this->options;

    }

    public function setCredentials($credentials)
    {

        $this->options[CURLOPT_USERPWD] = $credentials[0] . ":" . $credentials[1];

    }

}

