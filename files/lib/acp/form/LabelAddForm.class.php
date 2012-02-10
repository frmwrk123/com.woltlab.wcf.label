<?php
namespace wcf\acp\form;
use wcf\data\label\LabelAction;
use wcf\data\label\LabelEditor;
use wcf\data\label\group\LabelGroupList;
use wcf\system\exception\UserInputException;
use wcf\system\language\I18nHandler;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows the label add form.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	acp.form
 * @category 	Community Framework
 */
class LabelAddForm extends ACPForm {
	/**
	 * @see wcf\page\AbstractPage::$templateName
	 */
	public $templateName = 'labelAdd';
	
	/**
	 * @see wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.label.add';
	
	/**
	 * @see wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.content.label.canAddLabel');
	
	/**
	 * label group id
	 * @var	integer
	 */
	public $groupID = 0;
	
	/**
	 * label value
	 * @var	string
	 */
	public $label = '';
	
	/**
	 * label group list object
	 * @var	wcf\data\label\group\LabelGroupList
	 */
	public $labelGroupList = null;
	
	/**
	 * CSS class name
	 * @var	string
	 */
	public $cssClassName = '';
	
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		I18nHandler::getInstance()->register('label');
	}
	
	/**
	 * @see wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		I18nHandler::getInstance()->readValues();
		
		if (I18nHandler::getInstance()->isPlainValue('label')) $this->label = I18nHandler::getInstance()->getValue('label');
		if (isset($_POST['cssClassName'])) $this->cssClassName = StringUtil::trim($_POST['cssClassName']);
		if (isset($_POST['groupID'])) $this->groupID = intval($_POST['groupID']);
	}
	
	/**
	 * @see wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		// validate label
		try {
			if (!I18nHandler::getInstance()->validateValue('label')) {
				throw new UserInputException('label');
			}
		}
		catch (UserInputException $e) {
			$this->errorType[$e->getField()] = $e->getType();
		}
		
		// validate group
		if (!$this->groupID) {
			throw new UserInputException('groupID');
		}
		$groups = $this->labelGroupList->getObjects();
		if (!isset($groups[$this->groupID])) {
			throw new UserInputException('groupID', 'invalid');
		}
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		// save label
		$this->objectAction = new LabelAction(array(), 'create', array('data' => array(
			'label' => $this->label,
			'cssClassName' => $this->cssClassName,
			'groupID' => $this->groupID
		)));
		$this->objectAction->executeAction();
		
		if (!I18nHandler::getInstance()->isPlainValue('label')) {
			$returnValues = $this->objectAction->getReturnValues();
			$labelID = $returnValues['returnValues']->labelID;
			I18nHandler::getInstance()->save('label', 'wcf.acp.label.label'.$labelID, 'wcf.acp.label', PackageDependencyHandler::getPackageID('com.woltlab.wcf.label'));
			
			// update group name
			$labelEditor = new LabelEditor($returnValues['returnValues']);
			$labelEditor->update(array(
				'label' => 'wcf.acp.label.label'.$labelID
			));
		}
		
		$this->saved();
		
		// reset values
		$this->label = $this->cssClassName = '';
		$this->groupID = 0;
		I18nHandler::getInstance()->disableAssignValueVariables();
		
		// show success
		WCF::getTPL()->assign(array(
			'success' => true
		));
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		$this->labelGroupList = new LabelGroupList();
		$this->labelGroupList->sqlLimit = 0;
		$this->labelGroupList->readObjects();
		
		parent::readData();
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		I18nHandler::getInstance()->assignVariables();
		
		WCF::getTPL()->assign(array(
			'action' => 'add',
			'cssClassName' => $this->cssClassName,
			'groupID' => $this->groupID,
			'label' => $this->label,
			'labelGroupList' => $this->labelGroupList
		));
	}
}
