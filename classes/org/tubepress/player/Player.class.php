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
    || require dirname(__FILE__) . '/../../../tubepress_classloader.php';
tubepress_load_classes(array('org_tubepress_video_Video',
    'org_tubepress_ioc_IocService',
    'org_tubepress_theme_Theme'));

/**
 * A TubePress "player", such as lightWindow, GreyBox, popup window, etc
 */
class org_tubepress_player_Player
{
    const NORMAL    = 'normal';
    const POPUP     = 'popup';
    const SHADOWBOX = 'shadowbox';
    const JQMODAL   = 'jqmodal';
    const YOUTUBE   = 'youtube';
    const TINYBOX   = 'tinybox';
    const FANCYBOX  = 'fancybox';
    const STATICC   = 'static';
    const SOLO      = 'solo';
    const VIMEO     = 'vimeo';
    
    public function getHtml(org_tubepress_ioc_IocService $ioc, org_tubepress_video_Video $vid, $galleryId)
    {
        $tpom       = $ioc->get(org_tubepress_ioc_IocService::OPTIONS_MANAGER);
        $playerName = $tpom->get(org_tubepress_options_category_Display::CURRENT_PLAYER_NAME);
        $template   = org_tubepress_theme_Theme::getTemplateInstance($ioc, "players/$playerName.tpl.php");
        
        $template->setVariable(org_tubepress_template_Template::EMBEDDED_SOURCE, 
            org_tubepress_embedded_DelegatingEmbeddedPlayerService::toString($ioc, $vid->getId()));
            
        $template->setVariable(org_tubepress_template_Template::GALLERY_ID, $galleryId);
        $template->setVariable(org_tubepress_template_Template::VIDEO, $vid);
        $template->setVariable(org_tubepress_template_Template::EMBEDDED_WIDTH, $tpom->get(org_tubepress_options_category_Embedded::EMBEDDED_WIDTH));
        
        return $template->toString();
    }
}
