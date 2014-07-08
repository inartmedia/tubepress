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
class tubepress_test_jwplayer_resources_templates_embedded_JwPlayerEmbeddedVideoTemplateTest extends tubepress_test_TubePressUnitTest
{
    public function testTemplate()
    {
        $this->expectOutputString(<<<EOT
<div id="video-dom-id">
<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000'
        width='99'
        height='88'>
    <param name='movie' value='tp-base-url/src/core/integration/jwplayer/web/player.swf'>
    <param name='allowfullscreen' value='true'>
    <param name='allowscriptaccess' value='always'>
    <param name='wmode' value='transparent'>
    <param name='flashvars' value='file=data-url&amp;autostart=starttt&amp;backcolor=back-color&amp;frontcolor=front-color&amp;lightcolor=light-color&amp;screencolor=screen-color'>

<embed	type='application/x-shockwave-flash'
        src='tp-base-url/src/core/integration/jwplayer/web/player.swf'
		width='99'
		height='88'
		bgcolor='undefined'
        allowscriptaccess='always'
        allowfullscreen='true'
        wmode='transparent'
        flashvars='file=data-url&amp;autostart=starttt&amp;backcolor=back-color&amp;frontcolor=front-color&amp;lightcolor=light-color&amp;screencolor=screen-color'
</embed>
</object>
</div>
EOT
);

        ${tubepress_app_embedded_api_Constants::TEMPLATE_VAR_TUBEPRESS_BASE_URL} = 'tp-base-url';
        ${tubepress_app_embedded_api_Constants::TEMPLATE_VAR_MEDIA_ITEM_ID} = 'video-id';
        ${tubepress_app_embedded_api_Constants::TEMPLATE_VAR_WIDTH} = 99;
        ${tubepress_app_embedded_api_Constants::TEMPLATE_VAR_HEIGHT} = 88;
        ${tubepress_app_embedded_api_Constants::TEMPLATE_VAR_DATA_URL} = 'data-url';
        ${tubepress_app_embedded_api_Constants::TEMPLATE_VAR_AUTOSTART} = 'starttt';
        ${tubepress_jwplayer_api_Constants::OPTION_COLOR_BACK} = 'back-color';
        ${tubepress_jwplayer_api_Constants::OPTION_COLOR_FRONT} = 'front-color';
        ${tubepress_jwplayer_api_Constants::OPTION_COLOR_LIGHT} = 'light-color';
        ${tubepress_jwplayer_api_Constants::OPTION_COLOR_SCREEN} = 'screen-color';
        ${tubepress_app_embedded_api_Constants::TEMPLATE_VAR_MEDIA_ITEM_DOM_ID} = 'video-dom-id';

        require TUBEPRESS_ROOT . '/src/core/integration/jwplayer/resources/templates/embedded/longtail.tpl.php';
    }
}