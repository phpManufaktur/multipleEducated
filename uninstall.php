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