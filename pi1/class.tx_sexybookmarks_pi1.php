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
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');

if (t3lib_extMgm::isLoaded('t3jquery')) {
	require_once(t3lib_extMgm::extPath('t3jquery').'class.tx_t3jquery.php');
}

/**
 * Plugin 'Sexy-Bookmarks' for the 'sexybookmarks' extension.
 *
 * @author	Juergen Furrer <juergen.furrer@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_sexybookmarks
 */
class tx_sexybookmarks_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_sexybookmarks_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_sexybookmarks_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'sexybookmarks';	// The extension key.
	var $pi_checkCHash = true;
	var $contentKey = null;
	var $jsFiles = array();
	var $js = array();
	var $cssFiles = array();
	var $css = array();

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		// define the key of the element
		$this->contentKey = "sexybookmarks";

		if ($this->cObj->data['list_type'] == $this->extKey.'_pi1') {
			// content
			// Set the Flexform information
			$this->pi_initPIflexForm();
			$piFlexForm = $this->cObj->data['pi_flexform'];
			foreach ($piFlexForm['data'] as $sheet => $data) {
				foreach ($data as $lang => $value) {
					foreach ($value as $key => $val) {
						$this->lConf[$key] = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
					}
				}
			}

			// define the key of the element
			$this->contentKey .= "c" . $this->cObj->data['uid'];

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
	 * Parse all images into the template
	 * @param $data
	 * @return string
	 */
	function parseTemplate()
	{
		// define the contentKey if not exist
		if ($this->contentKey == '') {
			$this->contentKey = "sexybookmarks_key";
		}

		$animaion = array();
		$animaion[] = "duration:".($this->conf['transitionDuration'] ? $this->conf['transitionDuration'] : 500)."";
		$animaion[] = "queue:false";
		if ($this->conf['transition'] && $this->conf['transitionDir']) {
			$animaion[] = "easing:'ease{$this->conf['transitionDir']}{$this->conf['transition']}'";
		}
		// Add the animation settings
		$this->addJS("
jQuery(document).ready(function() {
	var sexyBaseHeight = jQuery('#{$this->contentKey}.sexybookmarks').height();
	var sexyFullHeight = jQuery('#{$this->contentKey}.sexybookmarks ul.socials').height();
	if (sexyFullHeight>sexyBaseHeight) {
		jQuery('#{$this->contentKey}.sexybookmarks-expand').hover(
			function() {
				jQuery(this).animate({
					height: sexyFullHeight+'px'
				}, {".implode(",", $animaion)."});
			},
			function() {
				jQuery(this).animate({
					height: sexyBaseHeight+'px'
				}, {".implode(",", $animaion)."});
			}
		);
	}
});");

		// add the JS file
		$this->addJsFile($this->conf['jsFile']);

		// add the CSS file
		$this->addCssFile($this->conf['cssFile']);

		// Add the ressources
		$this->addResources();

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
					$bookmarkContent .= $this->cObj->cObjGetSingle($this->conf['bookmarkConf.'][$bookmark], $this->conf['bookmarkConf.'][$bookmark.'.']);
				}
			}
		}

		$GLOBALS['TSFE']->register['classes'] = implode(" ", $classes);
		$GLOBALS['TSFE']->register['key']     = $this->contentKey;

		$return_string = $this->cObj->stdWrap($bookmarkContent, $this->conf['stdWrap.']);

		return $return_string;
	}


	/**
	 * Include all defined resources (JS / CSS)
	 *
	 * @return void
	 */
	function addResources()
	{
		// checks if t3jquery is loaded
		if (T3JQUERY === true) {
			tx_t3jquery::addJqJS();
		} else {
			$this->addJsFile($this->conf['jQueryLibrary'], true);
			$this->addJsFile($this->conf['jQueryEasing']);
		}
		// add all defined JS files
		if (count($this->jsFiles) > 0) {
			foreach ($this->jsFiles as $jsToLoad) {
				if (T3JQUERY === true) {
					tx_t3jquery::addJS('', array('jsfile' => $this->getPath($jsToLoad)));
				} else {
					// Add script only once
					if (! preg_match("/".preg_quote($this->getPath($jsToLoad), "/")."/", $GLOBALS['TSFE']->additionalHeaderData['jsFile_'.$this->extKey])) {
						$GLOBALS['TSFE']->additionalHeaderData['jsFile_'.$this->extKey] .= ($this->getPath($jsToLoad) ? '<script src="'.$this->getPath($jsToLoad).'" type="text/javascript"></script>'.chr(10) :'');
					}
				}
			}
		}
		// add all defined JS Script
		if (count($this->js) > 0) {
			foreach ($this->js as $jsToPut) {
				$temp_js .= $jsToPut;
			}
			if ($this->conf['jsMinify']) {
				$temp_js = t3lib_div::minifyJavaScript($temp_js);
			}
			$conf = array();
			$conf['jsdata'] = $temp_js;
			if (T3JQUERY === true && t3lib_div::int_from_ver(t3lib_extMgm::getExtensionVersion('t3jquery')) >= 1002000) {
				if ($this->conf['jsInFooter']) {
					$conf['tofooter'] = true;
					tx_t3jquery::addJS('', $conf);
				} else {
					$conf['tofooter'] = false;
					tx_t3jquery::addJS('', $conf);
				}
			} else {
				if ($this->conf['jsInFooter']) {
					$GLOBALS['TSFE']->additionalFooterData['js_'.$this->extKey] .= t3lib_div::wrapJS($temp_js, true);
				} else {
					$GLOBALS['TSFE']->additionalHeaderData['js_'.$this->extKey] .= t3lib_div::wrapJS($temp_js, true);
				}
			}
		}
		// add all defined CSS files
		if (count($this->cssFiles) > 0) {
			foreach ($this->cssFiles as $cssToLoad) {
				// Add script only once
				if (! preg_match("/".preg_quote($this->getPath($cssToLoad), "/")."/", $GLOBALS['TSFE']->additionalHeaderData['cssFile_'.$this->extKey])) {
					$GLOBALS['TSFE']->additionalHeaderData['cssFile_'.$this->extKey] .= ($this->getPath($cssToLoad) ? '<link rel="stylesheet" href="'.$this->getPath($cssToLoad).'" type="text/css" />'.chr(10) :'');
				}
			}
		}
		// add all defined CSS Script
		if (count($this->css) > 0) {
			foreach ($this->css as $cssToPut) {
				$temp_css .= $cssToPut;
			}
			$GLOBALS['TSFE']->additionalHeaderData['css_'.$this->extKey] .= '
<style type="text/css">
' . $temp_css . '
</style>';
		}
	}

	/**
	 * Return the webbased path
	 * 
	 * @param string $path
	 * return string
	 */
	function getPath($path="")
	{
		return $GLOBALS['TSFE']->tmpl->getFileName($path);
	}

	/**
	 * Add additional JS file
	 * 
	 * @param string $script
	 * @param boolean $first
	 * @return void
	 */
	function addJsFile($script="", $first=false)
	{
		$script = t3lib_div::fixWindowsFilePath($script);
		if ($this->getPath($script) && ! in_array($script, $this->jsFiles)) {
			if ($first === true) {
				$this->jsFiles = array_merge(array($script), $this->jsFiles);
			} else {
				$this->jsFiles[] = $script;
			}
		}
	}

	/**
	 * Add JS to header
	 * 
	 * @param string $script
	 * @return void
	 */
	function addJS($script="")
	{
		if (! in_array($script, $this->js)) {
			$this->js[] = $script;
		}
	}

	/**
	 * Add additional CSS file
	 * 
	 * @param string $script
	 * @return void
	 */
	function addCssFile($script="")
	{
		$script = t3lib_div::fixWindowsFilePath($script);
		if ($this->getPath($script) && ! in_array($script, $this->cssFiles)) {
			$this->cssFiles[] = $script;
		}
	}

	/**
	 * Add CSS to header
	 * 
	 * @param string $script
	 * @return void
	 */
	function addCSS($script="")
	{
		if (! in_array($script, $this->css)) {
			$this->css[] = $script;
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sexybookmarks/pi1/class.tx_sexybookmarks_pi1.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sexybookmarks/pi1/class.tx_sexybookmarks_pi1.php']);
}

?>