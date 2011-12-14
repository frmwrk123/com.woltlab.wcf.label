<?php
namespace wcf\data\label;
use wcf\data\DatabaseObjectEditor;

/**
 * Extends the label object with functions to create, update and delete labels.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	data.label
 * @category 	Community Framework
 */
class LabelEditor extends DatabaseObjectEditor {
	/**
	 * @see	wcf\data\DatabaseObjectEditor::$baseClass
	 */
	protected static $baseClass = 'wcf\data\label\Label';
}
