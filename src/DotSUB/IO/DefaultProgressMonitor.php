<?php namespace Lti\DotsubAPI\IO;

/**
 * Class IO_DefaultProgressMonitor
 *
 * Default implementation of the method that curl uses to output download/upload progress
 *
 * Curl expects an implementation of the IO_ProgressMonitorInterface that can be passed
 * through a client via the setProgressMonitor method.
 *
 *
 * @package Lti\DotsubAPI\IO
 */
class IO_DefaultProgressMonitor implements IO_ProgressMonitorInterface
{

    public function handleProgress($download_size, $downloaded, $upload_size, $uploaded)
    {
        echo $download_size . " " . $downloaded . " " . $upload_size . " " . $uploaded . "<br/>";
    }

}
