<?php
namespace Lti\DotsubAPI\Http;

use Lti\DotsubAPI\DotSUB_Tools;

/**
 * The HTTP request that will be executed by the IO Class.
 * The request's header, body and params will be filled in
 * when preparing a request.
 *
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Http_Request {
	private $url;
	private $postBody;
	private $requestMethod;
	private $requestHeaders;
	private $responseHttpCode;
	private $responseHeaders;
	private $responseBody;
	private $queryParams;
	private $fileName;
	private $isDownload = false;

	public function __construct($url, $method = 'GET', $headers = array(), $postBody = null){

		$this->setUrl($url);
		$this->setRequestMethod($method);
		$this->setRequestHeaders($headers);
		$this->setPostBody($postBody);
	
	}

	public function getUrl(){

		return $this->url . (count($this->queryParams) ? "?" . DotSUB_Tools::buildQuery($this->queryParams) : '');
	
	}

	public function getUrlToString(){

		return $this->url;
	
	}

	public function getRequestMethod(){

		return $this->requestMethod;
	
	}

	public function getPostBody(){

		return $this->postBody;
	
	}

	public function getResponseHttpCode(){

		return $this->responseHttpCode;
	
	}

	public function getResponseHeaders(){

		return $this->responseHeaders;
	
	}

	public function getResponseBody(){

		return $this->responseBody;
	
	}

	public function getQueryParams(){

		return $this->queryParams;
	
	}

	public function getRequestHeaders(){

		return $this->requestHeaders;
	
	}

	public function getFileName(){

		return $this->fileName;
	
	}

	public function setFileName($fileName){

		$this->fileName = $fileName;
		$this->isDownload = true;
	
	}

	public function isDownload(){

		return $this->isDownload;
	
	}

	public function setRequestHeaders($headers){

		if($this->requestHeaders) {
			$headers = array_merge($this->requestHeaders, $headers);
		}
		$this->requestHeaders = $headers;
	
	}

	public function setQueryParam($key, $value){

		if(!empty($key)) {
			$this->queryParams[$key] = $value;
		}
	
	}

	public function setUrl($url){

		$this->url = $url;
	
	}

	public function setRequestMethod($method){

		$this->requestMethod = $method;
	
	}

	public function setPostBody($postBody){

		$this->postBody = $postBody;
	
	}

	public function setResponseHttpCode($responseHttpCode){

		$this->responseHttpCode = $responseHttpCode;
	
	}

	public function setResponseHeaders($responseHeaders){

		$this->responseHeaders = $responseHeaders;
	
	}

	public function setResponseBody($responseBody){

		$this->responseBody = $responseBody;
	
	}

	public function appendToUrl($string){

		$this->url .= $string;
	
	}

}