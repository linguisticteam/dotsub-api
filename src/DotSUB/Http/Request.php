<?php
namespace Lti\DotsubAPI\Http;

use Lti\DotsubAPI\Tools;

/**
 * Class Http_Request
 * @package Lti\DotsubAPI\Http
 *
 * The HTTP request that will be executed by the IO Class.
 * The request's header, body and params will be filled in when preparing a request.
 */
class Http_Request
{
    private $url;
    private $baseUrl;
    private $postBody;
    private $basePostBody;
    private $requestMethod;
    private $baseRequestMethod;
    private $requestHeaders;
    private $baseRequestHeaders;
    private $responseHttpCode;
    private $responseHeaders;
    private $responseBody;
    private $queryParams;
    private $fileName;
    private $isDownload = false;

    public function __construct($url, $method = 'GET', $headers = array(), $postBody = null)
    {
        $this->setBaseUrl($url);
        $this->setBaseRequestMethod($method);
        $this->setBaseRequestHeaders($headers);
        $this->setBasePostBody($postBody);
    }

    public function getUrl()
    {
        return $this->url . (count($this->queryParams) ? "?" . Tools::buildQuery($this->queryParams) : '');
    }

    public function getUrlToString()
    {
        return $this->url;
    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    public function getPostBody()
    {
        return $this->postBody;
    }

    public function getResponseHttpCode()
    {
        return $this->responseHttpCode;
    }

    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        $this->isDownload = true;
    }

    public function isDownload()
    {
        return $this->isDownload;
    }

    public function setRequestHeaders($headers)
    {
        if ($this->requestHeaders) {
            $headers = array_merge($this->requestHeaders, $headers);
        }
        $this->requestHeaders = $headers;
    }

    public function setQueryParam($key, $value)
    {
        if (!empty($key)) {
            $this->queryParams[$key] = $value;
        }
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
        $this->setUrl($url);
    }

    public function setBaseRequestMethod($method)
    {
        $this->baseRequestMethod = $method;
        $this->setRequestMethod($method);
    }

    public function setBasePostBody($postBody)
    {
        $this->basePostBody = $postBody;
        $this->setPostBody($postBody);
    }

    public function setBaseRequestHeaders($headers)
    {
        $this->baseRequestHeaders = $headers;
        $this->setRequestHeaders($headers);
    }

    public function setRequestMethod($method)
    {
        $this->requestMethod = $method;
    }

    public function setPostBody($postBody)
    {
        $this->postBody = $postBody;
    }

    public function setResponseHttpCode($responseHttpCode)
    {
        $this->responseHttpCode = $responseHttpCode;
    }

    public function setResponseHeaders($responseHeaders)
    {
        $this->responseHeaders = $responseHeaders;
    }

    public function setResponseBody($responseBody)
    {
        $this->responseBody = $responseBody;
    }

    public function appendToUrl($string)
    {
        $this->url .= $string;
    }

    /**
     *    Allowing the request to be reused by resetting its params to their state when the request was instantiated.
     */
    public function reset()
    {
        $this->url = $this->baseUrl;
        $this->requestHeaders = $this->baseRequestHeaders;
        $this->requestMethod = $this->baseRequestMethod;
        $this->postBody = $this->basePostBody;
    }

}