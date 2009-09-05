<?php
namespace Spiral\Framework\DI\Definition;

/**
 * Represents an Inherithed Schema Service
 *
 * @author  	AME 17 juin 2009 
 * @copyright	Alexis Metaireau 	2009
 * @license		http://opensource.org/licenses/gpl-3.0.html GNU Public License V3
 */
class InheritedService extends DefaultService
{

    /**
     * The inherited service name
     * 
     * @var     \Spiral\Framework\DI\Definition\Service
     */
    protected $_inherit;

    /**
     * The schema reference
     *
     * @var     \Spiral\Framework\DI\Definition\Schema
     */
    protected $_schema;

    /**
     * Build an inherithed service
     *
     * @param	\Spiral\Framework\DI\Definition\Schema	$schema
     * @param   string  $service    the service name
     * @param   string  $inherit    the service that is inherited
     * @param   string  $className  the classname, if different than the inherithed one
     * @param   string	$scope
     */
    public function __construct($schema, $service, $inherit, $className='', $scope=null)
    {
        $this->_schema = $schema;
		$this->_serviceName = $service;
        $this->_inherit = $inherit;
        $this->_className  = $className;
        $this->setScope($scope);
	}

    /**
     * Return the name of the inherited service
     * 
     * @return  \Spiral\Framework\DI\Definition\Service
     */
    public function getInheritedService(){
        return $this->_schema->getService($this->_inherit);
    }

    /**
	 * Return the classname
	 *
	 * @return	string
	 */
	public function getClassName(){
        $className = $this->_className;
        if (empty($className)){
            $className = $this->getInheritedService()->getClassName();
        }
        return $className;
    }

    /**
     * Tell if this service is a singleton or not
     *
     * @return  void
     */
    public function isSingleton(){
        $singleton = $this->_isSingleton;
        if (empty($singleton)){
            $singleton = $this->getInheritedService()->isSingleton();
        }
        return $singleton;
    }

    /**
	 * Return the method corresponding to the name
     * 
     * If the child service has implemented the method, return this one,
     * if not, search in the parent service
	 *
	 * @param	string	  $name
	 * @return	Method
	 * @throws	UnknownMethod
	 */
	public function getMethod($name){
        if (parent::hasMethod($name)){
            return parent::getMethod($name);
        }
        return $this->getInheritedService()->getMethod($name);
    }

	/**
	 * return the internal array of methods
	 *
	 * @return Array
	 */
	public function getMethods(){
        $methods = array();
        foreach($this->getInheritedService()->getMethods() as $inheritedMethod){
            $methods[$inheritedMethod->getName()] = $this->getMethod($inheritedMethod->getName());
        }
        foreach(parent::getMethods() as $childMethod){
            if (!isset($methods[$childMethod->getName()])){
                $methods[$childMethod->getName()] = $childMethod;
            }
        }
        return $methods;
    }
}
?>
