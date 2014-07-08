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
 * Detects TubePress's environment
 */
class tubepress_app_environment_impl_Environment implements tubepress_app_environment_api_EnvironmentInterface
{
    /**
     * @var tubepress_lib_version_api_Version
     */
    private $_version;

    /**
     * @var tubepress_lib_url_api_UrlFactoryInterface
     */
    private $_urlFactory;

    /**
     * @var tubepress_wordpress_impl_wp_WpFunctions
     */
    private $_wpFunctionsInterface;

    /**
     * @var tubepress_lib_url_api_UrlInterface TubePress installation URL.
     */
    private $_baseUrl;

    /**
     * @var tubepress_lib_url_api_UrlInterface User content URL.
     */
    private $_userContentUrl;

    /**
     * @var tubepress_platform_api_boot_BootSettingsInterface
     */
    private $_bootSettings;

    /**
     * @var bool
     */
    private $_isPro = false;

    public function __construct(tubepress_lib_url_api_UrlFactoryInterface $urlFactory,
                                tubepress_platform_api_boot_BootSettingsInterface   $bootSettings)
    {
        $this->_version      = tubepress_lib_version_api_Version::parse('9.9.9');
        $this->_urlFactory   = $urlFactory;
        $this->_bootSettings = $bootSettings;
    }

    /**
     * Detects if the user is running TubePress Pro.
     *
     * @return boolean True is the user is running TubePress Pro. False otherwise.
     *
     * @api
     * @since 4.0.0
     */
    public function isPro()
    {
        return $this->_isPro;
    }

    /**
     * Get the current TubePress version.
     *
     * @return tubepress_lib_version_api_Version The current version.
     *
     * @api
     * @since 4.0.0
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * @return tubepress_lib_url_api_UrlInterface The base TubePress URL. May be null.
     *
     * @api
     * @since 4.0.0
     */
    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }

    /**
     * Set the TubePress base URL.
     *
     * @param string|tubepress_lib_url_api_UrlInterface $url The new base URL.
     *
     * @throws InvalidArgumentException If unable to parse URL.
     *
     * @return void
     *
     * @api
     * @since 4.0.0
     */
    public function setBaseUrl($url)
    {
        $this->_baseUrl = $this->_toUrl($url);
    }

    /**
     * @return tubepress_lib_url_api_UrlInterface The user content URL. May be null.
     *
     * @api
     * @since 4.0.0
     */
    public function getUserContentUrl()
    {
        if (!isset($this->_userContentUrl)) {

            if (defined('TUBEPRESS_CONTENT_URL')) {

                $url = rtrim(TUBEPRESS_CONTENT_URL, DIRECTORY_SEPARATOR);

            } else {

                if ($this->_isWordPress()) {

                    $url = $this->_wpFunctionsInterface->content_url();

                } else {

                    if ($this->getBaseUrl() === null) {

                        return null;
                    }

                    $url = $this->getBaseUrl()->toString();
                }

                $url .= '/tubepress-content';
            }

            try {

                $this->_userContentUrl = $this->_toUrl($url);

            } catch (InvalidArgumentException $e) {

                return null;
            }
        }

        return $this->_userContentUrl;
    }

    /**
     * Set the user content URL.
     *
     * @param string|tubepress_lib_url_api_UrlInterface $url The user content URL.
     *
     * @throws InvalidArgumentException If unable to parse URL.
     *
     * @return void
     *
     * @api
     * @since 4.0.0
     */
    public function setUserContentUrl($url)
    {
        $this->_userContentUrl = $this->_toUrl($url);
    }

    public function setWpFunctionsInterface($wpFunctionsInterface)
    {
        if (!is_a($wpFunctionsInterface, 'tubepress_wordpress_impl_wp_WpFunctions')) {

            throw new InvalidArgumentException('Invalid argument to tubepress_app_environment_impl_Environment::setWpFunctionsInterface');
        }

        $this->_wpFunctionsInterface = $wpFunctionsInterface;
    }

    public function markAsPro()
    {
        $this->_isPro = true;
    }

    private function _toUrl($url)
    {
        if (!($url instanceof tubepress_lib_url_api_UrlInterface)) {

            $url = $this->_urlFactory->fromString($url);
        }

        return $url;
    }

    private function _isWordPress()
    {
        return defined('WPLANG') && defined('ABSPATH');
    }
}