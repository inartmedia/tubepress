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
 * Most of this logic is loosely based on
 * https://github.com/KnpLabs/KnpPaginatorBundle/blob/master/Pagination/SlidingPagination.php
 */

/**
 * IF YOU SIMPLY WISH TO MODIFY THE HTML, SKIP DOWN TO LINE 115.
 */



$currentPageNum = ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_CURRENT_PAGE};
$totalItems     = ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_TOTAL_ITEMS};
$itemsPerPage   = ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_RESULTS_PER_PAGE};
$pageCount      = intval(ceil($totalItems / $itemsPerPage));
$dots           = '<li class="disabled"><a href="#">...</a></li>';
$pageRange      = 5;

if ($pageCount < $currentPageNum) {

    $currentPageNum = $pageCount;
}

if ($pageRange > $pageCount) {

    $pageRange = $pageCount;
}

$delta = ceil($pageRange / 2);

if ($currentPageNum - $delta > $pageCount - $pageRange) {

    $pages = range($pageCount - $pageRange + 1, $pageCount);

} else {

    if ($currentPageNum - $delta < 0) {

        $delta = $currentPageNum;
    }

    $offset = $currentPageNum - $delta;
    $pages  = range($offset + 1, $offset + $pageRange);
}

$proximity  = floor($pageRange / 2);
$startPage  = $currentPageNum - $proximity;
$endPage    = $currentPageNum + $proximity;

if ($startPage < 1) {

    $endPage   = min($endPage + (1 - $startPage), $pageCount);
    $startPage = 1;
}

if ($endPage > $pageCount) {

    $startPage = max($startPage - ($endPage - $pageCount), 1);
    $endPage   = $pageCount;
}

if ($currentPageNum - 1 > 0) {

    $previousPage = $currentPageNum - 1;
}

if ($currentPageNum + 1 <= $pageCount) {

    $nextPage = $currentPageNum + 1;
}

$firstPageInRange = min($pages);
$lastPageInRange  = max($pages);
$currentItemCount = count($pages);
$firstItemNumber  = (($currentPageNum - 1) * $itemsPerPage) + 1;
$lastItemNumber   = $firstItemNumber + $currentItemCount - 1;

if (!function_exists('___a')) {

    function ___a($page, $format, $innerText = null)
    {
        if (!$innerText) {

            $innerText = $page;
        }
        $href = sprintf($format, $page);

        if ($page > 1) {

            $noFollow = ' rel="nofollow"';

        } else {

            $noFollow = '';
        }

        /**
         * If you modify this theme, do not remove the "js-tubepress-*" class names as doing
         * so will break Ajax pagination.
         */
        echo <<<ABC
<a href="$href" class="js-tubepress-pager js-tubepress-page-$page"$noFollow>$innerText</a>
ABC;

    }
}

if ($pageCount > 1) : ?>
    <div class="tubepress-pagination tubepress-pagination-centered">
        <ul>

        <?php if (isset($previousPage)) : ?>
            <li>
                <?php ___a($previousPage, ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_HREF_FORMAT}, '&laquo;&nbsp;' . ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_TEXT_PREV}); ?>
            </li>
        <?php else: ?>
            <li class="disabled">
                <span>&laquo;&nbsp;<?php echo ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_TEXT_PREV}; ?></span>
            </li>
        <?php endif;

        if ($startPage > 1) : ?>

            <li>
                <?php ___a(1, ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_HREF_FORMAT}); ?>
            </li>

            <?php if ($startPage == 3) : ?>

                <li>
                    <?php ___a(2, ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_HREF_FORMAT}); ?>
                </li>

            <?php elseif ($startPage != 2): ?>

                <li class="disabled">
                    <span>&hellip;</span>
                </li>

            <?php endif;

        endif;

        foreach ($pages as $page) :
            if ($page != $currentPageNum) : ?>
                <li>
                    <?php ___a($page, ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_HREF_FORMAT}); ?>
                </li>
            <?php else: ?>
                <li class="active">
                    <span><?php echo $page; ?></span>
                </li>
            <?php endif;
        endforeach; ?>

        <?php if ($pageCount > $endPage) :
            if ($pageCount > ($endPage + 1)) :
                if ($pageCount > ($endPage + 2)) : ?>
                    <li class="disabled">
                        <span>&hellip;</span>
                    </li>
                <?php else: ?>
                    <li>
                        <?php ___a(($pageCount - 1), ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_HREF_FORMAT}); ?>
                    </li>
                <?php endif;
            endif; ?>
            <li>
                <?php ___a($pageCount, ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_HREF_FORMAT}); ?>
            </li>
        <?php endif;
        if (isset($nextPage)) : ?>
            <li>
                <?php ___a($nextPage, ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_HREF_FORMAT}, ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_TEXT_NEXT} . '&nbsp;&raquo;'); ?>
            </li>
        <?php else: ?>
            <li class="disabled">
                <span><?php echo ${tubepress_app_feature_gallery_api_Constants::TEMPLATE_VAR_PAGINATION_TEXT_NEXT}; ?>&nbsp;&raquo;</span>
            </li>
        <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>