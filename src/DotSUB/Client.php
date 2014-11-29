<?php
require_once realpath(dirname(__FILE__) . '/../../autoload.php');

/**
 * Handles client configuration, which is handed to the request as needed.
 *
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Client {
	private $config;
	private $io;
	private $hasCredentials = false;

	public function __construct($config = ""){
		if(!function_exists('curl_exec')) {
			trigger_error("This code needs CURL to handle HTTP Requests.", E_ERROR);
		}
		$this->config = new DotSUB_Config($config);
	}

	public function getClientCredentials(){
		return $this->config->getClientCredentials();
	}

	public function getClientUsername(){
		return $this->config->getClientUsername();
	}

	public function getIo(){
		if(!isset($this->io)) {
			$class = $this->config->getIoClass();
			$this->io = new $class($this);
		}
		return $this->io;
	}

	public function setClientCredentials($username, $password){
		$this->config->setClientCredentials($username, $password);
		$this->hasCredentials = true;
	}

	public function getClientProject(){
		return $this->config->getClientProject();
	}
	
	public function setClientProject($project){
		$this->config->setClientProject($project);
	}

	public function execute($request){
		return DotSUB_Http_REST::execute($this, $request);
	}

	public function hasCredentials(){
		$u = $this->config->getClientCredentials();
		return (!empty($u[0]) && !empty($u[1]));
	}
}