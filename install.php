<?php

/**
  Module developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
  Copyright (c) 2009, Ralf Hertsch
  Contact me: hertsch(at)berlin.de, http://phpManufaktur.de

  This module is free software. You can redistribute it and/or modify it
  under the terms of the GNU General Public License  - version 2 or later,
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  This module is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  $Id: install.php 9 2010-07-18 09:54:20Z ralf $
  
**/

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