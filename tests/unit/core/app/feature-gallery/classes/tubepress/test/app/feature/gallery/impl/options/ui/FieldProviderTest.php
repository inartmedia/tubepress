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
 * @covers tubepress_app_feature_gallery_impl_options_ui_FieldProvider<extended>
 */
class tubepress_test_app_feature_gallery_impl_options_ui_FieldProviderTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_app_feature_gallery_impl_options_ui_FieldProvider
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockCategory;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockField;

    public function onSetup()
    {
        $this->_mockCategory = $this->mock('tubepress_app_options_ui_api_ElementInterface');
        $this->_mockField    = $this->mock('tubepress_app_options_ui_api_FieldInterface');
        $map = array(
            'x' => array('a', 'b', 'c')
        );

        $this->_sut = new tubepress_app_feature_gallery_impl_options_ui_FieldProvider(
            array($this->_mockCategory),
            array($this->_mockField),
            $map
        );
    }

    public function testBasics()
    {
        $id         = $this->_sut->getId();
        $name       = $this->_sut->getTranslatedDisplayName();
        $categories = $this->_sut->getCategories();
        $fields     = $this->_sut->getFields();

        $this->assertEquals('', $name);
        $this->assertEquals('tubepress-core-thumbnails-category', $id);
        $this->assertTrue(is_array($categories) && count($categories) === 1);
        $this->assertSame($categories[0], $this->_mockCategory);
        $this->assertTrue(is_array($fields) && count($fields) === 1);
        $this->assertSame($fields[0], $this->_mockField);
        $this->assertFalse($this->_sut->isAbleToBeFilteredFromGui());
        $this->assertFalse($this->_sut->fieldsShouldBeInSeparateBoxes());
        $this->assertEquals(array(
            'x' => array('a', 'b', 'c')
        ), $this->_sut->getCategoryIdsToFieldIdsMap());
    }
}