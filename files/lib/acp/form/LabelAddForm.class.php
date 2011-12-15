<?php
namespace wcf\acp\form;
use wcf\data\label\LabelAction;
use wcf\system\exception\UserInputException;
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
	 * label value
	 * @var	string
	 */
	public $label = '';
	
	/**
	 * CSS class name
	 * @var	string
	 */
	public $cssClassName = '';
	
	/**
	 * @see wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['label'])) $this->label = StringUtil::trim($_POST['label']);
		if (isset($_POST['cssClassName'])) $this->cssClassName = StringUtil::trim($_POST['cssClassName']);
	}
	
	/**
	 * @see wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		// validate class name
		if (empty($this->label)) {
			throw new UserInputException('label');
		}
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		// save label
		$labelAction = new LabelAction(array(), 'create', array('data' => array(
			'label' => $this->label,
			'cssClassName' => $this->cssClassName
		)));
		$labelAction->executeAction();
		$this->saved();
		
		// reset values
		$this->label = $this->cssClassName = '';
		
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
			'cssClassName' => $this->cssClassName,
			'label' => $this->label
		));
	}
}
