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
class tubepress_lib_util_ioc_UtilsExtension implements tubepress_platform_api_ioc_ContainerExtensionInterface
{
    /**
     * Called during construction of the TubePress service container. If an add-on intends to add
     * services to the container, it should do so here. The incoming `tubepress_platform_api_ioc_ContainerBuilderInterface`
     * will be completely empty, and after this method is executed will be merged into the primary service container.
     *
     * @param tubepress_platform_api_ioc_ContainerBuilderInterface $containerBuilder An empty `tubepress_platform_api_ioc_ContainerBuilderInterface` instance.
     *
     * @return void
     *
     * @api
     * @since 4.0.0
     */
    public function load(tubepress_platform_api_ioc_ContainerBuilderInterface $containerBuilder)
    {
        $containerBuilder->register(
            tubepress_lib_util_api_UrlUtilsInterface::_,
            'tubepress_lib_util_impl_UrlUtils'
        );

        $containerBuilder->register(
            tubepress_lib_util_api_TimeUtilsInterface::_,
            'tubepress_lib_util_impl_TimeUtils'
        )->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_platform_api_util_StringUtilsInterface::_));

        $containerBuilder->register(
            tubepress_platform_api_util_LangUtilsInterface::_,
            'tubepress_platform_impl_util_LangUtils'
        );

        $containerBuilder->register(
            tubepress_platform_api_util_StringUtilsInterface::_,
            'tubepress_platform_impl_util_StringUtils'
        );
    }
}