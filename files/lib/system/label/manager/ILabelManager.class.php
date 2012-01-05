<?php
namespace wcf\system\label\manager;

/**
 * Default interface for label manager.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.label
 * @subpackage	system.label.manager
 * @category 	Community Framework
 */
interface ILabelManager {
	/**
	 * Returns a list of label group ids.
	 * 
	 * @param	array		$parameters
	 * @return	array<integer>
	 */
	public function getLabelGroupIDs(array $parameters = array());
	
	/**
	 * Returns a list of label groups.
	 * 
	 * @param	array		$parameters
	 * @return	array<wcf\data\label\group\ViewableLabelGroup>
	 */
	public function getLabelGroups(array $parameters = array());
	
	/**
	 * Returns true, if all given label ids are valid and accessible.
	 * 
	 * @param	array<integer>	$labelIDs
	 * @param	array		$parameters
	 * @return	boolean
	 */
	public function validateLabelIDs(array $labelIDs, array $parameters = array());
}