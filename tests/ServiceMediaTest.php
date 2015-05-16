<?php

/**
 * @covers \Lti\DotsubAPI\Service\Service_Media
 */
class ServiceMediaTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Lti\DotsubAPI\Client
     */
    private $client;
    /**
     * @var \Lti\DotsubAPI\Service\Service_Media
     */
    private $service;

    private $video;

    public function setUp(){
        $this->client = new \Lti\DotsubAPI\Client();

        $this->video = new stdClass();
        $this->video->title = "video title";
        $this->video->description = "video description";
        $this->video->language = \Lti\DotsubAPI\Config::DS_LANG_ISO_CODE;
        $this->video->license = \Lti\DotsubAPI\Config::DS_LICENSE;
        $this->video->project = "uuid of the project";
        $this->video->director = "director";
        $this->video->producer = "producer";
        $this->video->language = "language";
    }

    /**
     * @covers \Lti\DotsubAPI\Service\Service_Media::__construct
     * @throws \Lti\DotsubAPI\Service\Service_Exception_Authentication
     */
    public function testWithoutCredentials(){
        $this->client->setClientCredentials("", "");
        $this->setExpectedException('Lti\DotsubAPI\Service\Service_Exception_Authentication');
        $service = new \Lti\DotsubAPI\Service\Service_Media($this->client, false);
        $service->mediaUpload($this->video);
    }

    public function testMediaUploadWithoutReadableFile(){
        $clientUsername = "username";
        $clientPassword = "password";
        $clientProject = md5('test');
        $this->client->setClientCredentials($clientUsername, $clientPassword);
        $this->client->setClientProject($clientProject);
        $service = new \Lti\DotsubAPI\Service\Service_Media($this->client, false);
        $this->setExpectedException('Lti\DotsubAPI\Service\Service_Exception');
        $request = $service->mediaUpload($this->video);
    }

    /**
     * @covers \Lti\DotsubAPI\Service\Service_Media::mediaUpload
     * @covers \Lti\DotsubAPI\Http\Http_Request::getPostBody
     */
    public function testMediaUpload(){
        $clientUsername = "username";
        $clientPassword = "password";
        $clientProject = md5('test');
        $this->video->file = __DIR__.'/../README.md';
        $this->client->setClientCredentials($clientUsername, $clientPassword);
        $this->client->setClientProject($clientProject);
        $this->service = new \Lti\DotsubAPI\Service\Service_Media($this->client, false);
        $request = $this->service->mediaUpload($this->video);

        $file = $request->getPostBody();

        $this->assertTrue(is_array($file));
        $this->assertArrayHasKey('file',$file);
    }


    public function testdisplayMetadata(){
        $UUID = "fa960ff5-c6bf-4c84-a9a1-f6277c1a27be";

        $client = new \Lti\DotsubAPI\Client();
        $service = new \Lti\DotsubAPI\Service\Service_Media($client);
        $service->setUUID($UUID);
        $request = $service->mediaMetadata(true);
        $response = $client->execute($request);

        $this->assertEquals('200',\Lti\DotsubAPI\Http\Http_REST::$httpResponse->getResponseHttpCode());
    }

}