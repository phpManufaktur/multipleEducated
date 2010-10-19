<?php

/**
 * multipleEducated - create quizzes like "Bildungshappen" for WebsiteBaker
 * 
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de/cms/topics/multipleeducated.php
 * @copyright 2009 - 2010
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id$
 */ 

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