<?php
namespace Lti\DotsubAPI\Service;

use Lti\DotsubAPI\Service;
use Lti\DotsubAPI\Http\Http_Request;

/**
 *
 * Handling the Media part of the dotSUB API.
 * Authentication is required in most cases, except when retrieving metadata.
 *
 */
class Service_Media extends Service
{

    /**
     * @var string Holds all the information related to a video upload
     */
    protected $video;

    /**
     * Path: https://dotSUB.com/api/media
     *
     * Method: POST - Requires authentication
     *
     * You can upload media to DotSUB from your system using a HTTP Post to our
     * media upload API. This form does require you to be authenticated.
     *
     * @param \stdClass $videoData
     *            The video data to be sent in the upload
     */
    public function mediaUpload($videoData)
    {

        if (!$this->client->hasCredentials()) {
            throw new Service_Exception_Authentication();
        }
        $this->httpRequest->setRequestMethod("POST");
        $this->createVideoInfo($videoData, true);
        $this->addVideoProjectInfo($this->client->getClientProject());

        $this->httpRequest->setPostBody($this->getVideoInfo());

        return $this->requestWithAuthentication();

    }

    /**
     * Path: https://dotSUB.com/api/media/$UUID or
     * https://dotSUB.com/api/user/$username/media/$externalIdentifier
     *
     * Method: GET - No authentication required
     *
     * You can retrieve the metadata of a given media via this call.
     *
     * @param string $includeEmptyTranslations
     *            If true, also returns the data for translations with a 0%
     *            completion.
     * @return Http_Request
     */
    public function mediaMetadata($includeEmptyTranslations = false)
    {

        $this->httpRequest->appendToUrl("/" . $this->UUID);

        if ($includeEmptyTranslations) {
            $this->httpRequest->setQueryParam("includeEmptyTranslations", "true");
        }

        return $this->httpRequest;

    }

    /**
     * Metadata Editing
     *
     * Path: https://dotSUB.com/api/media/$UUID or
     * https://dotSUB.com/api/user/$username/media/$externalIdentifier
     *
     * Method: POST - Authentication required.
     *
     * @param \stdClass $videoData
     *            The video data to be sent in the upload
     * @return Http_Request
     */
    public function metaDataEditing($videoData)
    {

        $this->httpRequest->appendToUrl("/" . $this->UUID);
        $this->httpRequest->setRequestMethod("POST");
        $this->createVideoInfo($videoData);
        $this->httpRequest->setPostBody($this->getVideoInfo());
        return $this->requestWithAuthentication();

    }

    /**
     * Media deleting
     *
     * Path: https://dotSUB.com/api/media/$UUID or
     * https://dotSUB.com/api/user/$username/media/$externalIdentifier
     *
     * @return Http_Request
     */
    public function mediaDelete()
    {

        $this->httpRequest->appendToUrl("/" . $this->UUID);
        $this->httpRequest->setRequestMethod("POST");
        return $this->requestWithAuthentication();

    }

    /**
     * Add Media Permissions
     *
     * Path: https://dotSUB.com/api/media/$UUID/permissions
     *
     * Method: PUT - Requires authentication.
     *
     * The media permission API allows you to add permissions to videos on
     * DotSUB. Permissions can be added per user or as general settings on a
     * video.
     *
     * @param array $action
     *            The permission to be set/unset
     * @param string $isAdding
     *            Do we set or unset the permission?
     * @throws Service_Exception_Authentication
     * @return Http_Request
     */
    public function manageMediaPermissions($action, $isAdding = true)
    {

        if (!$this->client->hasCredentials()) {
            throw new Service_Exception_Authentication();
        }
        $this->httpRequest->appendToUrl("/" . $this->UUID . "/permissions");
        if ($isAdding) {
            $this->httpRequest->setRequestMethod("PUT");
        } else {
            $this->httpRequest->setRequestMethod("DELETE");
        }

        foreach ($action as $k => $v) {
            $this->httpRequest->setQueryParam($k, $v);
        }
        $this->httpRequest->setRequestHeaders(array("Content-Length" => 0));

        return $this->requestWithAuthentication();

    }

