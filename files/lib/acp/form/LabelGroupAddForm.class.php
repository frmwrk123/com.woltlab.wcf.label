<?php
namespace wcf\acp\form;
use wcf\data\label\group\LabelGroupAction;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\acl\ACLHandler;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows the label group add form.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	acp.form
 * @category 	Community Framework
 */
class LabelGroupAddForm extends ACPForm {
	/**
	 * @see wcf\page\AbstractPage::$templateName
	 */
	public $templateName = 'labelGroupAdd';
	
	/**
	 * @see wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.label.group.add';
	
	/**
	 * @see wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.content.label.canAddLabelGroup');
	
	/**
	 * group name
	 * @var	string
	 */
	public $groupName = '';
	
	/**
	 * list of label object type handlers
	 * @var	array<wcf\system\label\object\type\ILabelObjectTypeHandler>
	 */
	public $labelObjectTypes = array();
	
	/**
	 * list of label object type containers
	 * @var	array<wcf\system\label\object\type\LabelObjectTypeContainer>
	 */
	public $labelObjectTypeContainers = array();
	
	/**
	 * object type id
	 * @var	integer
	 */
	public $objectTypeID = 0;
	
	/**
	 * @see	wcf\page\AbstractPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		$this->objectTypeID = ACLHandler::getInstance()->getObjectTypeID('com.woltlab.wcf.label');
		
		// get label object type handlers
		$objectTypes = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.label.objectType');
		foreach ($objectTypes as $objectType) {
			$this->labelObjectTypes[] = $objectType->getProcessor();
		}
	}
	
	/**
	 * @see wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['groupName'])) $this->groupName = StringUtil::trim($_POST['groupName']);
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		foreach ($this->labelObjectTypes as $labelObjectType) {
			$this->labelObjectTypeContainers[] = $labelObjectType->getObjects();
		}
	}
	
	/**
	 * @see wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		// validate class name
		if (empty($this->groupName)) {
			throw new UserInputException('groupName');
		}
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		// save label
		$this->objectAction = new LabelGroupAction(array(), 'create', array('data' => array(
			'groupName' => $this->groupName
		)));
		$returnValues = $this->objectAction->executeAction();
				
		// save acl
		ACLHandler::getInstance()->save($returnValues['returnValues']->groupID, $this->objectTypeID);
				
		$this->saved();
		
		// reset values
		$this->groupName = '';
		
		// show success
		WCF::getTPL()->assign(array(
			'success' => true
		));
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'action' => 'add',
			'groupName' => $this->groupName,
			'labelObjectTypeContainers' => $this->labelObjectTypeContainers,
			'objectTypeID' => $this->objectTypeID
		));
	}
}
