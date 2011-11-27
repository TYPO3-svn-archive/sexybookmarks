<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Juergen Furrer <juergen.furrer@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
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

require_once(t3lib_extMgm::extPath('sexybookmarks').'pi1/class.tx_sexybookmarks_pi1.php');

/**
 * Plugin 'Sexy-Bookmarks' for the 'sexybookmarks' extension.
 *
 * @author	Juergen Furrer <juergen.furrer@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_sexybookmarks
 */
class tx_sexybookmarks_pi2 extends tx_sexybookmarks_pi1
{
	public $prefixId      = 'tx_sexybookmarks_pi2';
	public $scriptRelPath = 'pi2/class.tx_sexybookmarks_pi2.php';
	public $extKey        = 'sexybookmarks';
	public $pi_checkCHash = true;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	public function main($content, $conf)
	{
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		// define the key of the element
		if ($this->getContentKey() == null) {
			$this->setContentKey();
		}

		if ($this->cObj->data['list_type'] == $this->extKey.'_pi2') {
			// content
			$this->lConf['bookmarks'] = $this->getFlexformData('general', 'bookmarks');
			
			$this->lConf['bookmarkCenter']     = $this->getFlexformData('settings', 'bookmarkCenter');
			$this->lConf['bookmarkExpandable'] = $this->getFlexformData('settings', 'bookmarkExpandable');
			$this->lConf['bookmarkBackground'] = $this->getFlexformData('settings', 'bookmarkBackground');
			
			$this->lConf['transition']         = $this->getFlexformData('movement', 'transition');
			$this->lConf['transitionDir']      = $this->getFlexformData('movement', 'transitionDir');
			$this->lConf['transitionDuration'] = $this->getFlexformData('movement', 'transitionDuration');
			
			// define the key of the element
			$this->setContentKey('sexybookmarks_c' . $this->cObj->data['uid']);

			// If the bookmarks are not defined, the config bookmarks will be taken
			if ($this->lConf['bookmarks']) {
				$this->conf['bookmarks'] = $this->lConf['bookmarks'];
			}
			$this->conf['bookmarkCenter']     = $this->lConf['bookmarkCenter'];
			$this->conf['bookmarkExpandable'] = $this->lConf['bookmarkExpandable'];
			$this->conf['bookmarkBackground'] = $this->lConf['bookmarkBackground'];
			$this->conf['transition']         = $this->lConf['transition'];
			$this->conf['transitionDir']      = $this->lConf['transitionDir'];
			if (is_numeric($this->lConf['transitionDuration'])) {
				$this->conf['transitionDuration'] = $this->lConf['transitionDuration'];
			}
		}

		return $this->pi_wrapInBaseClass($this->parseTemplate());
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sexybookmarks/pi2/class.tx_sexybookmarks_pi2.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sexybookmarks/pi2/class.tx_sexybookmarks_pi2.php']);
}

?>