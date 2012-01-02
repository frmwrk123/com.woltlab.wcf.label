<?php
namespace wcf\acp\form;
use wcf\data\label\Label;
use wcf\data\label\LabelAction;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * Shows the label edit form.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	acp.form
 * @category 	Community Framework
 */
class LabelEditForm extends LabelAddForm {
	/**
	 * @see wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.label';
	
	/**
	 * @see wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.content.label.canEditLabel');
	
	/**
	 * label id
	 * @var	integer
	 */
	public $labelID = 0;
	
	/**
	 * label object
	 * @var	wcf\data\label\Label
	 */
	public $labelObj = null;
	
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->labelID = intval($_REQUEST['id']);
		$this->labelObj = new Label($this->labelID);
		if (!$this->labelObj->labelID) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		ACPForm::save();
		
		// update label
		$labelAction = new LabelAction(array($this->labelID), 'update', array('data' => array(
			'label' => $this->label,
			'cssClassName' => $this->cssClassName,
			'groupID' => $this->groupID
		)));
		$labelAction->executeAction();
		
		$this->saved();
		
		// show success
		WCF::getTPL()->assign(array(
			'success' => true
		));
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->label = $this->labelObj->label;
			$this->cssClassName = $this->labelObj->cssClassName;
			$this->groupID = $this->labelObj->groupID;
		}
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'labelID' => $this->labelID,
			'action' => 'edit'
		));
	}
}
