<?php
class DotSUB_Service_Exception extends DotSUB_Exception {
	
}

class DotSUB_Service_Exception_Authentication extends DotSUB_Service_Exception{
	public function __construct(){
		$this->message="You must give your credentials as parameters to DotSUB_Service_Media, this task requires authentication";
	}
}

class DotSUB_Service_Exception_Video extends DotSUB_Service_Exception{
	public function __construct($field){
		$this->message="The $field of the video must be specified for upload.";
	}
}