<?php
/**
 * Copyright 2006 - 2015 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * @covers tubepress_app_impl_theme_FilesystemTheme<extended>
 */
class tubepress_test_app_impl_theme_FilesystemThemeTest extends tubepress_test_platform_impl_contrib_AbstractContributableTest
{
    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockBaseUrl;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockUserContentUrl;

    public function onSetup()
    {
        $this->_mockBaseUrl        = $this->mock(tubepress_platform_api_url_UrlInterface::_);
        $this->_mockUserContentUrl = $this->mock(tubepress_platform_api_url_UrlInterface::_);
    }

    public function testParentThemeName()
    {
        /**
         * @var $theme tubepress_app_impl_theme_FilesystemTheme
         */
        $theme = $this->buildContributable();

        $this->assertNull($theme->getParentThemeName());

        $theme->setParentThemeName('hi');

        $this->assertEquals('hi', $theme->getParentThemeName());
    }

    /**
     * @dataProvider getDataScriptsStyles
     */
    public function testScripts($setter, $getter, $isAbsolute)
    {
        /**
         * @var $theme tubepress_app_impl_theme_FilesystemTheme
         */
        $theme = $this->buildContributable();

        $mockUrl = $this->mock('tubepress_platform_api_url_UrlInterface');

        $mockUrl->shouldReceive('isAbsolute')->once()->andReturn($isAbsolute);

        $theme->$setter(array($mockUrl));

        if ($isAbsolute) {

            $actual = $theme->$getter($this->_mockBaseUrl, $this->_mockUserContentUrl);

            $this->assertTrue(is_array($actual));
            $this->assertCount(1, $actual);
            $this->assertSame($mockUrl, $actual[0]);

        } else {

            list($handle, $manifestPath) = $this->getTemporaryFile();
            $theme->setManifestPath($manifestPath);
            $this->_mockUserContentUrl->shouldReceive('getClone')->once()->andReturn($this->_mockUserContentUrl);
            $this->_mockUserContentUrl->shouldReceive('addPath')->once()->with('/themes/' . basename(dirname($manifestPath)) . '/url as string');
            $mockUrl->shouldReceive('__toString')->twice()->andReturn('url as string');

            $actual = $theme->$getter($this->_mockBaseUrl, $this->_mockUserContentUrl);

            $this->assertTrue(is_array($actual));
            $this->assertCount(1, $actual);
            $this->assertSame($this->_mockUserContentUrl, $actual[0]);
        }
    }

    public function getDataScriptsStyles()
    {
        return array(
            array('setScripts', 'getUrlsJS', true),
            array('setScripts', 'getUrlsJS', false),
            array('setStyles', 'getUrlsCSS', true),
            array('setStyles', 'getUrlsCSS', false),
        );
    }

    public function testInlineCss()
    {
        /**
         * @var $theme tubepress_app_impl_theme_FilesystemTheme
         */
        $theme = $this->buildContributable();

        $theme->setInlineCss('hello');

        $this->assertEquals('hello', $theme->getInlineCSS());
    }

    public function testTemplateData()
    {
        $tmpFile = tmpfile();
        fwrite($tmpFile, 'hi');
        $data = stream_get_meta_data($tmpFile);
        $path = $data['uri'];
        $map = array('a' => $path);

        /**
         * @var $theme tubepress_app_impl_theme_FilesystemTheme
         */
        $theme = $this->buildContributable();

        $theme->setTemplateNamesToAbsPathsMap($map);

        $this->assertTrue($theme->hasTemplateSource('a'));
        $this->assertFalse($theme->hasTemplateSource('b'));

        $this->assertEquals('hi', $theme->getTemplateSource('a'));
        $this->assertEquals($path, $theme->getTemplateCacheKey('a'));

        $this->assertTrue($theme->isTemplateSourceFresh('a', time() + 30000));
        $this->assertFalse($theme->isTemplateSourceFresh('a', 0));
    }

    /**
     * @return tubepress_platform_impl_contrib_AbstractContributable
     */
    protected function buildSut($name, $version, $title, array $authors, array $licenses)
    {
        return new tubepress_app_impl_theme_FilesystemTheme($name, $version, $title, $authors, $licenses);
    }
}