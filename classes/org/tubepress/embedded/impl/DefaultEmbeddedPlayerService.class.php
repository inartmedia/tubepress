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

function_exists('tubepress_load_classes')
    || require(dirname(__FILE__) . '/../../../../tubepress_classloader.php');
tubepress_load_classes(array(
    'org_tubepress_embedded_impl_AbstractEmbeddedPlayerService'));

/**
 * An HTML-embeddable player
 *
 */
class org_tubepress_embedded_impl_DefaultEmbeddedPlayerService extends org_tubepress_embedded_impl_AbstractEmbeddedPlayerService
{
    private $_tpom;
    private $_youtubeService;
    private $_vimeoService;
    
    /**
     * Spits back the text for this embedded player
     *
     * @param $videoId The video ID to display
     *
     * @return string The text for this embedded player
     */
    public function toString($videoId)
    {   
        $provider = $this->_tpom->calculateCurrentVideoProvider();
        if ($provider === org_tubepress_video_feed_provider_Provider::VIMEO) {
            return $this->_vimeoService->toString($videoId);
        }
        return $this->_youtubeService->toString($videoId);
    }
    
    public function setOptionsManager(org_tubepress_options_manager_OptionsManager $tpom) { $this->_tpom = $tpom; }
    public function setYouTubeEmbeddedPlayerService(org_tubepress_embedded_EmbeddedPlayerService $s) { $this->_youtubeService = $s; }
    public function setVimeoEmbeddedPlayerService(org_tubepress_embedded_EmbeddedPlayerService $s) { $this->_vimeoService = $s; }
}
