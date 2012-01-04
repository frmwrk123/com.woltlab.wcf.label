<?php
namespace wcf\system\label\manager;
use wcf\system\label\LabelHandler;
use wcf\system\SingletonFactory;

/**
 * Default implementation for label manager.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	system.label.manager
 * @category 	Community Framework
 */
abstract class AbstractLabelManager extends SingletonFactory implements ILabelManager {
	/**
	 * list of available label groups
	 * 
	 * @var	array<wcf\data\label\group\ViewableLabelGroup>
	 */
	protected $labelGroups = array();
	
	/**
	 * @see wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		$this->labelGroups = LabelHandler::getInstance()->getLabelGroups();
	}
	
	/**
	 * @see	wcf\system\label\manager\ILabelManager::getLabelGroupIDs()
	 */
	public function getLabelGroupIDs(array $parameters = array()) {
		return array_keys($this->labelGroups);
		
	}

	/**
	 * @see	wcf\system\label\manager\ILabelManager::validateLabelIDs()
	 */
	public function validateLabelIDs(array $labelIDs, array $parameters = array()) {
		$data = array();
		
		foreach ($labelIDs as $labelID) {
			foreach ($this->labelGroups as $group) {
				if ($group->isValid($labelID)) {
					$data[] = $labelID;
					break;
				}
			}
		}
		
		return $data;
	}
}