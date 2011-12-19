<?php
namespace wcf\acp\form;
use wcf\data\label\group\LabelGroupAction;
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
	 * @see wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['groupName'])) $this->groupName = StringUtil::trim($_POST['groupName']);
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
		$groupAction = new LabelGroupAction(array(), 'create', array('data' => array(
			'groupName' => $this->groupName
		)));
		$groupAction->executeAction();
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
			'groupName' => $this->groupName
		));
	}
}
