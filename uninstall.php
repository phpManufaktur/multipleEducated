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
  
  $Id: uninstall.php 9 2010-07-18 09:54:20Z ralf $
  
**/

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