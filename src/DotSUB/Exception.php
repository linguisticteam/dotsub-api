<?php

class DotSUB_Exception extends Exception {
	protected $dSMessage;

	/**
	 * Overloaded constructor for the Exception class.
	 * We provide a general error message plus a decoded version of the JSON dotSUB error message.
	 * @param string $message general purpose message
	 * @param string $dSMessage specific dotSUB error message
	 * @param string $code http error code, if applicable
	 */
	public function __construct($message, $dSMessage = "", $code = "N/A"){

		$this->message = $message;
		$this->code = $code;
		$this->dSMessage = $dSMessage;
	
	}
	
	public function getDsMessage(){
		return $this->dSMessage;
	}

}