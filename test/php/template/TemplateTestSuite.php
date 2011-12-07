<?php

require_once 'templates/wordpress/WordPressTemplateTestSuite.php';
require_once 'templates/optionspage/OptionsPageTemplateTestSuite.php';

class org_tubepress_template_TemplateTestSuite
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite();

		$suite->addTestSuite(org_tubepress_impl_template_templates_wordpress_WordPressTemplateTestSuite::suite());
        $suite->addTestSuite(org_tubepress_impl_template_templates_optionspage_OptionsPageTemplateTestSuite::suite());

		return $suite;
	}
}
