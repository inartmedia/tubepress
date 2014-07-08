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
 * @covers tubepress_app_options_ui_impl_fields_GallerySourceRadioField<extended>
 */
class tubepress_test_app_options_ui_impl_fields_GallerySourceRadioFieldTest extends tubepress_test_app_options_ui_impl_fields_AbstractTemplateBasedOptionsPageFieldTest
{
    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockContext;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockAdditionalField;

    public function testGetName()
    {
        $this->_mockAdditionalField->shouldReceive('getTranslatedDisplayName')->once()->andReturn('hi');

        $result = $this->getSut()->getTranslatedDisplayName();

        $this->assertEquals('hi', $result);
    }

    public function testGetDescription()
    {
        $this->_mockAdditionalField->shouldReceive('getTranslatedDescription')->once()->andReturn('hi');

        $result = $this->getSut()->getTranslatedDescription();

        $this->assertEquals('hi', $result);
    }

    public function testIsPro()
    {
        $this->assertFalse($this->getSut()->isProOnly());
    }

    public function testSubmit()
    {
        $this->_mockAdditionalField->shouldReceive('onSubmit')->once()->andReturn('hello');

        $result = $this->getSut()->onSubmit();

        $this->assertEquals('hello', $result);
    }

    /**
     * @return tubepress_app_options_ui_impl_fields_GallerySourceRadioField
     */
    protected function getSut()
    {
        return parent::getSut();
    }

    /**
     * @return string
     */
    protected function getOptionsPageItemId()
    {
        return 'foo';
    }

    protected function onAfterTemplateBasedFieldSetup()
    {
        $this->_mockContext = $this->mock(tubepress_app_options_api_ContextInterface::_);
        $this->_mockAdditionalField = $this->mock('tubepress_app_options_ui_api_FieldInterface');
    }

    /**
     * @return tubepress_app_options_ui_impl_BaseElement
     */
    protected function buildSut()
    {
        return new tubepress_app_options_ui_impl_fields_GallerySourceRadioField(

            'foo',
            $this->getMockTranslator(),
            $this->getMockPersistence(),
            $this->getMockHttpRequestParams(),
            $this->getMockEventDispatcher(),
            $this->getMockTemplateFactory(),
            $this->_mockContext,
            $this->_mockAdditionalField
        );
    }

    /**
     * @return string
     */
    protected function getExpectedTemplatePath()
    {
        return TUBEPRESS_ROOT . '/src/core/app/options-ui/resources/field-templates/gallery-source-radio.tpl.php';
    }

    /**
     * @return void
     */
    protected function prepareForGetWidgetHtml(ehough_mockery_mockery_MockInterface $template)
    {
        $this->_mockContext->shouldReceive('get')->once()->with(tubepress_app_media_provider_api_Constants::OPTION_GALLERY_SOURCE)->andReturn('somethin');
        $this->_mockAdditionalField->shouldReceive('getWidgetHTML')->once()->andReturn('boo');

        $template->shouldReceive('setVariable')->once()->with('modeName', 'foo');
        $template->shouldReceive('setVariable')->once()->with('currentMode', 'somethin');
        $template->shouldReceive('setVariable')->once()->with('additionalFieldWidgetHtml', 'boo');
    }
}