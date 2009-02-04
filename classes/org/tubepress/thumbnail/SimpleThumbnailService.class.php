<?php
/**
 * Copyright 2006, 2007, 2008, 2009 Eric D. Hough (http://ehough.com)
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
 * Handles the parsing of the meta info below each video thumbnail
 *
 */
class org_tubepress_thumbnail_SimpleThumbnailService implements org_tubepress_thumbnail_ThumbnailService
{
	private $_tpl;
	private $_tpom;
	private $_msg;
	private $_tppf;
	private $_tpeps;
	
    public function getHtml($template, org_tubepress_video_Video $vid, org_tubepress_player_Player $player)
    {
        $this->_tpl = new net_php_pear_HTML_Template_IT($template);
        if (!$this->_tpl->loadTemplatefile("thumbnail.tpl.html", true, true)) {
            throw new Exception("Couldn't load thumbnail template");
        }
        $this->_getCommonStuff($vid, $player);
        $this->_getMetaStuff($vid, $player);
        return $this->_tpl->get();
    }
    
    public function setOptionsManager(org_tubepress_options_manager_OptionsManager $tpom)
    {
    	$this->_tpom = $tpom;
    }
    
    private function _getCommonStuff(org_tubepress_video_Video $vid, org_tubepress_player_Player $player)
    {
        $randomizeOpt = $this->_tpom->
            get(org_tubepress_options_category_Advanced::RANDOM_THUMBS);
        $thumbWidth   = $this->_tpom->
            get(org_tubepress_options_category_Display::THUMB_WIDTH);
        $thumbHeight  = $this->_tpom->
            get(org_tubepress_options_category_Display::THUMB_HEIGHT);
        $height       = $this->_tpom->
            get(org_tubepress_options_category_Embedded::EMBEDDED_HEIGHT);
        $width        = $this->_tpom->
            get(org_tubepress_options_category_Embedded::EMBEDDED_WIDTH);
        
        $playLink = $player->getPlayLink($vid, $this->_tpom);

        $this->_tpl->setVariable('IMAGEPLAYLINK', $playLink);
        $this->_tpl->setVariable('IMAGETITLE', $vid->getTitle());

        if ($randomizeOpt) {
            $this->_tpl->setVariable('THUMBURL', $vid->getRandomThumbURL());
        } else {
             $this->_tpl->setVariable('THUMBURL', $vid->getDefaultThumbURL());
        }    
        
        $this->_tpl->setVariable('THUMBWIDTH', $thumbWidth);
        $this->_tpl->setVariable('THUMBHEIGHT', $thumbHeight);
    }
    
