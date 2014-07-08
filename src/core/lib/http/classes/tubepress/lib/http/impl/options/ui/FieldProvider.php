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
 */
class tubepress_lib_http_impl_options_ui_FieldProvider implements tubepress_app_options_ui_api_FieldProviderInterface
{
    /**
     * @var tubepress_app_options_ui_api_FieldInterface[]
     */
    private $_fields;

    /**
     * @var array tubepress_app_options_ui_api_ElementInterface[]
     */
    private $_categories;

    /**
     * @var array
     */
    private $_map;

    public function __construct(array $categories, array $fields, array $map)
    {
        $this->_categories = $categories;
        $this->_fields     = $fields;
        $this->_map        = $map;
    }

    /**
     * @return string The name of the item that is displayed to the user.
     *
     * @api
     * @since 4.0.0
     */
    public function getTranslatedDisplayName()
    {
        return '';
    }

    /**
     * @return string The page-unique identifier for this item.
     *
     * @api
     * @since 4.0.0
     */
    public function getId()
    {
        return 'tubepress-core-http-field-provider';
    }

    /**
     * @return tubepress_app_options_ui_api_ElementInterface[] The categories that this field provider supplies.
     *
     * @api
     * @since 4.0.0
     */
    public function getCategories()
    {
        return $this->_categories;
    }

    /**
     * @return tubepress_app_options_ui_api_FieldInterface[] The fields that this field provider provides.
     *
     * @api
     * @since 4.0.0
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @return array An associative array, which may be empty, where the keys are category IDs and the values
     *               are arrays of field IDs that belong in the category.
     *
     * @api
     * @since 4.0.0
     */
    public function getCategoryIdsToFieldIdsMap()
    {
        return $this->_map;
    }

    /**
     * @return boolean True if this field provider should show up in the "Only show options to..." dropdown. False otherwise.
     *
     * @api
     * @since 4.0.0
     */
    public function isAbleToBeFilteredFromGui()
    {
        return false;
    }

    /**
     * @return boolean True if this field provider should separate its field into separate boxes. False otherwise.
     *
     * @api
     * @since 4.0.0
     */
    public function fieldsShouldBeInSeparateBoxes()
    {
        return false;
    }
}