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
 * @covers tubepress_lib_http_ioc_HttpExtension
 */
class tubepress_test_lib_http_impl_HttpExtensionTest extends tubepress_test_ioc_AbstractIocContainerExtensionTest
{

    /**
     * @return tubepress_lib_http_ioc_HttpExtension
     */
    protected function buildSut()
    {
        return  new tubepress_lib_http_ioc_HttpExtension();
    }

    protected function prepareForLoad()
    {
        $this->expectRegistration(
            tubepress_lib_http_api_oauth_v1_ClientInterface::_,
            'tubepress_lib_http_impl_oauth_v1_Client'
        );

        $this->expectRegistration(
            'puzzle.httpClient',
            'puzzle_Client'
        );

        $this->expectRegistration(
            tubepress_lib_http_api_HttpClientInterface::_,
            'tubepress_lib_http_impl_puzzle_PuzzleHttpClient'
        )->withArgument(new tubepress_platform_api_ioc_Reference(tubepress_lib_event_api_EventDispatcherInterface::_))
            ->withArgument(new tubepress_platform_api_ioc_Reference(tubepress_app_environment_api_EnvironmentInterface::_))
            ->withArgument(new tubepress_platform_api_ioc_Reference('puzzle.httpClient'));

        $this->expectRegistration(
            tubepress_lib_http_api_ResponseCodeInterface::_,
            'tubepress_lib_http_impl_ResponseCode'
        );
    }

    protected function getExpectedExternalServicesMap()
    {
        $logger = $this->mock(tubepress_platform_api_log_LoggerInterface::_);
        $logger->shouldReceive('isEnabled')->atLeast(1)->andReturn(true);

        $environment = $this->mock(tubepress_app_environment_api_EnvironmentInterface::_);
        $environment->shouldReceive('getVersion')->once()->andReturn('1.2.3');

        $mockField = $this->mock('tubepress_app_options_ui_api_FieldInterface');
        $fieldBuilder = $this->mock(tubepress_app_options_ui_api_FieldBuilderInterface::_);
        $fieldBuilder->shouldReceive('newInstance')->atLeast(1)->andReturn($mockField);

        return array(

            tubepress_platform_api_log_LoggerInterface::_ => $logger,
            tubepress_app_options_api_ContextInterface::_ => tubepress_app_options_api_ContextInterface::_,
            tubepress_app_player_api_PlayerHtmlInterface::_ => tubepress_app_player_api_PlayerHtmlInterface::_,
            tubepress_app_media_provider_api_CollectorInterface::_ => tubepress_app_media_provider_api_CollectorInterface::_,
            tubepress_lib_event_api_EventDispatcherInterface::_ => tubepress_lib_event_api_EventDispatcherInterface::_,
            tubepress_app_environment_api_EnvironmentInterface::_ => $environment,
            tubepress_app_options_ui_api_FieldBuilderInterface::_ => $fieldBuilder
        );
    }
}