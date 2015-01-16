<?php namespace Lti\DotsubAPI;

/**
 * Contains all the configuration elements which are handled by the client.
 * 
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Config {
	// default country code for uploaded videos
	const DS_COUNTRY_ISO_CODE = 'US';
	// default language code for uploaded videos
	const DS_LANG_ISO_CODE = 'eng';
	// License UUID for the default DotSUB license for new videos:
	// CC-Attribution Non-Commercial No Derivatives 3.0
	const DS_LICENSE = 'a2be14e1-37d9-11dd-ae16-0800200c9a66';
	private $config;
	private $progressMonitor;

	public function __construct(){
		$this->config = array('username' => '', 'password' => '', 'project' => '', 'io_class' => 'DotSUB_IO_Curl');
	}

	public function setClientCredentials($username, $password){
		$this->config['username'] = $username;
		$this->config['password'] = $password;
	}

	public function getClientCredentials(){
		return array($this->config['username'], $this->config['password']);
	}

	public function getClientUsername(){
		return $this->config['username'];
	}

	public function getClientProject(){
		return $this->config['project'];
	}

	public function getIoClass(){
		return $this->config['io_class'];
	}

	public function setClientProject($project){
		$this->config['project'] = $project;
	}

	public function setProgressMonitor($progressMonitor)
	{
		$this->progressMonitor = $progressMonitor;
	}

	public function getProgressMonitor()
	{
		return $this->progressMonitor;
	}

}