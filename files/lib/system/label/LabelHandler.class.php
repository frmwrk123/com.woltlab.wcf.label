<?php
namespace wcf\system\label;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\cache\CacheHandler;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\exception\SystemException;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Manages labels and label-to-object associations.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	system.label
 * @category 	Community Framework
 */
class LabelHandler extends SingletonFactory {
	/**
	 * cached list of object types
	 * @var	array<array>
	 */
	protected $cache = null;
	
	/**
	 * list of label groups
	 * @var	array<wcf\data\label\group\ViewableLabelGroup>
	 */
	protected $labelGroups = null;
	
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		$this->cache = array(
			'objectTypes' => array(),
			'objectTypeNames' => array()
		);
		
		$cache = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.label.object');
		foreach ($cache as $objectType) {
			$this->cache['objectTypes'][$objectType->objectTypeID] = $objectType;
			$this->cache['objectTypeNames'][$objectType->objectType] = $objectType->objectTypeID;
		}
		
		CacheHandler::getInstance()->addResource(
			'label',
			WCF_DIR.'cache/cache.label.php',
			'wcf\system\cache\builder\LabelCacheBuilder'
		);
		$this->labelGroups = CacheHandler::getInstance()->get('label');
	}
	
	/**
	 * Returns an ACL option id by option name.
	 * 
	 * @param	string		$optionName
	 * @return	integer
	 */
	public function getOptionID($optionName) {
		foreach ($this->labelGroups['options'] as $option) {
			if ($option->optionName == $optionName) {
				return $option->optionID;
			}
		}
		
		return null;
	}
	
	/**
	 * Returns an object type by name.
	 * 
	 * @param	string		$objectType
	 * @return	wcf\data\object\type\ObjectType
	 */
	public function getObjectType($objectType) {
		if (isset($this->cache['objectTypeNames'][$objectType])) {
			$objectTypeID = $this->cache['objectTypeNames'][$objectType];
			return $this->cache['objectTypes'][$objectTypeID];
		}
		
		return null;
	}
	
	/**
	 * Returns an array with view permissions for each label id.
	 * 
	 * @param	array<integer>	$labelIDs
	 * @return	array
	 * @see		wcf\system\label\LabelHandler::getPermissions()
	 */
	public function validateCanView(array $labelIDs) {
		return $this->getPermissions('canViewLabel', $labelIDs);
	}
	
	/**
	 * Returns an array with use permissions for each label id.
	 *
	 * @param	array<integer>	$labelIDs
	 * @return	array
	 * @see		wcf\system\label\LabelHandler::getPermissions()
	 */
	public function validateCanUse(array $labelIDs) {
		return $this->getPermissions('canUseLabel', $labelIDs);
	}
	
	/**
	 * Returns an array with boolean values for each given label id.
	 * 
	 * @param	string		$optionName
	 * @param	array<integer>	$labelIDs
	 * @return	array
	 */
	public function getPermissions($optionName, array $labelIDs) {
		if (empty($this->labelGroups['groups'])) {
			throw new SystemException("cannot validate label ids, missing label groups");
		}
		
		$optionID = $this->getOptionID($optionName);
		if ($optionID === null) {
			throw new SystemException("cannot validate label ids, ACL options missing");
		}
		
		// validate each label
		$data = array();
		foreach ($labelIDs as $labelID) {
			$isValid = false;
			
			foreach ($this->labelGroups['groups'] as $group) {
				if (!$group->isValid($labelID)) {
					continue;
				}
				
				if ($group->getPermission($optionID, $labelID)) {
					$isValid = true;
				}
			}
			
			$data[$labelID] = $isValid;
		}
		
		return $data;
	}
	
	/**
	 * Sets labels for given object id, pass an empty array to remove
	 * all previously assigned labels.
	 * 
	 * @param	array<integer>	$labelIDs
	 * @param	integer		$objectTypeID
	 * @param	integer		$objectID
	 */
	public function setLabel(array $labelIDs, $objectTypeID, $objectID) {
		// delete previous labels
		$sql = "DELETE FROM	wcf".WCF_N."_label_object
			WHERE		objectTypeID = ?
					AND objectID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			$objectTypeID,
			$objectID
		));
		
		// insert new labels
		if (!empty($labelIDs)) {
			$sql = "INSERT INTO	wcf".WCF_N."_label_object
						(labelID, objectTypeID, objectID)
				VALUES		(?, ?, ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			foreach ($labelIDs as $labelID) {
				$statement->execute(array(
					$labelID,
					$objectTypeID,
					$objectID
				));
			}
		}
	}
	
	/**
	 * Returns all assigned labels, optionally filtered to validate permissions.
	 * 
	 * @param	integer		$objectTypeID
	 * @param	array<integer>	$objectIds
	 * @param	boolean		$validatePermissions
	 * @return	array<array>
	 */
	public function getAssignedLabels($objectTypeID, array $objectIDs, $validatePermissions = true) {
		$conditions = new PreparedStatementConditionBuilder();
		$conditions->add("objectTypeID = ?", array($objectTypeID));
		$conditions->add("objectID IN (?)", array($objectIDs));
		$sql = "SELECT	objectID, labelID
			FROM	wcf".WCF_N."_label_to_object
			".$conditions;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditions->getParameters());
		
		$labels = array();
		while ($row = $statement->fetchArray()) {
			if (!isset($labels[$row['labelID']])) {
				$labels[$row['labelID']] = array();
			}
			
			$labels[$row['labelID']][] = $row['objectID'];
		}
		
		// optionally filter out labels without permissions
		if ($validatePermissions) {
			$labelIDs = array_keys($labels);
			$result = $this->validateCanView($labelIDs);
			
			foreach ($labelIDs as $labelID) {
				if (!$result[$labelID]) {
					unset($labels[$labelID]);
				}
			}
		}
		
		// reorder the array by object id
		$data = array();
		foreach ($labels as $labelID => $objectIDs) {
			foreach ($objectIDs as $objectID) {
				if (!isset($data[$objectID])) {
					$data[$objectID] = array();
				}
				
				$data[$objectID][] = $labelID;
			}
		}
		
		return $data;
	}
	
	/**
	 * Removes all previously assigned labels.
	 * 
	 * @param	integer		$objectTypeID
	 * @param	integer		$objectID
	 * @see		wcf\system\label\LabelHandler::setLabel()
	 */
	public function removeLabels($objectTypeID, $objectID) {
		$this->setLabel(array(), $objectTypeID, $objectID);
	}
}