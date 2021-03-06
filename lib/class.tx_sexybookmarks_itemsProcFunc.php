<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009 Juergen Furrer <juergen.furrer@gmail.com>
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


/**
 * 'itemsProcFunc' for the 'sexybookmarks' extension.
 *
 * @author     Juergen Furrer <juergen.furrer@gmail.com>
 * @package    TYPO3
 * @subpackage tx_sexybookmarks
 */
class tx_sexybookmarks_itemsProcFunc
{
	/**
	 * Get all bookmarks for drupdown
	 * @return array
	 */
	public function getBookmarks($config, $item)
	{
		$setup = $this->loadTS($config['row']['pid']);
		$bookmarks = $setup['plugin.']['tx_sexybookmarks_pi1.']['bookmarkConf.'];
		$optionList = array();
		if (count($bookmarks) > 0) {
			foreach ($bookmarks as $key => $bookmark) {
				if (preg_match("/(.*)\.$/", $key, $preg)) {
					$optionList[] = array(
						trim($preg[1]),
						trim($preg[1])
					);
				}
			}
		}
		sort($optionList);
		$config['items'] = array_merge($config['items'], $optionList);
		return $config;
	}

	/**
	 * Get all follow bookmarks for drupdown
	 * @return array
	 */
	public function getFollows($config, $item)
	{
		$setup = $this->loadTS($config['row']['pid']);
		$bookmarks = $setup['plugin.']['tx_sexybookmarks_pi2.']['bookmarkConf.'];
		$optionList = array();
		if (count($bookmarks) > 0) {
			foreach ($bookmarks as $key => $bookmark) {
				if (preg_match("/(.*)\.$/", $key, $preg)) {
					$optionList[] = array(
						trim($preg[1]),
						trim($preg[1])
					);
				}
			}
		}
		sort($optionList);
		$config['items'] = array_merge($config['items'], $optionList);
		return $config;
	}

	/**
	 * Get all background classes for dropdown
	 * @return array
	 */
	public function getBackgroundClasses($config, $item)
	{
		$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sexybookmarks']);
		$items = t3lib_div::trimExplode(",", $confArr['backgroundClasses']);
		if (count($items) < 1) {
			$items = array('', 'sexy', 'caring', 'caring-old', 'love', 'wealth', 'enjoy', 'german');
		}
		$optionList = array();
		foreach ($items as $item) {
			$optionList[] = array(
				trim($item),
				trim($item)
			);
		}
		$config['items'] = array_merge($config['items'], $optionList);
		return $config;
	}

	/**
	 * Get the setup of the curront page
	 * @param integer $pageUid
	 * @return void
	 */
	private function loadTS($pageUid) {
		$sysPageObj = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $sysPageObj->getRootLine($pageUid);
		$TSObj = t3lib_div::makeInstance('t3lib_tsparser_ext');
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();
		return $TSObj->setup;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sexybookmarks/lib/class.tx_sexybookmarks_itemsProcFunc.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sexybookmarks/lib/class.tx_sexybookmarks_itemsProcFunc.php']);
}
?>