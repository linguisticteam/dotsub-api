<?php
require_once realpath(dirname(__FILE__) . '/../../../autoload.php');

class DotSUB_Service_Exception extends DotSUB_Exception {
	
}

class DotSUB_Service_Exception_Authentication extends DotSUB_Service_Exception{
	public function __construct(){
		$this->message="You must supply your credentials, as only authenticated clients can perform this task.";
	}
}

class DotSUB_Service_Exception_Video extends DotSUB_Service_Exception{
	public function __construct($field){
		$this->message="The $field of the video must be specified for upload.";
	}
}

class DotSUB_Service_Exception_Project extends DotSUB_Service_Exception
{
	public function __construct()
	{
		$this->message = "Your DotSUB account project ID was not specified.";
	}
}