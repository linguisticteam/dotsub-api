<?php namespace Lti\DotsubAPI\Service;


use Lti\DotsubAPI\DotSUB_Exception;

class DotSUB_Service_Exception extends DotSUB_Exception
{
}

class DotSUB_Service_Exception_Authentication extends DotSUB_Service_Exception
{
    public function __construct()
    {
        $this->message = "You must supply your credentials, as only authenticated clients can perform this task.";
    }
}

class DotSUB_Service_Exception_Invalid_Credentials extends DotSUB_Service_Exception
{
    public function __construct($message, $dSMessage, $code)
    {
        parent::__construct($message, $dSMessage, $code);
        $this->message = "Credentials are invalid.";
    }
}

class DotSUB_Service_Exception_Forbidden extends DotSUB_Service_Exception
{
    public function __construct($message, $dSMessage, $code)
    {
        parent::__construct($message, $dSMessage, $code);
        $this->message = "Forbidden. Trying to access a private resource?";
    }
}

class DotSUB_Service_Exception_Bad_Gateway extends DotSUB_Service_Exception
{
    public function __construct($message, $dSMessage, $code)
    {
        parent::__construct($message, $dSMessage, $code);
        $this->message = "Bad Gateway.";
    }
}

class DotSUB_Service_Exception_Server_Error extends DotSUB_Service_Exception
{
    public function __construct($message, $dSMessage, $code)
    {
        parent::__construct($message, $dSMessage, $code);
        $this->message = "Server errror.";
    }
}

class DotSUB_Service_Exception_Video extends DotSUB_Service_Exception
{
    public function __construct($field)
    {
        $this->message = "The $field of the video must be specified for upload.";
    }
}

class DotSUB_Service_Exception_Project extends DotSUB_Service_Exception
{
    public function __construct()
    {
        $this->message = "Your DotSUB account project ID was not specified.";
    }
}