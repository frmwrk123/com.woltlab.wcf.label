<?php
namespace wcf\data\label;
use wcf\data\DatabaseObject;

/**
 * Represents a label.
 *
 * @author	Alexander Ebert
 * @copyright	2009-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	data.label
 * @category 	Community Framework
 */
class Label extends DatabaseObject {
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'label';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseIndexName
	 */
	protected static $databaseTableIndexName = 'labelID';
}
