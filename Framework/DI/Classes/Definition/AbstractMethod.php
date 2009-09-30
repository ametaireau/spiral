<?php
namespace Spiral\Framework\DI\Definition;

use \Spiral\Framework\DI\Construction;
use \Spiral\Framework\DI\Definition\Exception\UnknownArgumentException;

/**
 * Abstract method
 * 
 * @author  	Alexis Métaireau	08 apr. 2009
 * @copyright	Alexis Metaireau 	2009
 * @license		http://opensource.org/licenses/gpl-3.0.html GNU Public License V3
 */
abstract class AbstractMethod
{		
	/**
     * the construction strategy used to build the argument
     * 
     * @var 	\Spiral\Framework\DI\Definition\MethodConstructionStrategy
     */
    protected $_strategy;
    
    /**
     * return the construction strategy object
     * 
     * @return \Spiral\Framework\DI\Definition\MethodConstructionStrategy
     */
    public function getConstructionStrategy(){
    	return $this->_strategy;
    }
    
    /**
     * Set the construction strategy object
     * 
     * @param 	\Spiral\Framework\DI\Construction\MethodConstructionStrategy $context
     * @return 	void
     */
    public function setConstructionStrategy(Construction\MethodConstructionStrategy $strategy){
		$strategy->setMethod($this);
    	$this->_strategy = $strategy;
    }  
}
?>
