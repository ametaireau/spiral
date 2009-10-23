<?php

namespace Spiral\Framework\Persistence\ORM;

/**
 * Unit of work
 * 
 * This component is a unit of work as defined by Martin Fowler at {@link http://martinfowler.com/eaaCatalog/unitOfWork.html}.
 * 
 * The role of this component is to manage the status of objects stored in the {@link ObjectRepository}.
 * The status of an object can take 3 values :
 * 	- new
 * 	- dirty
 * 	- deleted
 * 
 * The unit of work is responsible of the transition from the high-level world to the storage engine world.
 * It is responsible of recensing only the needed operations that have to be communicated to the storage engine.
 * 
 * For example, if an object is added then deleted from the object repository, no operation have to be sent 
 * to the storage engine.
 * 
 * Moreover, the unit of work acts as a transaction process since it is possible to rollback or to commit all 
 * the registered operations to the storage engine depending on contextual choice.
 * 
 * @author		Frédéric Sureau <frederic.sureau@gmail.com>
 * @copyright	Frédéric Sureau 2009
 * @license		http://www.gnu.org/licenses/gpl.html GNU General Public License V3
 */
interface UnitOfWork
{
	/**
	 * Status constants
	 */
	const STATUS_NEW = 1;
	const STATUS_DIRTY = 2;
	const STATUS_DELETED = 3;
	
	/**
	 * Commit all operations to the storage engine
	 * 
	 * @return	void
	 */
	public function commit();
	
	/**
	 * Rollback all operations
	 * 
	 * @return	void
	 */
	public function rollback();
	
	/**
	 * Define the current status of an object
	 * 
	 * The status is one of the UnitOfWork::STATUS_* constants.
	 * 
	 * @param	object	$object		Object which status has to be set
	 * @param	int		$status		Status for the object
	 * 
	 * @return	void
	 */
	public function setStatus($object, $status);
}