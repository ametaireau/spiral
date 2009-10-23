<?php

namespace Spiral\Framework\Bootstrap;

/**
 * Loader interface
 *
 * @author		Alexis Métaireau	16 apr. 2009
 * @copyright	Alexis Metaireau	2009
 * @license		http://opensource.org/licenses/gpl-3.0.html GNU Public License V3
 */
interface Loader
{
	/**
	 * Try to load the required class
	 * 
	 * If the class cannot be loaded, this method return FALSE, else return TRUE.
	 *
	 * @param	string	$class 	Full classname with namespace to load
	 * 
	 * @return	boolean
	 */
	public static function load($class);
}
