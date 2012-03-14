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
	 * Returns a list of connectable objects.
	 * 
	 * @return	array<wcf\data\DatabaseObject>
	 */
	public function getObjects();
	
	/**
	 * Saves connected objects.
	 * 
	 * @param	array		$data
	 */
	public function saveObjects(array &$data);
}