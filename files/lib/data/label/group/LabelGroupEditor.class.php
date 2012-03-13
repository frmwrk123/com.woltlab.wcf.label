<?php
namespace wcf\data\label\group;
use wcf\data\DatabaseObjectEditor;
use wcf\data\IEditableCachedObject;
use wcf\system\acl\ACLHandler;
use wcf\system\cache\CacheHandler;

/**
 * Extends the label group object with functions to create, update and delete label groups.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	data.label.group
 * @category 	Community Framework
 */
class LabelGroupEditor extends DatabaseObjectEditor implements IEditableCachedObject {
	/**
	 * @see	wcf\data\DatabaseObjectEditor::$baseClass
	 */
	protected static $baseClass = 'wcf\data\label\group\LabelGroup';
	
	/**
	 * @see wcf\data\IEditableObject::deleteAll()
	 */
	public static function deleteAll(array $objectIDs = array()) {
		$count = parent::deleteAll($objectIDs);
		
		// remove ACL values
		$objectTypeID = ACLHandler::getInstance()->getObjectTypeID('com.woltlab.wcf.label');
		ACLHandler::getInstance()->removeValues($objectTypeID, $objectIDs);
		
		return $count;
	}
	
	/**
	 * @see	wcf\data\IEditableCachedObject::resetCache()
	 */
	public static function resetCache() {
		CacheHandler::getInstance()->clear(WCF_DIR.'cache/', 'cache.label.php');
	}
}
