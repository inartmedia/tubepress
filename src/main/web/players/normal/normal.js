/**
 * Copyright 2006 - 2012 Eric D. Hough (http://ehough.com)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/*global jQuery, TubePressAjax, TubePressEvents */
/*jslint sloppy: true, white: true, onevar: true, undef: true, newcap: true, nomen: true, regexp: true, plusplus: true, bitwise: true, continue: true, browser: true, maxerr: 50, indent: 4 */

var TubePressNormalPlayer = (function () {
	
	var prefix  = 'tubepress_',
	
		getTitleId = function (gId) {
			return '#' + prefix + 'embedded_title_' + gId;
		},
	
		/* this stuff helps compression */
		jquery	= jQuery,
		tpAjax	= TubePressAjax,
		events	= TubePressEvents,
		name	= 'normal',
		doc		= jquery(document),
	
		applyLoadingStyle = function (id) {
			tpAjax.applyLoadingStyle(id);
		},
		
		removeLoadingStyle = function (id) {
			tpAjax.removeLoadingStyle(id);
		},
	
		getEmbedId = function (gId) {
			return '#' + prefix + 'embedded_object_' + gId;
		},
	
		invoke = function (e, videoId, galleryId, width, height) {

			var titleDivId = getTitleId(galleryId);
			
			applyLoadingStyle(titleDivId);
			applyLoadingStyle(getEmbedId(galleryId));

			jquery(titleDivId)[0].scrollIntoView(true);
		},
		
		populate = function (e, title, html, height, width, videoId, galleryId) {
			
			jquery('#' + prefix + 'gallery_' + galleryId + ' div.' + prefix + 'normal_embedded_wrapper:first').replaceWith(html);
			
			removeLoadingStyle(getTitleId(galleryId));
			removeLoadingStyle(getEmbedId(galleryId));
		};

	doc.bind(events.PLAYER_INVOKE + name, invoke);
	doc.bind(events.PLAYER_POPULATE + name, populate);
} ());

