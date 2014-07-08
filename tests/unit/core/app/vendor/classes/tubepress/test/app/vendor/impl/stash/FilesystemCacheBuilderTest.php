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
 * @covers tubepress_app_vendor_impl_stash_FilesystemCacheBuilder
 */
class tubepress_test_app_vendor_impl_stash_FilesystemCacheBuilderTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_app_vendor_impl_stash_FilesystemCacheBuilder
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockContext;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockFs;


    public function onSetup()
    {
        $this->_mockFs      = $this->mock('ehough_filesystem_FilesystemInterface');
        $this->_mockContext = $this->mock(tubepress_app_options_api_ContextInterface::_);
        $this->_sut         = new tubepress_app_vendor_impl_stash_FilesystemCacheBuilder(

            $this->_mockContext,
            $this->_mockFs
        );
    }

    public function testBuildCache()
    {
        $this->_mockContext->shouldReceive('get')->once()->with(tubepress_app_apicache_api_Constants::OPTION_DIRECTORY)->andReturn('/abc');
        $this->_mockFs->shouldReceive('getSystemTempDirectory')->once()->andReturn(sys_get_temp_dir());

        $result = $this->_sut->buildFilesystemDriver();

        $this->assertInstanceOf('ehough_stash_interfaces_DriverInterface', $result);
    }
}