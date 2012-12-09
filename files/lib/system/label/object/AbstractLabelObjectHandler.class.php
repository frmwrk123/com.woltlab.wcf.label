<?php
namespace wcf\system\label\object;
use wcf\system\exception\SystemException;
use wcf\system\label\LabelHandler;
use wcf\system\SingletonFactory;

/**
 * Abstract implementation of a label object handler.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	system.label.object
 * @category	Community Framework
 */
abstract class AbstractLabelObjectHandler extends SingletonFactory implements ILabelObjectHandler {
	/**
	 * list of available label groups
	 * @var	array<wcf\data\label\group\ViewableLabelGroup>
	 */
	protected $labelGroups = array();
	
	/**
	 * object type name
	 * @var	string
	 */
	protected $objectType = '';
	
	/**
	 * object type id
	 * @var	integer
	 */
	protected $objectTypeID = 0;
	
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		$this->labelGroups = LabelHandler::getInstance()->getLabelGroups();
		
		$objectType = LabelHandler::getInstance()->getObjectType($this->objectType);
		if ($objectType === null) {
			throw new SystemException("object type '".$this->objectType."' is invalid");
		}
		$this->objectTypeID = $objectType->objectTypeID;
	}
	
	/**
	 * @see	wcf\system\label\manager\ILabelManager::getLabelGroupIDs()
	 */
	public function getLabelGroupIDs(array $parameters = array()) {
		return array_keys($this->labelGroups);
	}
	
	/**
	 * @see	wcf\system\label\manager\ILabelManager::getLabelGroups()
	 */
	public function getLabelGroups(array $parameters = array()) {
		$groupIDs = $this->getLabelGroupIDs($parameters);
		
		$data = array();
		foreach ($groupIDs as $groupID) {
			$data[$groupID] = $this->labelGroups[$groupID];
		}
		
		return $data;
	}
	
	/**
	 * @see	wcf\system\label\manager\ILabelManager::validateLabelIDs()
	 */
	public function validateLabelIDs(array $labelIDs, array $parameters = array()) {
		$result = array();
		
		foreach ($labelIDs as $labelID) {
			$isValid = false;
			
			foreach ($this->labelGroups as $group) {
				if ($group->isValid($labelID)) {
					if (!isset($result[$group->groupID])) {
						$result[$group->groupID] = array();
					}
					
					$result[$group->groupID][] = $labelID;
					$isValid = true;
					
					break;
				}
			}
			
			// label id is invalid or not accessible
			if (!$isValid) {
				return false;
			}
		}
		
		// only one label per group allowed
		foreach ($result as $groupData) {
			if (count($groupData) > 1) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * @see	wcf\system\label\manager\ILabelManager::setLabels()
	 */
	public function setLabels(array $labelIDs, $objectID, $validatePermissions = true) {
		LabelHandler::getInstance()->setLabels($labelIDs, $this->objectTypeID, $objectID, $validatePermissions);
	}
	
	/**
	 * @see	wcf\system\label\manager\ILabelManager::removeLabels()
	 */
	public function removeLabels($objectID, $validatePermissions = true) {
		LabelHandler::getInstance()->removeLabels($this->objectTypeID, $objectID, $validatePermissions);
	}
	
	/**
	 * @see	wcf\system\label\manager\ILabelManager::getAssignedLabels()
	 */
	public function getAssignedLabels(array $objectIDs, $validatePermissions = true) {
		return LabelHandler::getInstance()->getAssignedLabels($this->objectTypeID, $objectIDs, $validatePermissions);
	}
}