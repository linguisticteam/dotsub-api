<?php

/**
 * @covers \Lti\DotsubAPI\Auth\Auth_Simple
 */
class AuthTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Lti\DotsubAPI\Client
     */
    private $client;


    public function setUp(){
        $this->client = new \Lti\DotsubAPI\Client();
    }

    /**
     * @covers \Lti\DotsubAPI\Auth_Simple::__construct
     * @covers \Lti\DotsubAPI\Auth_Simple::sendCredentials
     */
    public function testSendCredentials(){
        $auth = new Lti\DotsubAPI\Auth\Auth_Simple($this->client);
        $request = new \Lti\DotsubAPI\Http\Http_Request('https://dotSUB.com/api/media');

        $this->assertInstanceOf('Lti\DotsubAPI\Http\Http_Request',$auth->sendCredentials($request));
    }



}