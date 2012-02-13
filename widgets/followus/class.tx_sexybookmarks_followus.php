<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Juergen Furrer (juergen.furrer@gmail.com)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
  * This is the widget class for t3blog. It embeds sexybookmarks followus to the page.
  *
  * @author Juergen Furrer <juergen.furrer@gmail.com>
  * @package TYPO3
  * @subpackage tx_sexybookmarks
  */

class tx_sexybookmarks_followus
{
	/**
	 * Produces the output.
	 *
	 * @param string $unused
	 * @param array $conf
	 * @param array $piVars
	 * @param tslib_cObj $cObj
	 */
	public function main($content, array $conf, $piVars, tslib_cObj $cObj)
	{
		require_once(t3lib_extMgm::extPath('sexybookmarks') . 'pi2/class.tx_sexybookmarks_pi2.php');
		$obj = t3lib_div::makeInstance('tx_sexybookmarks_pi2');
		$obj->setCObj($cObj);
		$html = $obj->main($content, $conf['config.']);

		return $html;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sexybookmarks/widgets/followus/class.tx_sexybookmarks_followus.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sexybookmarks/widgets/followus/class.tx_sexybookmarks_followus.php']);
}

?>