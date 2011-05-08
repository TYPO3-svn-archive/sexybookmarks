<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Static
t3lib_extMgm::addStaticFile($_EXTKEY, 'static/', 'Sexy-Bookmarks ShareThis');
t3lib_extMgm::addStaticFile($_EXTKEY, 'static/followus', 'Sexy-Bookmarks FollowUs');

// For the content plugin
t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1'] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1'] = 'pi_flexform';

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi2'] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi2'] = 'pi_flexform';



// ICON
t3lib_extMgm::addPlugin(array(
	'LLL:EXT:sexybookmarks/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'pi1/ce_icon.gif'
),'list_type');

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:sexybookmarks/locallang_db.xml:tt_content.list_type_pi2',
	$_EXTKEY . '_pi2',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'pi2/ce_icon.gif'
),'list_type');



t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/pi1/flexform_ds.xml');
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi2', 'FILE:EXT:'.$_EXTKEY.'/pi2/flexform_ds.xml');

if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_sexybookmarks_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_sexybookmarks_pi1_wizicon.php';
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_sexybookmarks_pi2_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi2/class.tx_sexybookmarks_pi2_wizicon.php';
}

require_once(t3lib_extMgm::extPath($_EXTKEY).'lib/class.tx_sexybookmarks_itemsProcFunc.php');
?>