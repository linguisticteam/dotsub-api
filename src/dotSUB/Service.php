<?php

class DotSUB_Service {

	public function __construct(DotSUB_Client $client){
		$this->client = $client;
	}

	public function getClient(){
		return $this->client;
	}
}