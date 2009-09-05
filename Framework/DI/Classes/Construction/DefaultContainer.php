<?php
namespace Spiral\Framework\DI\Construction;

use \Spiral\Framework\DI\Definition\Schema;
use \Spiral\Framework\Bootstrap\Loader;
use \Spiral\Framework\DI\Definition\FactoryService;
use \Spiral\Framework\DI\Definition\Argument;
use \Spiral\Framework\DI\Definition\Exception\UnknownMethodException;
use \Spiral\Framework\DI\ConstructionAware;

/**
 * Default Container implementation
 *
 * Use autoload of classes by default, if no Loader is specified at
 * construction time.
 *
 * See the interface for further information / documentation.
 *
 * @author  	Alexis Métaireau	16 apr. 2009
 * @copyright	Alexis Metaireau 	2009
 * @license		GNU/GPL V3. Please see the COPYING FILE.
 */
class DefaultContainer implements Container
{

    /**
     * The schema object
     *
     * @var	\Spiral\Framework\DI\Definition\Schema
     */
    protected $_schema;

    /**
     * the loader object
     *
     * @var	\Spiral\Framework\Bootstrap\Loader
     */
    protected $_loader  = null;

    /**
     * Shared services
     *
     * @var array
     */
    protected $_sharedServices = array();
    
    /**
     * The Argument Chain Resolver
     * 
     * @var \Spiral\Framework\DI\Construction\ArgumentChainResolver
     */
    protected $_argumentChainResolver = null;

    /**
     * set the schema object given in parameter
     *
     * @param	Schema     $schema
     * @param	Loader     $loader
     * @return	void
     */
    public function __construct(Schema $schema, Loader $loader = null)
    {
        $this->_schema = $schema;
        if ($loader != null)
        {
            $this->_loader = $loader;
        }
    }

    /**
     * Call the given method
     *
     * @param	mixed	$class	class or Service to call
     * @param	string	$methodName
     * @param	array	$args
     * @return
     */
    protected function _callMethod($class, $methodName, $arguments)
    {
        $this->_load($class);

        if (method_exists($class, $methodName) && is_callable(array($class, $methodName)))
        {
            $object = call_user_func_array(array($class, $methodName), $arguments);
        }

        return $object;
    }

    /**
     * Call the loader if defined
     *
     * @return	void
     */
    protected function _load($className)
    {
        if ($this->_loader != null)
        {
            $this->_loader->load($className);
        }
    }

    /**
     * Iterate all arguments and do some stuff
     *
     * @param	array 	$args
     * @param	object	$object
     * @return	array
     */
    protected function _processMethodArguments(array $arguments, $object = null)
    {
        $processedArguments = array();
        
        foreach($arguments as $argument)
        {
            $processedArguments[] = $this->_argumentChainResolver->resolve($argument, $this, $object);
        }
        return $processedArguments;
    }
    
    /**
     * Set the argument chain resolver
     * 
     * @param 	\Spiral\Framework\DI\Construction\ArgumentChainResolver	$resolver
     * @return 	void
     */
    public function setArgumentChainResolver(ArgumentChainResolver $resolver)
    {
    	$this->_argumentChainResolver = $resolver;
    }

    /**
     * Call all dynamic added methods
     *
     * @param	array	$methods	methods to call
     * @param	mixed	$object 	object to act on
     * @return	void
     */
    public function injectMethods($methods, $object)
    {
        foreach ($methods as $method)
        {
            $methodName = $method->getName();
            if ($methodName != '__construct')
            {
                if ($method->isStatic())
                {
                    $this->_callMethod(
		                $method->getClass(), $methodName,
		                $this->_processMethodArguments($method->getArguments(), $object)
                    );
                } 
                else
                {
                    $this->_callMethod(
		                $object, $methodName,
		                $this->_processMethodArguments($method->getArguments(), $object)
                    );
                }
            }
        }
        return $object;
    }

    /**
     * Resolve all dependencies and return the
     * injected service object
     *
     * @param	string	$key
     * @return	mixed
     * @throws	\Spiral\Framework\DI\Definition\Exception\UnknownServiceException
     */
    public function getService($key){

        // get the registred service object
        $this->_schema->getService($key)->getconstructionStrategy()->buildService($container);

        $className = $service->getClassName();

        $this->_load($className);

        if ($service instanceOf FactoryService){
            $methods = $service->getMethods();
            $method = array_shift($methods);
            $args = $this->_processMethodArguments($method->getArguments());

            $return = $this->_callMethod($service->getClassName(), $method->getName(), $args);

            if ($service->isSingleton())
            {
                $this->_sharedServices[$key] = $return;
            }

            return $return;
        }

        // build the object
        try{
            $constructor = $service->getMethod('__construct');
            // build the object
            $args = $this->_processMethodArguments($constructor->getArguments());

            $params = '';
            for ($i = 0; $i < count($args); $i++)
            {
                $params .= '$args['.$i.'],';
            }
            // really really ugly eval here. Should be replaced by some reflexion
            $params = rtrim($params,',');
            $object = eval("return new $className($params);");

            // if no constructor is defined in the schema, just build the object
        } 
        catch(UnknownMethodException $e) 
        {
            $object = new $className();
        }

        $this->injectMethods($service->getMethods(), $object);

        // For ContainerAware objects
        if($object instanceof ContainerAware)
        {
            $object->setDiContainer($this);
        }

        if($service->isSingleton())
        {
            $this->_sharedServices[$key] = $object;
        }

        return $object;
    }
}
