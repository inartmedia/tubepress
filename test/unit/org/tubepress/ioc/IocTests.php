<?php
require_once '/Applications/MAMP/bin/php5/lib/php/PHPUnit/Framework.php';
require_once 'FreeWordPressPluginIocServiceTest.php';
require_once 'IocContainerTest.php';

class IocTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('TubePress IOC Tests');
		$suite->addTestSuite('org_tubepress_ioc_FreeWordPressPluginIocServiceTest');
		$suite->addTestSuite('org_tubepress_ioc_IocContainerTest');
		return $suite;
	}
}
?>