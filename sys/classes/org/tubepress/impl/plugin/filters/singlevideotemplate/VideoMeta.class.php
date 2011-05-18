<?php
/**
 * Copyright 2006 - 2011 Eric D. Hough (http://ehough.com)
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
 * Handles applying video meta info to the gallery template.
 */
class org_tubepress_impl_plugin_filters_singlevideotemplate_VideoMeta extends org_tubepress_impl_plugin_filters_gallerytemplate_VideoMeta
{
    public function alter_singleVideoTemplate(org_tubepress_api_template_Template $template, org_tubepress_api_video_Video $video)
    {
        return parent::alter_galleryTemplate($template, new org_tubepress_api_provider_ProviderResult(), $galleryId);
    }
}