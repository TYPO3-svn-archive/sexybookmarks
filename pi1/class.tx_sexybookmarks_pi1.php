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

require_once(t3lib_extMgm::extPath('sexybookmarks').'lib/class.tx_sexybookmarks_pagerenderer.php');

/**
 * Plugin 'Sexy-Bookmarks' for the 'sexybookmarks' extension.
 *
 * @author	Juergen Furrer <juergen.furrer@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_sexybookmarks
 */
class tx_sexybookmarks_pi1 extends tslib_pibase
{
	public $prefixId      = 'tx_sexybookmarks_pi1';
	public $scriptRelPath = 'pi1/class.tx_sexybookmarks_pi1.php';
	public $extKey        = 'sexybookmarks';
	public $pi_checkCHash = true;
	protected $contentKey = null;
	protected $jsFiles = array();
	protected $js = array();
	protected $cssFiles = array();
	protected $css = array();
	protected $templateFileJS = null;
	protected $piFlexForm = array();

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

		if ($this->cObj->data['list_type'] == $this->extKey.'_pi1') {
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

	/**
	 * Set the contentKey
	 * @param string $contentKey
	 */
	public function setContentKey($contentKey=null)
	{
		$this->contentKey = ($contentKey == null ? $this->extKey : $contentKey);
	}

	/**
	 * Get the contentKey
	 * @return string
	 */
	public function getContentKey()
	{
		return $this->contentKey;
	}

	/**
	 * Set the cObj
	 * @param tslib_cObj $cObj
	 */
	public function setCObj(tslib_cObj $cObj)
	{
		$this->cObj = $cObj;
	}

		/**
	 * Parse all images into the template
	 * @param $data
	 * @return string
	 */
	public function parseTemplate()
	{
		$this->pagerenderer = t3lib_div::makeInstance('tx_sexybookmarks_pagerenderer');
		$this->pagerenderer->setConf($this->conf);

		// define the jQuery mode and function
		if ($this->conf['jQueryNoConflict']) {
			$jQueryNoConflict = "jQuery.noConflict();";
		} else {
			$jQueryNoConflict = "";
		}

		$animaion = array();
		$animaion[] = "duration:".($this->conf['transitionDuration'] ? $this->conf['transitionDuration'] : 500)."";
		$animaion[] = "queue:false";
		if ($this->conf['transition'] && $this->conf['transitionDir']) {
			$animaion[] = "easing:'ease{$this->conf['transitionDir']}{$this->conf['transition']}'";
		}

		// The template for JS
		if (! $this->templateFileJS = $this->cObj->fileResource($this->conf['templateFileJS'])) {
			$this->templateFileJS = $this->cObj->fileResource("EXT:sexybookmarks/res/tx_sexybookmarks.js");
		}
		// get the Template of the Javascript
		if (! $templateCode = trim($this->cObj->getSubpart($this->templateFileJS, "###TEMPLATE_JS###"))) {
			$templateCode = "alert('Template TEMPLATE_JS is missing')";
		}
		// set the key
		$markerArray = array();
		$markerArray["KEY"] = $this->getContentKey();
		$markerArray["ANIMATION"] = implode(",", $animaion);
		// set the markers
		$templateCode = $this->cObj->substituteMarkerArray($templateCode, $markerArray, '###|###', 0);

		$this->pagerenderer->addJS($jQueryNoConflict . $templateCode);

		// add the JS file
		$this->pagerenderer->addJsFile($this->conf['jsFile']);

		// add the CSS file
		$this->pagerenderer->addCssFile($this->conf['cssFile']);

		// checks if t3jquery is loaded
		if (T3JQUERY === true) {
			tx_t3jquery::addJqJS();
		} else {
			$this->pagerenderer->addJsFile($this->conf['jQueryLibrary'], true);
			$this->pagerenderer->addJsFile($this->conf['jQueryEasing']);
		}

		// Add the ressources
		$this->pagerenderer->addResources();

		$classes = array();
		$classes[] = "sexybookmarks";
		if ($this->conf['bookmarkCenter']) {
			$classes[] = "sexybookmarks-center";
		}
		if ($this->conf['bookmarkExpandable']) {
			$classes[] = "sexybookmarks-expand";
		}
		if ($this->conf['bookmarkBackground']) {
			$classes[] = "sexybookmarks-bg-".$this->conf['bookmarkBackground'];
		}

		$bookmarkContent = null;
		if (isset($this->conf['bookmarks'])) {
			$bookmarkArray = array();
			// get used bookmarks
			$bookmarks = t3lib_div::trimExplode(',', $this->conf['bookmarks']);
			// get used bookmarks configuration
			if(count($bookmarks) > 0) {
				foreach( $bookmarks as $bookmark) {
					$bookmarkItem = $this->cObj->cObjGetSingle($this->conf['bookmarkConf.'][$bookmark], $this->conf['bookmarkConf.'][$bookmark.'.']);
					// fix the xhtml
					if ($GLOBALS['TSFE']->xhtmlVersion > 0 || $GLOBALS['TSFE']->config['config']['doctype'] == 'html5') {
						$bookmarkContent .= preg_replace("/&(?!(?i:\#((x([\dA-F]){1,5})|(104857[0-5]|10485[0-6]\d|1048[0-4]\d\d|104[0-7]\d{3}|10[0-3]\d{4}|0?\d{1,6}))|([A-Za-z\d.]{2,31}));)/", "&amp;", $bookmarkItem);
					} else {
						$bookmarkContent .= $bookmarkItem;
					}
				}
			}
		}

		$GLOBALS['TSFE']->register['classes'] = implode(" ", $classes);
		$GLOBALS['TSFE']->register['key']     = $this->getContentKey();

		$version = class_exists('t3lib_utility_VersionNumber')
			? t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version)
			: t3lib_div::int_from_ver(TYPO3_version);

		if ($version >= 6000000) {
			return $bookmarkContent;
		} else {
			return $this->cObj->stdWrap($bookmarkContent, $this->conf['stdWrap.']);
		}
	}

