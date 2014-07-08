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
 * @covers tubepress_app_player_impl_listeners_html_SoloPlayerListener
 */
class tubepress_test_app_impl_shortcode_SoloPlayerCommandTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_app_player_impl_listeners_html_SoloPlayerListener
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockExecutionContext;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockHttpRequestParameterService;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockLogger;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockEvent;

    public function onSetup()
    {

        $this->_mockExecutionContext            = $this->mock(tubepress_app_options_api_ContextInterface::_);
        $this->_mockHttpRequestParameterService = $this->mock(tubepress_app_http_api_RequestParametersInterface::_);
        $this->_mockLogger                       = $this->mock(tubepress_platform_api_log_LoggerInterface::_);
        $this->_mockEvent            = $this->mock('tubepress_lib_event_api_EventInterface');

        $this->_mockLogger->shouldReceive('isEnabled')->atLeast(1)->andReturn(true);
        $this->_mockLogger->shouldReceive('debug')->atLeast(1);

        $this->_sut = new tubepress_app_player_impl_listeners_html_SoloPlayerListener(

            $this->_mockLogger,
            $this->_mockExecutionContext,
            $this->_mockHttpRequestParameterService
        );
    }

    public function testExecuteWrongPlayer()
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_app_player_api_Constants::OPTION_PLAYER_LOCATION)->andReturn('shadowbox');

        $this->_sut->onHtmlGeneration($this->_mockEvent);
        $this->assertTrue(true);
    }

    public function testExecuteNoVideoId()
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_app_player_api_Constants::OPTION_PLAYER_LOCATION)->andReturn('solo');

        $this->_mockHttpRequestParameterService->shouldReceive('getParamValue')->once()->with(tubepress_lib_http_api_Constants::PARAM_NAME_ITEMID)->andReturn('');

        $this->_sut->onHtmlGeneration($this->_mockEvent);
        $this->assertTrue(true);
    }

    public function testExecute()
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_app_player_api_Constants::OPTION_PLAYER_LOCATION)->andReturn('solo');

        $this->_mockExecutionContext->shouldReceive('setEphemeralOption')->once()->with(tubepress_app_feature_single_api_Constants::OPTION_MEDIA_ITEM_ID, 'video-id')->andReturn(true);

        $this->_mockHttpRequestParameterService->shouldReceive('getParamValue')->twice()->with(tubepress_lib_http_api_Constants::PARAM_NAME_ITEMID)->andReturn('video-id');

        $this->_sut->onHtmlGeneration($this->_mockEvent);
        $this->assertTrue(true);
    }
}