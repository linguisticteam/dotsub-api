<?php

class DotSUB_Exception extends Exception {

	public function __construct($message, $code = "N/A", $previous = null){
		echo $message;
	}
}