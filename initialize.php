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

// prevent this file from being accesses directly
if(defined('WB_PATH') == false) {
	exit("Cannot access this file directly"); 
}

$debug = true;
if ($debug) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
}

// WB Konfigurationsdatei einbinden
require_once(WB_PATH.'/config.php');

// Sprachdateien einbinden
if(!file_exists(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php')) {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/DE.php'); 
}
else {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php'); 
}

require_once(WB_PATH.'/modules/dbconnect_le/include.php');
require_once(WB_PATH.'/modules/rhtools/include.php'); 
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.educated.php');
if (!class_exists('Dwoo')) require_once(WB_PATH.'/modules/dwoo/include.php');

global $parser;
if (!is_object($parser)) $parser = new Dwoo();
 
?>