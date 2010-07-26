<?php
/**
 * Copyright 2006 - 2010 Eric D. Hough (http://ehough.com)
 * 
 * This file is part of TubePress (http://tubepress.org)
 * 
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * TubePress's feed retrieval mechanism
 *
 */
interface org_tubepress_video_feed_retrieval_FeedRetrievalService
{
    /**
     * Fetches the feed from the remote provider
     *
     * @param org_tubepress_ioc_IocService $ioc      The IOC container.
     * @param string                       $url      The URL to fetch.
     * @param boolean                      $useCache Whether or not to use the network cache.
     *
     * @return unknown The raw feed from the provider
     */
    public function fetch(org_tubepress_ioc_IocService $ioc, $url, $useCache);
}
