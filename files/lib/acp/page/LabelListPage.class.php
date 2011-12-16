<?php
namespace wcf\acp\page;
use wcf\page\MultipleLinkPage;
use wcf\system\menu\acp\ACPMenu;

/**
 * Lists available labels
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	acp.page
 * @category 	Community Framework
 */
class LabelListPage extends MultipleLinkPage {
	/**
	 * @see wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.content.label.canEditLabel', 'admin.content.label.canDeleteLabel');
	
	/**
	 * @see	wcf\page\MultipleLinkPage::$objectListClassName
	 */
	public $objectListClassName = 'wcf\data\label\LabelList';
	
	/**
	 * @see wcf\page\IPage::show()
	 */
	public function show() {
		// set active menu item.
		ACPMenu::getInstance()->setActiveMenuItem('wcf.acp.menu.link.label.list');
		
		parent::show();
	}
}
