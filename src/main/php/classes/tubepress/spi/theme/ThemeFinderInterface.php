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
 * Finds TubePress themes.
 */
interface tubepress_spi_theme_ThemeFinderInterface
{
    const _ = 'tubepress_spi_theme_ThemeFinderInterface';

    /**
     * @return tubepress_spi_theme_ThemeInterface[]
     */
    function findAllThemes();
}