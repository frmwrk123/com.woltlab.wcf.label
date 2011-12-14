<?php
namespace wcf\system\label;
use wcf\data\label\LabelList;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\database\util\PreparedStatementConditionBuilder;
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
	 * list of labels
	 * @var	wcf\data\label\LabelList
	 */
	protected $labels = null;
	
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
	 * Returns a label list.
	 * 
	 * @return	wcf\data\label\LabelList
	 */
	public function getLabels() {
		if ($this->labels === null) {
			$this->labels = new LabelList();
			$this->labels->getConditionBuilder()->add("label.objectTypeID IN (?)", array(array_keys($this->cache['objectTypes'])));
			$this->labels->sqlLimit = 0;
			$this->labels->readObjects();
		}
		
		return $this->labels;
	}
	
	/**
	 * Returns a list of assigned labels.
	 * 
	 * @param	integer		$objectTypeID
	 * @param	array<integer>	$objectIDs
	 * @return	array<array>
	 */
	public function getAssignedLabels($objectTypeID, array $objectIDs) {
		$data = array();
		
		$conditions = new PreparedStatementConditionBuilder();
		$conditions->add("objectTypeID = ?", array($objectTypeID));
		$conditions->add("objectID IN (?)", array($objectIDs));
		
		$sql = "SELECT	labelID, objectID
			FROM	wcf".WCF_N."_label_object
			".$conditions;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditions->getConditions());
		
		$labelIDs = array();
		while ($row = $statement->fetchArray()) {
			if (!isset($labelIDs[$row['objectID']])) {
				$labelIDs[$row['objectID']] = array();
			}
			
			$labelIDs[$row['objectID']][] = $row['labelID'];
		}
		
		if (!empty($labelIDs)) {
			$labels = $this->getLabels();
			$labels = $labels->getObjects();
			
			foreach ($labelIDs as $objectID => $objectLabelIDs) {
				foreach ($objectLabelIDs as $objectLabelID) {
					if (isset($labels[$objectLabelID])) {
						if (!isset($data[$objectID])) {
							$data[$objectID] = array();
						}
						
						$data[$objectID][$objectLabelID] = $labels[$objectLabelID];
					}
				}
			}
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