    private function _getMetaStuff(org_tubepress_video_Video $vid, org_tubepress_player_Player $player)
    {
        $class = new ReflectionClass("org_tubepress_options_category_Meta");    

        $link = $playLink = $player->getPlayLink($vid, $this->_tpom);
        
        /* go through each option in the category */
        foreach ($class->getConstants() as $constant) {

            if ($this->_tpom->get($constant) != 1 
                && $this->_tpom->get($constant) != $constant) {
                continue;
            }
            
            $nofollow = $this->_tpom->get(org_tubepress_options_category_Advanced::NOFOLLOW_LINKS);
            
            switch ($constant) {
                
            case org_tubepress_options_category_Meta::TITLE:
                $this->_tpl->setVariable('PLAYLINK', $link);
                $this->_tpl->setVariable('TITLE', $vid->getTitle());
                $this->_tpl->parse('title');
                break;
                    
            case org_tubepress_options_category_Meta::LENGTH:
                $this->_tpl->setVariable('RUNTIME', $vid->getLength());
                $this->_tpl->parse('runtime');
                break;
                    
            case org_tubepress_options_category_Meta::DESCRIPTION:
            	$limit = $this->_tpom->get(org_tubepress_options_category_Display::DESC_LIMIT);
            	$desc = $vid->getDescription();
            	if ($limit > 0 && strlen($desc) > $limit) {
            		$desc = substr($desc, 0, $limit) . "...";
            	}
                $this->_tpl->setVariable('DESCRIPTION', $desc);
                $this->_tpl->parse('description');
                break;
                
            case org_tubepress_options_category_Meta::AUTHOR:
                $this->_tpl->setVariable('METANAME', $this->_msg->_("video-" . $constant));
                $this->_tpl->setVariable('AUTHOR', $vid->getAuthor());
                if ($nofollow) { $this->_tpl->setVariable("NOFOLLOW", "rel=\"external nofollow\""); }
                $this->_tpl->parse('author');
                break;
                    
            case org_tubepress_options_category_Meta::TAGS:
                $tags = implode("%20", $vid->getTags());
                $this->_tpl->setVariable('METANAME', $this->_msg->_("video-" . $constant));
                $this->_tpl->setVariable('SEARCHSTRING', $tags);
                $this->_tpl->setVariable('TAGS', implode(" ", $vid->getTags()));
             	if ($nofollow) { $this->_tpl->setVariable("NOFOLLOW", "rel=\"external nofollow\""); }
                $this->_tpl->parse('tags');
                break;
                    
            case org_tubepress_options_category_Meta::URL:
                $this->_tpl->setVariable('LINKVALUE', $vid->getYouTubeUrl());
                $this->_tpl->setVariable('LINKTEXT', $this->_msg->_("video-" . $constant));
                $this->_tpl->parse('url');
                break;
                
            default:
                $this->_tpl->setVariable('METANAME', $this->_msg->_("video-" . $constant));
                   
                switch ($constant) {

                case org_tubepress_options_category_Meta::VIEWS:
                    $this->_tpl->setVariable('METAVALUE', $vid->getViews());
                    break;
                           
                case org_tubepress_options_category_Meta::ID:            
                    $this->_tpl->setVariable('METAVALUE', $vid->getId());
                    break;

                case org_tubepress_options_category_Meta::RATING:
                    $this->_tpl->setVariable('METAVALUE', $vid->getRating());
                    break;
                           
                case org_tubepress_options_category_Meta::RATINGS:
                    $this->_tpl->setVariable('METAVALUE', $vid->getRatings());
                    break;
                           
                case org_tubepress_options_category_Meta::UPLOADED:
                    $niceDate = $vid->getUploadTime();
                    if ($niceDate != "N/A") {
                    	if ($this->_tpom->get(org_tubepress_options_category_Display::RELATIVE_DATES)) {
                    		$niceDate = 
                    		    $this->_relativeTime($vid->getUploadTime());
                    	} else {
                            $niceDate = date($this->_tpom->
                                get(org_tubepress_options_category_Advanced::DATEFORMAT), 
                                $vid->getUploadTime());
                    	}
                    }
                    $this->_tpl->setVariable('METAVALUE', $niceDate);
                    break;
                        
                case org_tubepress_options_category_Meta::CATEGORY:
                    $this->_tpl->setVariable('METAVALUE', $vid->getCategory());
                }
                $this->_tpl->parse('meta');
            }
        }
    }
    
    public function setMessageService(org_tubepress_message_MessageService $messageService)
    { 
    	$this->_msg = $messageService; 
    }
    
    //Grabbed from http://www.weberdev.com/get_example-4769.html
    
    private function _relativeTime($timestamp){
        $difference = time() - $timestamp;
        $periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");
    
        if ($difference > 0) { // this was in the past
            $ending = "ago";
        } else { // this was in the future
            $difference = -$difference;
            $ending = "to go";
        }       
        for($j = 0; $difference >= $lengths[$j]; $j++) $difference /= $lengths[$j];
        $difference = round($difference);
        if($difference != 1) $periods[$j].= "s";
        $text = "$difference $periods[$j] $ending";
        return $text;
    }
}