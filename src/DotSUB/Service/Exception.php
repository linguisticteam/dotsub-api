<?php namespace Lti\DotsubAPI\Service;


use Lti\DotsubAPI\Exception;

class Service_Exception extends Exception
{
}

class Service_Exception_Authentication extends Service_Exception
{
    public function __construct()
    {
        $this->message = "You must supply your credentials, as only authenticated clients can perform this task.";
    }
}

class Service_Exception_Invalid_Credentials extends Service_Exception
{
    public function __construct($message, $dSMessage, $code)
    {
        parent::__construct($message, $dSMessage, $code);
        $this->message = "Credentials are invalid.";
    }
}

class Service_Exception_Forbidden extends Service_Exception
{
    public function __construct($message, $dSMessage, $code)
    {
        parent::__construct($message, $dSMessage, $code);
        $this->message = "Forbidden. Trying to access a private resource?";
    }
}

class Service_Exception_Bad_Gateway extends Service_Exception
{
    public function __construct($message, $dSMessage, $code)
    {
        parent::__construct($message, $dSMessage, $code);
        $this->message = "Bad Gateway.";
    }
}

class Service_Exception_Server_Error extends Service_Exception
{
    public function __construct($message, $dSMessage, $code)
    {
        parent::__construct($message, $dSMessage, $code);
        $this->message = "Server errror.";
    }
}

class Service_Exception_Video extends Service_Exception
{
    public function __construct($field)
    {
        $this->message = "The $field of the video must be specified for upload.";
    }
}

class Service_Exception_Project extends Service_Exception
{
    public function __construct()
    {
        $this->message = "Your DotSUB account project ID was not specified.";
    }
}