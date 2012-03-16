<?php
namespace wcf\system\label\object\type;

/**
 * Default interface for label object type handler.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	system.label.object.type
 * @category 	Community Framework
 */
interface ILabelObjectTypeHandler {
	/**
	 * Sets object type id.
	 * 
	 * @param	integer		$objectTypeID
	 */
	public function setObjectTypeID($objectTypeID);
	
	/**
	 * Returns object type id.
	 * 
	 * @return	integer
	 */
	public function getObjectTypeID();
	
	/**
	 * Returns a label object type container.
	 * 
	 * @return	wcf\system\label\object\type\LabelObjectTypeContainer
	 */
	public function getContainer();
	
	/**
	 * Performs save actions.
	 */
	public function saveObjects();
}