    /**
     * Media Query
     *
     * Path: https://dotsub.com/api/media
     *
     * Method: GET
     *
     * The Media Query API allows you to query public videos in Dotsub. This API
     * returns any videos that are public and publicly listed.
     *
     *
     * @param string $query The query you wish to perform
     * @param int $limit Number of results per page
     * @param int $start The first result to return
     * @return Http_Request
     */
    public function mediaQuery($query, $limit = 20, $start = 0)
    {

        $this->httpRequest->setQueryParam("q", $query);
        $this->httpRequest->setQueryParam("limit", $limit);
        $this->httpRequest->setQueryParam("start", $start);

        return $this->httpRequest;

    }

    /**
     * Transcription Upload
     *
     * Path: https://dotsub.com/api/media/$UUID/transcription or
     * https://dotsub.com/api/user/$username/media/$externalIdentifier/transcription
     *
     * Method: POST - Requires authentication
     *
     * You can upload an existing caption file as the video transcription. Most
     * standard subtitle formats are supported (ex: SRT, WebVTT, Timed Text).
     *
     * @param string $filename
     *            The file to be uploaded
     * @param string $language
     *            The file language's language code
     * @throws Service_Exception_Authentication
     * @return Http_Request
     */
    public function translationUpload($filename, $language)
    {
        if (!$this->client->hasCredentials()) {
            throw new Service_Exception_Authentication();
        }
        $this->httpRequest->appendToUrl("/" . $this->UUID . "/translation");
        $this->httpRequest->setRequestMethod("POST");
        $this->httpRequest->setPostBody(array("file" => "@" . $filename, "language" => $language));

        return $this->requestWithAuthentication();

    }

    /**
     *
     * Media Workflow
     *
     * Path: https://dotsub.com/api/media/$UUID/workflow or
     * https://dotsub.com/api/user/$username/media/$externalIdentifier/workflow
     *
     * Method: POST
     *
     * Changes can be made to the workflow state of translations and transcriptions via this API.
     *
     * Name                 Description                                                                                             Required
     * workflowStatus       The status you want to change to. ["ASSIGNED", "TRANSLATED", "TRANSCRIBED", "REVISED", "PUBLISHED"]     Yes
     * language             The language of the subtitles you wish to change state.                                                 Yes
     * useTransition        This defaults to 'false'. Set this to 'true' if you want Dotsub to use the workflow to change states.   No
     *
     * @param $workflowStatus
     * @param $language
     * @param boolean $useTransition
     * @throws Service_Exception_Authentication
     *
     */
    public function mediaWorkflow($workflowStatus, $language = 'eng', $useTransition = false)
    {
        switch ($workflowStatus) {
            case 'ASSIGNED':
            case 'TRANSLATED':
            case 'TRANSCRIBED':
            case 'REVISED':
            case 'PUBLISHED':
                break;
            default:
                throw new Service_Exception('Workflow status must have one of following values: ASSIGNED, TRANSLATED, TRANSCRIBED, REVISED or PUBLISHED');
        }
        if (!$this->client->hasCredentials()) {
            throw new Service_Exception_Authentication();
        }
        $this->httpRequest->appendToUrl("/" . $this->UUID . "/workflow");
        $this->httpRequest->setQueryParam('workflowStatus', $workflowStatus);
        $this->httpRequest->setQueryParam('language', $language);
        $this->httpRequest->setQueryParam('useTransition', ($useTransition) ? 'true' : 'false');
        $this->httpRequest->setRequestMethod("POST");
    }

    /**
     * Creating the object that will hold the video information (title,
     * description, filename...)
     *
     * @param \stdClass $videoInfo
     * @param boolean $isUpload
     *            Are we going to upload a video?
     */
    public function createVideoInfo($videoInfo, $isUpload = false)
    {

        $this->video = new Service_Video($videoInfo, $isUpload);

    }

    /**
     * Get the information out of the video object
     */
    public function getVideoInfo()
    {
        return $this->video->getVars();
    }

    /**
     * Setting the dotSUB project id (UUID)
     *
     * @param string $projectInfo
     */
    public function addVideoProjectInfo($projectInfo)
    {
        $this->video->setProject($projectInfo);
    }

}
