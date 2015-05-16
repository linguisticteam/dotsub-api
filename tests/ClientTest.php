<?php

/**
 * @covers \Lti\DotsubAPI\Client
 */
class ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Lti\DotsubAPI\Client
     */
    private $client;


    public function setUp(){
        $this->client = new \Lti\DotsubAPI\Client();
    }
    /**
     * @covers \Lti\DotsubAPI\Client::__construct
     */
    public function testInit(){
        $this->assertTrue(is_array($this->client->getConfig()));
    }

    /**
     * @covers \Lti\DotsubAPI\Client::getClientCredentials
     * @covers \Lti\DotsubAPI\Client::setClientCredentials
     */
    public function testClientCredentials(){
        $credentials = $this->client->getClientCredentials();
        $this->assertTrue(count($credentials)==2);

        $this->client->setClientCredentials("username","password");
        $credentials = $this->client->getClientCredentials();
        $this->assertTrue($credentials[0] == "username");
        $this->assertTrue($credentials[1] == "password");

        $this->assertTrue($this->client->getClientUsername()=="username");
    }

    /**
     *
     * @covers \Lti\DotsubAPI\Client::getIo
     */
    public function testGetIO(){
        $IO = $this->client->getIo();
        $this->assertInstanceOf('Lti\DotsubAPI\IO\IO_Curl',$IO );
    }



}