<?php
namespace wcf\data\label\group;
use wcf\data\acl\option\ACLOptionList;
use wcf\data\label\Label;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\WCF;

/**
 * Represents a viewable label group.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	data.label.group
 * @category 	Community Framework
 */
class ViewableLabelGroup extends DatabaseObjectDecorator {
	/**
	 * @see	wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wcf\data\label\group\LabelGroup';
	
	/**
	 * list of labels
	 * @var	array<wcf\data\label\Label>
	 */
	protected $labels = array();
	
	/**
	 * list of permissions by type
	 * @var	array<array>
	 */
	protected $permissions = array(
		'group' => array(),
		'user' => array()
	);
	
	/**
	 * Adds a label.
	 * 
	 * @param	wcf\data\label\Label	$label
	 */
	public function addLabel(Label $label) {
		$this->labels[$label->labelID] = $label;
	}
	
	/**
	 * Sets group permissions.
	 * 
	 * @param	array		$permissions
	 */
	public function setGroupPermissions(array $permissions) {
		$this->permissions['group'] = $permissions;
	}
	
	/**
	 * Sets user permissions.
	 *
	 * @param	array		$permissions
	 */
	public function setUserPermissions(array $permissions) {
		$this->permissions['user'] = $permissions;
	}
	
	/**
	 * Returns true, if label is known.
	 * 
	 * @param	integer		$labelID
	 * @return	boolean
	 */
	public function isValid($labelID) {
		return isset($this->labels[$labelID]);
	}
	
	/**
	 * Returns true, if current user fulfils option id permissions for given label id.
	 * 
	 * @param	integer		$optionID
	 * @param	integer		$labelID
	 * @return	boolean
	 */
	public function getPermission($optionID, $labelID) {
		// validate by user id
		if (WCF::getUser()->userID) {
			if (isset($this->permissions['user'][$labelID])) {
				if ($this->permissions['user'][$labelID] == 1) {
					return true;
				}
			}
		}
		
		// validate by group id
		$groupIDs = WCF::getUser()->getGroupIDs();
		foreach ($groupIDs as $groupID) {
			if (isset($this->permissions['group'][$groupID])) {
				if ($this->permissions['group'][$groupID] == 1) {
					return true;
				}
			}
		}
		
		return false;
	}
}