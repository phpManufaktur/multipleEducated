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
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.backend.php');

$backendEducated = new backendEducated();
$backendEducated->action();

?>