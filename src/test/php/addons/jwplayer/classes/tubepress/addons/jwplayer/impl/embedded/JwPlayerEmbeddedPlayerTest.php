<?php
/**
 * Copyright 2006 - 2013 TubePress LLC (http://tubepress.org)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
class tubepress_addons_jwplayer_impl_embedded_JwPlayerEmbeddedPlayerTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_addons_jwplayer_impl_embedded_JwPlayerPluggableEmbeddedPlayerService
     */
    private $_sut;

    public function onSetup() {

        $this->_sut = new tubepress_addons_jwplayer_impl_embedded_JwPlayerPluggableEmbeddedPlayerService();
    }

    public function testGetName()
    {
        $this->assertEquals('longtail', $this->_sut->getName());
    }

    public function testGetProviderName()
    {
        $this->assertEquals('youtube', $this->_sut->getHandledProviderName());
    }

    public function testGetTemplate()
    {
        $mockThemeHandler = $this->createMockSingletonService(tubepress_spi_theme_ThemeHandler::_);

        $mockThemeHandler->shouldReceive('getTemplateInstance')->once()->with(

            'embedded/longtail.tpl.php',
            TUBEPRESS_ROOT . '/src/main/php/addons/jwplayer/resources/templates'
        )->andReturn('abc');

        $result = $this->_sut->getTemplate($mockThemeHandler);

        $this->assertEquals('abc', $result);
    }

    public function testGetDataUrl()
    {
        $mockExecutionContext = $this->createMockSingletonService(tubepress_spi_context_ExecutionContext::_);

        $result = $this->_sut->getDataUrlForVideo('xx');

        $this->assertTrue($result instanceof ehough_curly_Url);
        $this->assertEquals('http://www.youtube.com/watch?v=xx', $result->toString());
    }



}