	/**
	* Set the piFlexform data
	*
	* @return void
	*/
	protected function setFlexFormData()
	{
		if (! count($this->piFlexForm)) {
			$this->pi_initPIflexForm();
			$this->piFlexForm = $this->cObj->data['pi_flexform'];
		}
	}

	/**
	 * Extract the requested information from flexform
	 * @param string $sheet
	 * @param string $name
	 * @param boolean $devlog
	 * @return string
	 */
	protected function getFlexformData($sheet='', $name='', $devlog=true)
	{
		$this->setFlexFormData();
		if (! isset($this->piFlexForm['data'])) {
			if ($devlog === true) {
				t3lib_div::devLog("Flexform Data not set", $this->extKey, 1);
			}
			return null;
		}
		if (! isset($this->piFlexForm['data'][$sheet])) {
			if ($devlog === true) {
				t3lib_div::devLog("Flexform sheet '{$sheet}' not defined", $this->extKey, 1);
			}
			return null;
		}
		if (! isset($this->piFlexForm['data'][$sheet]['lDEF'][$name])) {
			if ($devlog === true) {
				t3lib_div::devLog("Flexform Data [{$sheet}][{$name}] does not exist", $this->extKey, 1);
			}
			return null;
		}
		if (isset($this->piFlexForm['data'][$sheet]['lDEF'][$name]['vDEF'])) {
			return $this->pi_getFFvalue($this->piFlexForm, $name, $sheet);
		} else {
			return $this->piFlexForm['data'][$sheet]['lDEF'][$name];
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sexybookmarks/pi1/class.tx_sexybookmarks_pi1.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sexybookmarks/pi1/class.tx_sexybookmarks_pi1.php']);
}

?>