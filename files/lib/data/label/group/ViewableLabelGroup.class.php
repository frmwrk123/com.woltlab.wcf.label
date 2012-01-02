<?php
namespace wcf\data\label\group;
use wcf\data\acl\option\ACLOptionList;
use wcf\data\label\Label;
use wcf\data\DatabaseObjectDecorator;

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
	 * list of options
	 * @var	<wcf\data\acl\option\ACLOption>
	 */
	protected $options = array();
	
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
	 * Sets ACL options.
	 * 
	 * @param	wcf\data\acl\option\ACLOptionList	$optionList
	 */
	public function setOptions(ACLOptionList $optionList) {
		$this->options = $optionList->getObjects();
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
}