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
 * Adds some core variables to the single media item template.
 */
class tubepress_app_feature_single_impl_listeners_template_SingleVideoCoreVariables
{
    /**
     * @var tubepress_app_options_api_ContextInterface
     */
    private $_context;

    /**
     * @var tubepress_app_embedded_api_EmbeddedHtmlInterface
     */
    private $_embeddedHtml;

    public function __construct(tubepress_app_options_api_ContextInterface       $context,
                                tubepress_app_embedded_api_EmbeddedHtmlInterface $embeddedHtml)
    {
        $this->_context      = $context;
        $this->_embeddedHtml = $embeddedHtml;
    }

    public function onSingleVideoTemplate(tubepress_lib_event_api_EventInterface $event)
    {
        $mediaItem = $event->getArgument('item');
        $template  = $event->getSubject();

        $embeddedString = $this->_embeddedHtml->getHtml($mediaItem->getId());
        $width          = $this->_context->get(tubepress_app_embedded_api_Constants::OPTION_EMBEDDED_WIDTH);

        /* apply it to the template */
        $template->setVariable(tubepress_app_embedded_api_Constants::TEMPLATE_VAR_SOURCE, $embeddedString);
        $template->setVariable(tubepress_app_embedded_api_Constants::TEMPLATE_VAR_WIDTH, $width);
        $template->setVariable(tubepress_app_feature_single_api_Constants::TEMPLATE_VAR_MEDIA_ITEM, $mediaItem);
    }
}