<?php
namespace wcf\system\cache\builder;
use wcf\data\acl\option\ACLOptionAction;
use wcf\data\label\group\LabelGroupList;
use wcf\data\label\group\ViewableLabelGroup;
use wcf\data\label\LabelList;
use wcf\system\acl\ACLHandler;

/**
 * Caches labels and groups.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	system.cache.builder
 * @category 	Community Framework
 */
class LabelCacheBuilder implements ICacheBuilder {
	/**
	 * @see wcf\system\cache\ICacheBuilder::getData()
	 */
	public function getData(array $cacheResource) {
		$data = array(
			'options' => array(),
			'groups' => array()
		);
		
		// get label groups
		$groupList = new LabelGroupList();
		$groupList->sqlLimit = 0;
		$groupList->readObjects();
		foreach ($groupList as &$group) {
			$data['groups'][$group->groupID] = new ViewableLabelGroup($group);
		}
		unset($group);
		
		// get permissions for groups
		$permissions = ACLHandler::getInstance()->getPermissions(
			ACLHandler::getInstance()->getObjectTypeID('com.woltlab.wcf.label'),
			array_keys($data),
			false
		);
		
		// store options
		$data['options'] = $permissions['options']->getObjects();
		
		// assign permissions for each label group
		foreach ($data['groups'] as $groupID => $group) {
			// group permissions
			if (isset($permissions['group'][$groupID])) {
				$group->setGroupPermissions($permissions['group'][$groupID]);
			}
			
			// user permissions
			if (isset($permissions['user'][$groupID])) {
				$group->setUserPermissions($permissions['user'][$groupID]);
			}
		}
		
		// get labels
		$labelList = new LabelList();
		$labelList->sqlLimit = 0;
		$labelList->readObjects();
		foreach ($labelList as $label) {
			$data[$label->groupID]->addLabel($label);
		}
		
		return $data;
	}
}
