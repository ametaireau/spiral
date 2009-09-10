<?php
namespace Spiral\Framework\DI\Definition;

use \Spiral\Framework\DI\Construction;

/**
 * Interface for a Schema method
 * 
 * This class represents all methods of services (classes) of the schema. 
 * It could be a standard dynamic method or a static one.
 * 
 * When defining arguments of a method representing a service, it can be 
 * resolved thanks to the ARG_IS_SERVICE const.
 *
 * Here is an exemple of how to use this interface:
 * <code>
 * // define a dynamic method, and add it a service argument, and a standard arg
 * $dMethod = new Method('methodName');
 * $dMethod->addArgument('service', true);
 * $dMethod->addArgument('arg1');
 * 
 * // define a static method, with an standard argument
 * $sMethod = new Method('staticMethod', 'className');
 * $sMethod->addArgument('arg1');
 *
 * // then, when needed, call getArgument method
 * $dMethod->getArguments()
 * // will return an associative array of arguments
 *
 * // can be used in a foreach statement:
 * foreach($dMethod as $argument){
 * 		// do some stuff with $argument
 * }
 * </code>
 *
 * @author  	Alexis Métaireau	16 apr. 2009
 * @copyright	Alexis Metaireau 	2009
 * @license		http://opensource.org/licenses/gpl-3.0.html GNU Public License V3
 */
interface Method
{		
	/**
     * return the construction strategy object
     * 
     * @return \Spiral\Framework\DI\Definition\MethodConstructionStrategy
     */
    public function getConstructionStrategy();
    
    /**
     * Set the construction strategy object
     * 
     * @param 	\Spiral\Framework\DI\Construction\MethodConstructionStrategy $context
     * @return 	void
     */
    public function setConstructionStrategy(Construction\MethodConstructionStrategy $strategy);
    
	/**
	 * Returne the name of this method
	 * 
	 * @return string
	 */
	public function getName();

}
