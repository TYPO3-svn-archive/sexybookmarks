<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_sexybookmarks_pi1.php', '_pi1', 'list_type', 1);
t3lib_extMgm::addPItoST43($_EXTKEY, 'pi2/class.tx_sexybookmarks_pi2.php', '_pi2', 'list_type', 1);

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3blog']['getWidgets'][$_EXTKEY.'_1'] = 'EXT:'.$_EXTKEY.'/widgets/class.tx_sexybookmarks_getwidgets.php:tx_sexybookmarks_getwidgets->getWidgets';

?>