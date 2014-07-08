<?php
/**
 * Copyright 2006 - 2014 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 */
class tubepress_app_media_provider_impl_HttpMediaProvider implements tubepress_app_media_provider_api_MediaProviderInterface
{
    /**
     * @var tubepress_platform_api_log_LoggerInterface
     */
    private $_logger;

    /**
     * @var tubepress_lib_event_api_EventDispatcherInterface
     */
    private $_eventDispatcher;

    /**
     * @var tubepress_app_media_provider_api_HttpProviderInterface
     */
    private $_delegate;

    /**
     * @var tubepress_lib_http_api_HttpClientInterface
     */
    private $_httpClient;

    public function __construct(tubepress_app_media_provider_api_HttpProviderInterface $delegate,
                                tubepress_platform_api_log_LoggerInterface                 $logger,
                                tubepress_lib_event_api_EventDispatcherInterface $eventDispatcher,
                                tubepress_lib_http_api_HttpClientInterface       $httpClient)
    {
        $this->_logger          = $logger;
        $this->_eventDispatcher = $eventDispatcher;
        $this->_delegate        = $delegate;
        $this->_httpClient      = $httpClient;
    }

    /**
     * Fetch a media page.
     *
     * @param int $currentPage The requested page number of the gallery.
     *
     * @return tubepress_app_media_provider_api_Page The media gallery page for this page. May be empty, never null.
     *
     * @api
     * @since 4.0.0
     */
    public function fetchPage($currentPage)
    {
        $toReturn     = new tubepress_app_media_provider_api_Page();
        $debugEnabled = $this->_logger->isEnabled();

        $url = $this->_delegate->buildUrlForPage($currentPage);
        $url = $this->_dispatchUrl($url, tubepress_app_media_provider_api_Constants::EVENT_URL_MEDIA_PAGE, array(
            'pageNumber' => $currentPage
        ));

        if ($debugEnabled) {

            $this->_logger->debug(sprintf('URL to fetch is <code>%s</code>', $url));
        }

        $rawFeed                  = $this->_fetchFeedAndPrepareForAnalysis($url);
        $reportedTotalResultCount = $this->_delegate->getTotalResultCount($rawFeed);

        /**
         * If no results, we can shortcut things here.
         */
        if ($reportedTotalResultCount < 1) {

            $this->_delegate->onAnalysisComplete($rawFeed);
            return $this->_emptyPage($toReturn);
        }

        if ($debugEnabled) {

            $this->_logger->debug(sprintf('Reported total result count is %d video(s)', $reportedTotalResultCount));
        }

        /* convert the feed to videos */
        $mediaItemArray = $this->_feedToMediaItemArray($rawFeed);

        if (count($mediaItemArray) == 0) {

            return $this->_emptyPage($toReturn);
        }

        $toReturn->setTotalResultCount($reportedTotalResultCount);
        $toReturn->setItems($mediaItemArray);

        return $toReturn;
    }

    /**
     * Fetch a single media item.
     *
     * @param string $itemId The item ID to fetch.
     *
     * @return tubepress_app_media_item_api_MediaItem The media item, or null if unable to retrive.
     *
     * @api
     * @since 4.0.0
     */
    public function fetchSingle($itemId)
    {
        $isLoggerDebugEnabled = $this->_logger->isEnabled();

        if ($isLoggerDebugEnabled) {

            $this->_logger->debug(sprintf('Fetching media item with ID <code>%s</code>', $itemId));
        }

        $mediaItemUrl = $this->_delegate->buildUrlForSingle($itemId);
        $mediaItemUrl = $this->_dispatchUrl($mediaItemUrl, tubepress_app_media_provider_api_Constants::EVENT_URL_MEDIA_ITEM, array(
            'itemId' => $itemId
        ));

        if ($isLoggerDebugEnabled) {

            $this->_logger->debug(sprintf('URL to fetch is <a href="%s">this</a>', $mediaItemUrl));
        }

        $feed           = $this->_fetchFeedAndPrepareForAnalysis($mediaItemUrl);
        $mediaItemArray = $this->_feedToMediaItemArray($feed);
        $toReturn       = null;

        if (count($mediaItemArray) > 0) {

            return $mediaItemArray[0];
        }

        return null;
    }

    /**
     * @param tubepress_app_media_item_api_MediaItem $first
     * @param tubepress_app_media_item_api_MediaItem $second
     * @param string                                $perPageSort
     *
     * @return int
     */
    public function compareForPerPageSort(tubepress_app_media_item_api_MediaItem $first, tubepress_app_media_item_api_MediaItem $second, $perPageSort)
    {
        return $this->_delegate->compareForPerPageSort($first, $second, $perPageSort);
    }

    /**
     * Ask this media provider if it recognizes the given item ID.
     *
     * @param string $mediaId The globally unique media item identifier.
     *
     * @return boolean True if this provider recognizes the given item ID, false otherwise.
     *
     * @api
     * @since 4.0.0
     */
    public function recognizesItemId($mediaId)
    {
        return $this->_delegate->recognizesItemId($mediaId);
    }

    /**
     * @return string The name of the "mode" value that this provider uses for searching.
     *
     * @api
     * @since 4.0.0
     */
    public function getSearchModeName()
    {
        return $this->_delegate->getSearchModeName();
    }

    /**
     * @return array An array of the valid option values for the "mode" option.
     */
    public function getGallerySourceNames()
    {
        return $this->_delegate->getGallerySourceNames();
    }

    /**
     * @return string The name of this video provider. Never empty or null. All lowercase alphanumerics and dashes.
     */
    public function getName()
    {
        return $this->_delegate->getName();
    }

    /**
     * @return array An array of meta names
     *
     * @api
     * @since 4.0.0
     */
    public function getMapOfMetaOptionNamesToAttributeDisplayNames()
    {
        return $this->_delegate->getMapOfMetaOptionNamesToAttributeDisplayNames();
    }

