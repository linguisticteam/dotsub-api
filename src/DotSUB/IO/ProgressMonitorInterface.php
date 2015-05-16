<?php namespace Lti\DotsubAPI\IO;

interface IO_ProgressMonitorInterface
{

    public function handleProgress($download_size, $downloaded, $upload_size, $uploaded);
}
