<?php
namespace wcf\acp\form;
use wcf\data\label\group\LabelGroup;
use wcf\data\label\group\LabelGroupAction;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * Shows the label group edit form.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	acp.form
 * @category 	Community Framework
 */
class LabelGroupEditForm extends LabelGroupAddForm {
	/**
	 * @see wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.label.group';
	
	/**
	 * @see wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.content.label.canEditLabelGroup');
	
	/**
	 * group id
	 * @var	integer
	 */
	public $groupID = 0;
	
	/**
	 * label group object
	 * @var	wcf\data\label\group\LabelGroup
	 */
	public $group = null;
	
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->groupID = intval($_REQUEST['id']);
		$this->group = new LabelGroup($this->groupID);
		if (!$this->group->groupID) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		ACPForm::save();
		
		// update label
		$groupAction = new LabelGroupAction(array($this->groupID), 'update', array('data' => array(
			'groupName' => $this->groupName
		)));
		$groupAction->executeAction();
		
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
			$this->groupName = $this->group->groupName;
		}
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'groupID' => $this->groupID,
			'action' => 'edit'
		));
	}
}
