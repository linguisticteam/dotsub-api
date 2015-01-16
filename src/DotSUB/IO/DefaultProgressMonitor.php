<?php namespace Lti\DotsubAPI\IO;

/**
 * Class DotSUB_IO_DefaultProgressMonitor
 *
 * Default implementation of the method that curl uses to output download/upload progress
 *
 * Curl expects an implementation of the DotSUB_IO_ProgressMonitorInterface that can be passed
 * through a client via the setProgressMonitor method.
 *
 *
 * @package Lti\DotsubAPI\IO
 */
class DotSUB_IO_DefaultProgressMonitor implements DotSUB_IO_ProgressMonitorInterface
{

    public function handleProgress($download_size, $downloaded, $upload_size, $uploaded)
    {
        echo $download_size . " " . $downloaded . " " . $upload_size . " " . $uploaded . "<br/>";
    }

}
