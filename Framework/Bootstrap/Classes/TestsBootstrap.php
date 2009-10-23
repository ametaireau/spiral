<?php

namespace Spiral\Framework\Bootstrap;

require_once('Bootstrap.php');
require_once('PackageLoader.php');
require_once('PEARLoader.php');

/**
 * Tests bootstrap
 * 
 * Launch all the tests of the framework.
 *
 * @author		Frédéric Sureau
 * @copyright	Frédéric Sureau
 * @license		http://opensource.org/licenses/gpl-3.0.html GNU Public License V3
 */
class TestsBootstrap implements Bootstrap
{
	/**
	 * Bootstrap the application
	 *
	 * @return 	void
	 */
	public function run()
	{
		// Define include path
		$spiralRootPath = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..';
		set_include_path( $spiralRootPath .PATH_SEPARATOR. get_include_path() );
		
		// Configure the package loader and register it in the SPL autoload queue
		PackageLoader::addSearchDirectory('Classes', 'Tests');
		spl_autoload_register('\Spiral\Framework\Bootstrap\PackageLoader::load');
		
		// Register the PEAR loader for PHPUnit framework
		spl_autoload_register('\Spiral\Framework\Bootstrap\PEARLoader::load');
		
		// Run CLI of PHPUnit
		\PHPUnit_TextUI_Command::main();
	}
}