<?php
class DotSUB_Service_Exception extends DotSUB_Exception {
	
}

class DotSUB_Service_Exception_Authentication extends DotSUB_Service_Exception{
	public function __construct(){
		echo "<h2>You must give your credentials as parameters to DotSUB_Service_Media, this task requires authentication</h2>";
	}
}

class DotSUB_Service_Exception_Video extends DotSUB_Service_Exception{
	public function __construct($field){
		echo "<h2>The $field of the video must be specified for upload.</h2>";
	}
}