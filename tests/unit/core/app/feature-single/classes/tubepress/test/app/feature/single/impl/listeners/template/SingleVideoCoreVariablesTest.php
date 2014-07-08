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
 * @covers tubepress_app_feature_single_impl_listeners_template_SingleVideoCoreVariables
 */
class tubepress_test_app_feature_single_impl_listeners_template_SingleVideoCoreVariablesTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_app_feature_single_impl_listeners_template_SingleVideoCoreVariables
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockExecutionContext;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockEmbeddedHtmlGenerator;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockOptionReference;

    public function onSetup()
    {
        $this->_mockExecutionContext      = $this->mock(tubepress_app_options_api_ContextInterface::_);
        $this->_mockEmbeddedHtmlGenerator = $this->mock(tubepress_app_embedded_api_EmbeddedHtmlInterface::_);
        $this->_mockOptionReference   = $this->mock(tubepress_app_options_api_ReferenceInterface::_);

        $this->_sut = new tubepress_app_feature_single_impl_listeners_template_SingleVideoCoreVariables(
            $this->_mockExecutionContext,
            $this->_mockEmbeddedHtmlGenerator,
            $this->_mockOptionReference
        );
    }

    public function testYouTubeFavorites()
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_app_embedded_api_Constants::OPTION_EMBEDDED_WIDTH)->andReturn(889);

        $video = new tubepress_app_media_item_api_MediaItem('video-id');

        $this->_mockEmbeddedHtmlGenerator->shouldReceive('getHtml')->once()->with('video-id')->andReturn('embedded-html');

        $mockTemplate = $this->mock('tubepress_lib_template_api_TemplateInterface');
        $mockTemplate->shouldReceive('setVariable')->once()->with(tubepress_app_embedded_api_Constants::TEMPLATE_VAR_SOURCE, 'embedded-html');
        $mockTemplate->shouldReceive('setVariable')->once()->with(tubepress_app_embedded_api_Constants::TEMPLATE_VAR_WIDTH, 889);
        $mockTemplate->shouldReceive('setVariable')->once()->with(tubepress_app_feature_single_api_Constants::TEMPLATE_VAR_MEDIA_ITEM, $video);

        $event = $this->mock('tubepress_lib_event_api_EventInterface');
        $event->shouldReceive('getArgument')->once()->with('item')->andReturn($video);
        $event->shouldReceive('getSubject')->once()->andReturn($mockTemplate);

        $this->_sut->onSingleVideoTemplate($event);

        $this->assertTrue(true);
    }
}
