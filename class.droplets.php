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

class dbDroplets extends dbConnectLE {

	const field_id							= 'id';
	const field_name						= 'name';
	const field_code						= 'code';
	const field_description			= 'description';
	const field_modified_when		= 'modified_when';
	const field_modified_by			= 'modified_by';
	const field_active					= 'active';
	const field_comments				= 'comments';

	public function __construct() {
		parent::__construct();
		$this->setTableName('mod_droplets');
		$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_name, "VARCHAR(32) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_code, "TEXT NOT NULL DEFAULT ''", false, false, true);
		$this->addFieldDefinition(self::field_description, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_modified_when, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_modified_by, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_active, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_comments, "TEXT NOT NULL DEFAULT ''");
		$this->checkFieldDefinitions();
	} // __construct()

} // class dbDroplets

define('droplet_educated_name', 'Educated');
define('droplet_educated_code', '$educated = "<p>- multipleEducated ist nicht implementiert -</p>";'."\r\n".'if (file_exists(WB_PATH.\'/modules/educated/class.frontend.php\')) {'."\r\n".'  require_once(WB_PATH.\'/modules/educated/class.frontend.php\');'."\r\n".'  $educated = new multipleEducated();'."\r\n".'  ob_start();'."\r\n".'    $educated->action();'."\r\n".'    $educated = ob_get_contents();'."\r\n".'  ob_end_clean();'."\r\n".'}'."\r\n".'return $educated;');
define('droplet_educated_description', '');

class checkDroplets {

	var $droplet_path	= '';
	var $error = '';

	public function __construct() {
		$this->droplet_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/droplets/' ;
	} // __construct()

	/**
    * Set $this->error to $error
    *
    * @param STR $error
    */
  public function setError($error) {
    $this->error = $error;
  } // setError()

  /**
    * Get Error from $this->error;
    *
    * @return STR $this->error
    */
  public function getError() {
    return $this->error;
  } // getError()

  /**
    * Check if $this->error is empty
    *
    * @return BOOL
    */
  public function isError() {
    return (bool) !empty($this->error);
  } // isError

	public function insertDropletsIntoTable() {
		global $admin;
		// Read droplets from directory
		$folder = opendir($this->droplet_path.'.');
		$names = array();
		while (false !== ($file = readdir($folder))) {
			$ext = strtolower(substr($file,-4));
			if ($ext	==	".php") {
				$names[count($names)] = $file;
			}
		}
		closedir($folder);
		// init droplets
		$dbDroplets = new dbDroplets();
		if (!$dbDroplets->sqlTableExists()) {
			// Droplets not installed!
			return false;
		}
		// walk through array
		foreach ($names as $dropfile) {
			//$droplet = addslashes($this->getDropletCodeFromFile($dropfile));
			$droplet = $this->getDropletCodeFromFile($dropfile);
			if ($droplet != "") {
				// get droplet name
				$name = substr($dropfile,0,-4);
				$where = array();
				$where[dbDroplets::field_name] = $name;
				$result = array();
				if (!$dbDroplets->sqlSelectRecord($where, $result)) {
					// error exec query
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbDroplets->getError()));
					return false;
				}
				if (sizeof($result) < 1) {
					// insert this droplet into table
					$description = "Example Droplet";
					$comments = "Example Droplet";
					$cArray = explode("\n",$droplet);
					if (substr($cArray[0],0,3) == "//:") {
						// extract description
						$description = trim(substr($cArray[0],3));
						array_shift($cArray);
					}
					if (substr($cArray[0],0,3) == "//:") {
						// extract comment
						$comments = trim(substr($cArray[0],3));
						array_shift($cArray);
					}
					$data = array();
					$data[dbDroplets::field_name] = $name;
					$code = implode("\r\n", $cArray);
					$data[dbDroplets::field_code] = $code;
					$data[dbDroplets::field_description] = $description;
					$data[dbDroplets::field_comments] = $comments;
					$data[dbDroplets::field_active] = 1;
					$data[dbDroplets::field_modified_by] = $admin->get_user_id();
					$data[dbDroplets::field_modified_when] = time();
					if (!$dbDroplets->sqlInsertRecord($data)) {
						// error exec query
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbDroplets->getError()));
						return false;
					}
				}
			}
		}
		return true;
	} // insertDropletsIntoTable()

	public function getDropletCodeFromFile($dropletfile) {
		$data = "";
		$filename = $this->droplet_path.$dropletfile;
		if (file_exists($filename)) {
			$filehandle = fopen ($filename, "r");
			$data = fread ($filehandle, filesize ($filename));
			fclose($filehandle);
		}
		return $data;
	} // getDropletCodeFromFile()

} // checkDroplets

?>