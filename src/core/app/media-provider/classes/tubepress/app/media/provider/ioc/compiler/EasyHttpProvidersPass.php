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
 *
 */
class tubepress_app_media_provider_ioc_compiler_EasyHttpProvidersPass implements tubepress_platform_api_ioc_CompilerPassInterface
{
    /**
     * @param tubepress_platform_api_ioc_ContainerBuilderInterface $containerBuilder The primary service container builder.
     *
     * @api
     * @since 4.0.0
     */
    public function process(tubepress_platform_api_ioc_ContainerBuilderInterface $containerBuilder)
    {
        $simpleProviderIds = $containerBuilder->findTaggedServiceIds('tubepress_app_media_provider_api_HttpProviderInterface');

        foreach ($simpleProviderIds as $simpleProviderId => $tags) {

            $containerBuilder->register(

                'http_video_provider_for_' . $simpleProviderId,
                'tubepress_app_media_provider_impl_HttpMediaProvider'
            )->addArgument(new tubepress_platform_api_ioc_Reference($simpleProviderId))
             ->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_platform_api_log_LoggerInterface::_))
             ->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_lib_event_api_EventDispatcherInterface::_))
             ->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_lib_http_api_HttpClientInterface::_))
             ->addTag(tubepress_app_media_provider_api_MediaProviderInterface::_);
        }
    }
}