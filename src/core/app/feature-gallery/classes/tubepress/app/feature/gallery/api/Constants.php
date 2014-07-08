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
 * @api
 * @since 4.0.0
 */
interface tubepress_app_feature_gallery_api_Constants
{
    /**
     * This event is fired after TubePress builds the HTML for gallery pagination.
     *
     * @subject `string` The pagination HTML.
     *
     * @api
     * @since 4.0.0
     */
    const EVENT_HTML_PAGINATION = 'tubepress.core.html.gallery.event.html.pagination';

    /**
     * This event is fired after TubePress builds the HTML for a media gallery.
     *
     * @subject `string` The HTML for the thumbnail gallery.
     *
     * @argument <var>page</var> (`{@link tubepress_app_media_provider_api_Page}`): The backing {@link tubepress_app_media_provider_api_Page}.
     * @argument <var>pageNumber</var> (`integer`): The page number.
     *
     * @api
     * @since 4.0.0
     */
    const EVENT_HTML_THUMBNAIL_GALLERY = 'tubepress.core.html.gallery.event.html.gallery';

    /**
     * This event is fired after TubePress builds the pagination HTML template.
     *
     * @subject `tubepress_lib_template_api_TemplateInterface` The template for the pagination.
     *
     * @api
     * @since 4.0.0
     */
    const EVENT_TEMPLATE_PAGINATION = 'tubepress.core.html.gallery.event.template.pagination';

    /**
     * This event is fired after TubePress builds the PHP/HTML template for a media gallery.
     *
     * @subject `tubepress_lib_template_api_TemplateInterface` The template.
     *
     * @argument <var>page</var> (`{@link tubepress_app_media_provider_api_Page}`): The backing {@link tubepress_app_media_provider_api_Page}
     * @argument <var>pageNumber</var> (`integer`): The page number.
     *
     * @api
     * @since 4.0.0
     */
    const EVENT_TEMPLATE_THUMBNAIL_GALLERY = 'tubepress.core.html.gallery.event.template.gallery';

    /**
     * This event is fired after TubePress builds the gallery initialization JSON, which is inserted immediately
     * after each gallery as it appears in the HTML.
     *
     * @subject `array` An associative `array` that will be converted into JSON and applied as
     *                  init code for the gallery in JavaScript.
     *
     * @api
     * @since 4.0.0
     */
    const EVENT_GALLERY_INIT_JS = 'tubepress.core.html.gallery.event.galleryInitJs';

    /**
     * @api
     * @since 4.0.0
     */
    const OPTION_AUTONEXT = 'autoNext';

    /**
     * @api
     * @since 4.0.0
     */
    const OPTION_SEQUENCE = 'sequence';

    /**
     * @api
     * @since 4.0.0
     */
    const OPTION_AJAX_PAGINATION = 'ajaxPagination';

    /**
     * @api
     * @since 4.0.0
     */
    const OPTION_FLUID_THUMBS = 'fluidThumbs';

    /**
     * @api
     * @since 4.0.0
     */
    const OPTION_HQ_THUMBS = 'hqThumbs';

    /**
     * @api
     * @since 4.0.0
     */
    const OPTION_PAGINATE_ABOVE = 'paginationAbove';

    /**
     * @api
     * @since 4.0.0
     */
    const OPTION_PAGINATE_BELOW = 'paginationBelow';

    /**
     * @api
     * @since 4.0.0
     */
    const OPTION_RANDOM_THUMBS = 'randomize_thumbnails';

    /**
     * @api
     * @since 4.0.0
     */
    const OPTION_THUMB_HEIGHT = 'thumbHeight';

    /**
     * @api
     * @since 4.0.0
     */
    const OPTION_THUMB_WIDTH = 'thumbWidth';

    /**
     * @api
     * @since 4.0.0
     */
    const OPTIONS_UI_CATEGORY_THUMBNAILS = 'tubepress-core-thumbnails-category';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_PAGINATION_BOTTOM = 'bottomPagination';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_PAGINATION_TOP = 'topPagination';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_PAGINATION_CURRENT_PAGE = 'paginationCurrentPage';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_PAGINATION_TOTAL_ITEMS = 'paginationTotalItems';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_PAGINATION_RESULTS_PER_PAGE = 'paginationResultsPerPage';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_PAGINATION_TEXT_NEXT = 'paginationTextNext';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_PAGINATION_TEXT_PREV = 'paginationTextPrev';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_PAGINATION_HREF_FORMAT = 'paginationHrefFormat';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_THUMBNAIL_HEIGHT = 'thumbHeight';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_THUMBNAIL_WIDTH = 'thumbWidth';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_MEDIA_ITEM_ARRAY = 'mediaItemArray';

    /**
     * @api
     * @since 4.0.0
     */
    const TEMPLATE_VAR_PLAYER_HTML = 'preGallery';
}