<?php

/**
 * multipleEducated
 *
 * @author Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @link http://phpmanufaktur.de
 * @copyright 2009 - 2013
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
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.droplets.php');

global $admin;

$dbEducatedQuestions = new dbEducatedQuestions(true);
if ($dbEducatedQuestions->isError()) {
	$admin->print_error(sprintf('[Installation] %s', $dbEducatedQuestions->getError()));
}

$dbEducatedItems = new dbEducatedItems(true);
if ($dbEducatedItems->isError()) {
	$admin->print_error(sprintf('[Installation] %s', $dbEducatedItems->getError()));
}

$dbEducatedGroups = new dbEducatedGroups(true);
if ($dbEducatedGroups->isError()) {
	$admin->print_error(sprintf('[Installation] %s', $dbEducatedGroups->getError()));
}

$dbEducatedConfig = new dbEducatedConfig(true);
if ($dbEducatedConfig->isError()) {
	$admin->print_error(sprintf('[Installation] %s', $dbEducatedConfig->getError()));
}

// Install Droplets
$droplets = new checkDroplets();
if ($droplets->insertDropletsIntoTable()) {
  $message = 'The Droplets for multipleEducated where successfully installed! Please look at the Help for further informations.';
}
else {
  $message = 'The installation of the Droplets for multipleEducated failed. Error: '. $droplets->getError();
}
if ($message != "") {
  echo '<script language="javascript">alert ("'.$message.'");</script>';
}


?>