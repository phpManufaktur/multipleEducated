<?php

/**
 * multipleEducated
 *
 * @author Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @link http://phpmanufaktur.de
 * @copyright 2009 - 2012
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {
  if (defined('LEPTON_VERSION'))
    include(WB_PATH.'/framework/class.secure.php');
}
else {
  $oneback = "../";
  $root = $oneback;
  $level = 1;
  while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
    $root .= $oneback;
    $level += 1;
  }
  if (file_exists($root.'/framework/class.secure.php')) {
    include($root.'/framework/class.secure.php');
  }
  else {
    trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
  }
}
// end include class.secure.php

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');

global $admin;

$dbEducatedQuestions = new dbEducatedQuestions();
if ($dbEducatedQuestions->sqlTableExists()) {
	if (!$dbEducatedQuestions->sqlDeleteTable()) {
		$admin->print_error(sprintf('[Uninstall] %s', $dbEducatedQuestions->getError()));
	}
}

$dbEducatedItems = new dbEducatedItems();
if ($dbEducatedItems->sqlTableExists()) {
	if (!$dbEducatedItems->sqlDeleteTable()) {
		$admin->print_error(sprintf('[Uninstall] %s', $dbEducatedItems->getError()));
	}
}

$dbEducatedGroups = new dbEducatedGroups();
if ($dbEducatedGroups->sqlTableExists()) {
	if (!$dbEducatedGroups->sqlDeleteTable()) {
		$admin->print_error(sprintf('[Uninstall] %s', $dbEducatedGroups->getError()));
	}
}

$dbEducatedConfig = new dbEducatedConfig();
if ($dbEducatedConfig->sqlTableExists()) {
	if (!$dbEducatedConfig->sqlDeleteTable()) {
		$admin->print_error(sprintf('[Uninstall] %s', $dbEducatedConfig->getError()));
	}
}

?>