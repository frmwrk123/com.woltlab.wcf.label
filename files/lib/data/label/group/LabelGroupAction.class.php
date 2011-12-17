<?php
namespace wcf\data\label\group;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * Executes label group-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	data.label.group
 * @category 	Community Framework
 */
class LabelGroupAction extends AbstractDatabaseObjectAction {
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::$className
	 */
	protected $className = 'wcf\data\label\group\LabelGroupEditor';
}