    /**
     * @return string The option name where TubePress should put the users search results.
     *
     * @api
     * @since 4.0.0
     */
    public function getSearchQueryOptionName()
    {
        return $this->_delegate->getSearchQueryOptionName();
    }

    /**
     * @return array
     *
     * @api
     * @since 4.0.0
     */
    public function getMapOfFeedSortNamesToUntranslatedLabels()
    {
        return $this->_delegate->getMapOfFeedSortNamesToUntranslatedLabels();
    }

    /**
     * @return string The human-readable name of this media provider.
     *
     * @api
     * @since 4.0.0
     */
    public function getDisplayName()
    {
        return $this->_delegate->getDisplayName();
    }

    /**
     * @return array
     *
     * @api
     * @since 4.0.0
     */
    public function getMapOfPerPageSortNamesToUntranslatedLabels()
    {
        return $this->_delegate->getMapOfPerPageSortNamesToUntranslatedLabels();
    }

    private function _feedToMediaItemArray($feed)
    {
        $toReturn       = array();
        $total          = $this->_delegate->getCurrentResultCount($feed);
        $isDebugEnabled = $this->_logger->isEnabled();

        if ($isDebugEnabled) {

            $this->_logger->debug(sprintf('Now attempting to build %d item(s) from raw feed', $total));
        }

        for ($index = 0; $index < $total; $index++) {

            if (! $this->_delegate->canWorkWithItemAtIndex($index, $feed)) {

                if ($isDebugEnabled) {

                    $this->_logger->debug(sprintf('Skipping item at index %d: %s', $index,
                        $this->_delegate->getReasonUnableToWorkWithItemAtIndex($index, $feed)));
                }

                continue;
            }

            $mediaItem = $this->_buildMediaItem($feed, $index);

            array_push($toReturn, $mediaItem);
        }

        $this->_delegate->onAnalysisComplete($feed);

        if ($isDebugEnabled) {

            $this->_logger->debug(sprintf('Built %d items(s) from raw feed', sizeof($toReturn)));
        }

        return $toReturn;
    }

    private function _fetchFeedAndPrepareForAnalysis($url)
    {
        $debugStream = null;
        $requestOpts = array();

        if ($this->_logger->isEnabled()) {

            $debugStream          = fopen('php://memory','r+');
            $requestOpts['debug'] = $debugStream;
        }

        try {

            $httpRequest = $this->_httpClient->createRequest('GET', $url, $requestOpts);

            /**
             * Allows the cache to recognize this as an API call.
             */
            $httpRequest->setConfig(array_merge($httpRequest->getConfig(), array('tubepress-remote-api-call' => true)));

            $httpResponse = $this->_httpClient->send($httpRequest);

        } catch (tubepress_lib_http_api_exception_RequestException $e) {

            $this->_flushDebugStream($debugStream, true);

            throw new tubepress_app_media_provider_api_exception_ProviderException($e->getMessage());
        }

        $this->_flushDebugStream($debugStream, false);

        $rawFeed = $httpResponse->getBody()->toString();

        if ($this->_logger->isEnabled()) {

            $this->_logger->debug(sprintf('Raw result for <a href="%s">URL</a> is in the HTML source for this page. <span style="display:none">%s</span>',
                $url, htmlspecialchars($rawFeed)));
        }

        $this->_delegate->onAnalysisStart($rawFeed);

        return $rawFeed;
    }

    private function _flushDebugStream($stream, $error)
    {
        if (!$stream) {

            return;
        }

        rewind($stream);

        $contents = stream_get_contents($stream);
        $contents = explode("\n", $contents);

        foreach ($contents as $line) {

            if ($error) {

                $this->_logger->error($line);
                continue;
            }

            $this->_logger->debug($line);
        }
    }

    private function _emptyPage(tubepress_app_media_provider_api_Page $page)
    {
        $page->setTotalResultCount(0);
        $page->setItems(array());

        return $page;
    }

    /**
     * @param $feed
     * @param $index
     *
     * @return mixed|tubepress_app_media_item_api_MediaItem
     */
    private function _buildMediaItem($feed, $index)
    {
        $id = $this->_delegate->getIdForItemAtIndex($index, $feed);

        $mediaItem = new tubepress_app_media_item_api_MediaItem($id);

        /*
         * Every video needs to have a provider.
         */
        $mediaItem->setAttribute(tubepress_app_media_item_api_Constants::ATTRIBUTE_PROVIDER, $this);

        /*
         * Let add-ons build the rest of the video.
         */
        $event = $this->_eventDispatcher->newEventInstance($mediaItem, array(
            'provider'           => $this,
            'zeroBasedFeedIndex' => $index,
            'rawFeed'            => $feed
        ));

        /*
         * Let subclasses add to the event.
         */
        $this->_delegate->onPreFireNewMediaItemEvent($event);

        return $this->_dispatchEventAndReturnSubject($event, tubepress_app_media_provider_api_Constants::EVENT_NEW_MEDIA_ITEM);
    }

    /**
     * @return tubepress_lib_url_api_UrlInterface
     */
    private function _dispatchUrl(tubepress_lib_url_api_UrlInterface $url, $eventName, array $additionalArgs = array())
    {
        $args = array_merge(array(
            'provider' => $this
        ), $additionalArgs);

        $event = $this->_eventDispatcher->newEventInstance($url, $args);

        return $this->_dispatchEventAndReturnSubject($event, $eventName);
    }

    private function _dispatchEventAndReturnSubject(tubepress_lib_event_api_EventInterface $event, $eventName)
    {
        $this->_eventDispatcher->dispatch($eventName, $event);

        return $event->getSubject();
    }
}