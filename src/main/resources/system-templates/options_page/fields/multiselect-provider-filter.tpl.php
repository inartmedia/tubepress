<?php
/**
 * Copyright 2006 - 2012 Eric D. Hough (http://ehough.com)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
?>
<select id="multiselect-<?php echo ${tubepress_impl_options_ui_fields_AbstractMultiSelectField::TEMPLATE_VAR_NAME}; ?>" name="<?php echo ${tubepress_impl_options_ui_fields_AbstractMultiSelectField::TEMPLATE_VAR_NAME}; ?>[]" multiple="multiple">

	<?php foreach (${tubepress_impl_options_ui_fields_FilterMultiSelectField::TEMPLATE_VAR_PROVIDERS} as $providerName => $providerLabel): ?>

	<option value="<?php echo $providerName; ?>" <?php if (in_array($providerName, ${tubepress_impl_options_ui_fields_FilterMultiSelectField::TEMPLATE_VAR_CURRENTVALUES})): ?>selected="selected"<?php endif; ?>><?php echo $providerLabel; ?></option>

	<?php endforeach; ?>
</select>