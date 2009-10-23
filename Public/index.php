<?php

/**
 * Web index
 *
 * This is the entry point of the Spiral web application.
 * Configure and run a Spiral web application.
 *
 * @author  	Alexis Métaireau	28 jul. 2009
 * @author  	Frédéric Sureau
 * @copyright	Alexis Metaireau 	2009
 * @license		http://opensource.org/licenses/gpl-3.0.html GNU Public License V3
 */

// Include the bootstrap class
require_once(__DIR__.'/../Framework/Bootstrap/Classes/WebBootstrap.php');

// Bootstrap the application
$bootstrap = new \Spiral\Framework\Bootstrap\WebBootstrap();
$bootstrap->run();