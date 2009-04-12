<?php
namespace Spiral\Core\Di;
/**
 * Interface for the Container
 * 
 *
 */
interface Container
{
    /**
     * set the schema object given in parameter
     *
     * @param   Ischema $schema
     * @return  void
     */
    public function  __construct(Schema $schema);
    
    /**
     * Magic method get.
     *
     * Alias of get()
     */
    public function __get($key);
    
    /**
     * Resolve all dependencies and return the 
     * configured object
     *
     * @param   String  $key
     * @return  mixed
     */
    public function getService($key);
}
?>
