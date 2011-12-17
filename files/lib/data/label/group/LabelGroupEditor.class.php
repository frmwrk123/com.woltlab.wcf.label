<?php
namespace wcf\data\label\group;
use wcf\data\DatabaseObjectEditor;

/**
 * Extends the label group object with functions to create, update and delete label groups.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	data.label.group
 * @category 	Community Framework
 */
class LabelGroupEditor extends DatabaseObjectEditor {
	/**
	 * @see	wcf\data\DatabaseObjectEditor::$baseClass
	 */
	protected static $baseClass = 'wcf\data\label\group\LabelGroup';
}
