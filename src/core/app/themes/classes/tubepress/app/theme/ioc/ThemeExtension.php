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
class tubepress_app_theme_ioc_ThemeExtension implements tubepress_platform_api_ioc_ContainerExtensionInterface
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
            tubepress_app_theme_api_ThemeLibraryInterface::_,
            'tubepress_app_theme_impl_ThemeLibrary'
        )->addArgument('%themes%')
         ->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_app_options_api_ContextInterface::_))
         ->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_app_environment_api_EnvironmentInterface::_))
         ->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_lib_url_api_UrlFactoryInterface::_))
         ->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_platform_api_util_LangUtilsInterface::_))
         ->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_platform_api_log_LoggerInterface::_));

        $containerBuilder->register(
            'tubepress_app_theme_impl_ThemeRegistry',
            'tubepress_app_theme_impl_ThemeRegistry'
        )->addArgument(new tubepress_platform_api_ioc_Reference('tubepress_platform_impl_log_BootLogger'))
         ->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_platform_api_boot_BootSettingsInterface::_))
         ->addArgument(new tubepress_platform_api_ioc_Reference('ehough_finder_FinderFactoryInterface'))
         ->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_app_contrib_api_ContributableValidatorInterface::_))
         ->addTag(tubepress_platform_api_contrib_RegistryInterface::_, array('type' => 'tubepress_app_theme_api_ThemeInterface'));

        $containerBuilder->register(
            'tubepress_app_theme_impl_listeners_options_LegacyThemeListener',
            'tubepress_app_theme_impl_listeners_options_LegacyThemeListener'
        )->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_platform_api_log_LoggerInterface::_))
         ->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_app_theme_api_ThemeLibraryInterface::_))
         ->addTag(tubepress_lib_ioc_api_Constants::TAG_EVENT_LISTENER, array(
            'event'    => tubepress_app_options_api_Constants::EVENT_OPTION_SET . '.' . tubepress_app_theme_api_Constants::OPTION_THEME,
            'method'   => 'onPreValidationSet',
            'priority' => 300000
        ));

        $containerBuilder->register(
            'tubepress_app_theme_impl_listeners_options_AcceptableValues',
            'tubepress_app_theme_impl_listeners_options_AcceptableValues'
        )->addArgument(new tubepress_platform_api_ioc_Reference(tubepress_app_theme_api_ThemeLibraryInterface::_))
         ->addTag(tubepress_lib_ioc_api_Constants::TAG_EVENT_LISTENER, array(
            'event'    => tubepress_app_options_api_Constants::EVENT_OPTION_GET_ACCEPTABLE_VALUES . '.' . tubepress_app_theme_api_Constants::OPTION_THEME,
            'method'   => 'onAcceptableValues',
            'priority' => 30000
        ));

        $containerBuilder->setParameter(tubepress_app_options_api_Constants::IOC_PARAM_EASY_REFERENCE . '_theme', array(

            'defaultValues' => array(
                tubepress_app_theme_api_Constants::OPTION_THEME => 'tubepress/default',
            ),

            'labels' => array(
                tubepress_app_theme_api_Constants::OPTION_THEME => 'Theme',  //>(translatable)<
            )
        ));

        $containerBuilder->register(
            'theme_field',
            'tubepress_app_options_ui_api_FieldInterface'
        )->setFactoryService(tubepress_app_options_ui_api_FieldBuilderInterface::_)
         ->setFactoryMethod('newInstance')
         ->addArgument(tubepress_app_theme_api_Constants::OPTION_THEME)
         ->addArgument('theme');

        $containerBuilder->register(
            'theme_category',
            'tubepress_app_options_ui_api_ElementInterface'
        )->setFactoryService(tubepress_app_options_ui_api_ElementBuilderInterface::_)
            ->setFactoryMethod('newInstance')
            ->addArgument(tubepress_app_theme_api_Constants::OPTIONS_UI_CATEGORY_THEMES)
            ->addArgument('Theme');

        $fieldMap = array(
            tubepress_app_theme_api_Constants::OPTIONS_UI_CATEGORY_THEMES => array(
                tubepress_app_theme_api_Constants::OPTION_THEME
            )
        );

        $containerBuilder->register(

            'tubepress_app_theme_impl_options_ui_FieldProvider',
            'tubepress_app_theme_impl_options_ui_FieldProvider'
        )->addArgument(array(new tubepress_platform_api_ioc_Reference('theme_category')))
            ->addArgument(array(new tubepress_platform_api_ioc_Reference('theme_field')))
            ->addArgument($fieldMap)
            ->addTag('tubepress_app_options_ui_api_FieldProviderInterface');
    }
}