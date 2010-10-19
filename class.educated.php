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

class dbEducatedQuestions extends dbConnectLE {
	
	const field_id						= 'question_id';
	const field_name					= 'question_name';
	const field_question			= 'question_question';
	const field_group					= 'question_group';
	const field_date_start		= 'question_date_start';
	const field_date_stop			= 'question_date_stop';
	const field_status				= 'question_status';
	const field_created_when	= 'questions_created_when';
	const field_update_by			= 'question_update_by';
	const field_update_when		= 'question_update_when';
	
	const status_active				= 1;
	const status_locked				= 2;
	const status_deleted			= 0;
	
	var $status_array = array(
		self::status_active		=> ed_status_active,
		self::status_locked		=> ed_status_locked,
		self::status_deleted	=> ed_status_deleted
	);
	
	private $create_tables 		= false;
	
	public function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_educated_questions');
		$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_name, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_question, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_group, "TINYINT UNSIGNED NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_date_start, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_date_stop, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");		
		$this->addFieldDefinition(self::field_status, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_created_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(64) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->checkFieldDefinitions();
		if (($this->create_tables) && (!$this->sqlTableExists())) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
			}
		}
		// important: switch decoding OFF.
		$this->setDecodeSpecialChars(false);
	} // __construct()
} // dbEducatedQuestions

class dbEducatedItems extends dbConnectLE {
	
	const field_id						= 'item_id';
	const field_question_id		= 'item_question_id';
	//const field_question			= 'item_question';
	const field_answer				= 'item_answer';
	const field_truth					= 'item_is_true';
	const field_explain				= 'item_explain';
	const field_status				= 'item_status';
	const field_update_by			= 'item_update_by';
	const field_update_when		= 'item_update_when';
	
	const status_active				= 1;
	//const status_locked				= 2;
	const status_deleted			= 0;
	
	var $status_array = array(
		self::status_active		=> ed_status_active,
		//self::status_locked		=> ed_status_locked,
		self::status_deleted	=> ed_status_deleted
	);
	
	const truth_true					= 1;
	const truth_false					= 0;
	
	var $truth_array = array(
		self::truth_true			=> ed_str_true,
		self::truth_false			=> ed_str_false
	);
	
	private $create_tables 		= false;
	 
	public function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_educated_items');
		$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_question_id, "INT(11) NOT NULL DEFAULT '-1'");
		//$this->addFieldDefinition(self::field_question, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_answer, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_truth, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::truth_false."'");
		$this->addFieldDefinition(self::field_explain, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_status, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(64) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->setIndexFields(array(self::field_question_id));
		$this->checkFieldDefinitions();
		if (($this->create_tables) && (!$this->sqlTableExists())) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
			}
		}	
		// important: switch decoding OFF.
		$this->setDecodeSpecialChars(false);
	} // __construct()
	
} // class dbEducatedItems

class dbEducatedGroups extends dbConnectLE {
	
	const field_id						= 'grp_id';
	const field_name					= 'grp_name';
	const field_description		= 'grp_description';
	const field_status				= 'grp_status';
	const field_update_by			= 'grp_update_by';
	const field_update_when		= 'grp_update_when';
	
	const status_active				= 1;
	const status_deleted			= 0;
	const status_locked				= 2;
	
	var $status_array = array(
		self::status_active		=> ed_status_active,
		self::status_locked		=> ed_status_locked,
		self::status_deleted	=> ed_status_deleted
	);
	
	private $create_tables 		= false;
	
	public function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_educated_groups');
		$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_name, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_description, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_status, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(64) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->checkFieldDefinitions();
		if (($this->create_tables) && (!$this->sqlTableExists())) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
			}
		}
		// important: switch decoding OFF.
		$this->setDecodeSpecialChars(false);
	} // __construct()
	
} // class dbEducatedGroups

class dbEducatedConfig extends dbConnectLE {
	
	const field_id						= 'cfg_id';
	const field_name					= 'cfg_name';
	const field_type					= 'cfg_type';
	const field_value					= 'cfg_value';
	const field_label					= 'cfg_label';
	const field_description		= 'cfg_desc';
	const field_status				= 'cfg_status';
	const field_update_by			= 'cfg_update_by';
	const field_update_when		= 'cfg_update_when';
	
	const status_active				= 1;
	const status_deleted			= 0;
	
	const type_undefined			= 0;
	const type_array					= 7;
  const type_boolean				= 1;
  const type_email					= 2;
  const type_float					= 3;
  const type_integer				= 4;
  const type_path						= 5;
  const type_string					= 6;
  const type_url						= 8;
  
  public $type_array = array(
  	self::type_undefined		=> '-UNDEFINED-',
  	self::type_array				=> 'ARRAY',
  	self::type_boolean			=> 'BOOLEAN',
  	self::type_email				=> 'E-MAIL',
  	self::type_float				=> 'FLOAT',
  	self::type_integer			=> 'INTEGER',
  	self::type_path					=> 'PATH',
  	self::type_string				=> 'STRING',
  	self::type_url					=> 'URL'
  );
  
  private $createTables 		= false;
  private $message					= '';
    
  const cfgQuestionShuffle	= 'cfgQuestionShuffle';
  const cfgRepliesCount			= 'cfgRepliesCount';
  
  public $config_array = array(
  	array('ed_label_cfg_question_shuffle', self::cfgQuestionShuffle, self::type_boolean, 1, 'ed_desc_cfg_question_shuffle'),
  	array('ed_label_cfg_replies_count', self::cfgRepliesCount, self::type_integer, 3, 'ed_desc_cfg_replies_count')  	
  );
  
  public function __construct($createTables = false) {
  	$this->createTables = $createTables;
  	parent::__construct();
  	$this->setTableName('mod_educated_cfg');
  	$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
  	$this->addFieldDefinition(self::field_name, "VARCHAR(32) NOT NULL DEFAULT ''");
  	$this->addFieldDefinition(self::field_type, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::type_undefined."'");
  	$this->addFieldDefinition(self::field_value, "VARCHAR(255) NOT NULL DEFAULT ''");
  	$this->addFieldDefinition(self::field_label, "VARCHAR(64) NOT NULL DEFAULT 'ed_str_undefined'");
  	$this->addFieldDefinition(self::field_description, "VARCHAR(255) NOT NULL DEFAULT 'ed_str_undefined'");
  	$this->addFieldDefinition(self::field_status, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::status_active."'");
  	$this->addFieldDefinition(self::field_update_by, "VARCHAR(32) NOT NULL DEFAULT 'SYSTEM'");
  	$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
  	$this->setIndexFields(array(self::field_name));
  	$this->checkFieldDefinitions();
  	// Tabelle erstellen
  	if ($this->createTables) {
  		if (!$this->sqlTableExists()) {
  			if (!$this->sqlCreateTable()) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  			}
  		}
  	}
  	// Default Werte garantieren
  	if ($this->sqlTableExists()) {
  		$this->checkConfig();
  	}
  	// important: switch decoding OFF.
		$this->setDecodeSpecialChars(false);
  } // __construct()
  
  public function setMessage($message) {
    $this->message = $message;
  } // setMessage()

  /**
    * Get Message from $this->message;
    * 
    * @return STR $this->message
    */
  public function getMessage() {
    return $this->message;
  } // getMessage()

  /**
    * Check if $this->message is empty
    * 
    * @return BOOL
    */
  public function isMessage() {
    return (bool) !empty($this->message);
  } // isMessage
  
  
  /**
   * Fuegt den Wert $new_value in die dbShortLinkConfig ein
   * 
   * @param $new_value STR - Wert, der uebernommen werden soll
   * @param $id INT - ID des Datensatz, dessen Wert aktualisiert werden soll
   * 
   * @return BOOL Ergebnis
   */
  public function setValue($new_value, $id) {
  	$tools = new rhTools();
  	$value = '';
  	$where = array();
  	$where[self::field_id] = $id;
  	$config = array();
  	if (!$this->sqlSelectRecord($where, $config)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  		return false;
  	}
  	if (sizeof($config) < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(ed_error_cfg_id, $id)));
  		return false;
  	}
  	$config = $config[0];
  	switch ($config[self::field_type]):
  	case self::type_array:
  		// Funktion geht davon aus, dass $value als STR uebergeben wird!!!
  		$worker = explode(",", $new_value);
  		$data = array();
  		foreach ($worker as $item) {
  			$data[] = trim($item);
  		};
  		$value = implode(",", $data);  			
  		break;
  	case self::type_boolean:
  		$value = (bool) $new_value;
  		$value = (int) $value;
  		break;
  	case self::type_email:
  		if ($tools->validateEMail($new_value)) {
  			$value = trim($new_value);
  		}
  		else {
  			$this->setMessage(sprintf(sl_msg_invalid_email, $new_value));
  			return false;			
  		}
  		break;
  	case self::type_float:
  		$value = $tools->str2float($new_value);
  		break;
  	case self::type_integer:
  		$value = $tools->str2int($new_value);
  		break;
  	case self::type_url:
  	case self::type_path:
  		$value = $tools->addSlash(trim($new_value));
  		break;
  	case self::type_string:
  		$value = (string) trim($new_value);
  		break;
  	endswitch;
  	unset($config[self::field_id]);
  	$config[self::field_value] = (string) $value;
  	$config[self::field_update_by] = $tools->getDisplayName();
  	$config[self::field_update_when] = date('Y-m-d H:i:s');
  	if (!$this->sqlUpdateRecord($config, $where)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  		return false;
  	}
  	return true;
  } // setValue()
  
  /**
   * Gibt den angeforderten Wert zurueck
   * 
   * @param $name - Bezeichner 
   * 
   * @return WERT entsprechend des TYP
   */
  public function getValue($name) {
  	$result = '';
  	$where = array();
  	$where[self::field_name] = $name;
  	$config = array();
  	if (!$this->sqlSelectRecord($where, $config)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  		return false;
  	}
  	if (sizeof($config) < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(ed_error_cfg_name, $name)));
  		return false;
  	}
  	$config = $config[0];
  	switch ($config[self::field_type]):
  	case self::type_array:
  		$result = explode(",", $config[self::field_value]);
  		break;
  	case self::type_boolean:
  		$result = (bool) $config[self::field_value];
  		break;
  	case self::type_email:
  	case self::type_path:
  	case self::type_string:
  	case self::type_url:
  		$result = (string) utf8_decode($config[self::field_value]);
  		break;
  	case self::type_float:
  		$result = (float) $config[self::field_value];
  		break;
  	case self::type_integer:
  		$result = (integer) $config[self::field_value];
  		break;
  	default:
  		$result = utf8_decode($config[self::field_value]);
  		break;
  	endswitch;
  	return $result;
  } // getValue()
  
  public function checkConfig() {
  	foreach ($this->config_array as $item) {
  		$where = array();
  		$where[self::field_name] = $item[1];
  		$check = array();
  		if (!$this->sqlSelectRecord($where, $check)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  			return false;
  		}
  		if (sizeof($check) < 1) {
  			// Eintrag existiert nicht
  			$data = array();
  			$data[self::field_label] = $item[0];
  			$data[self::field_name] = $item[1];
  			$data[self::field_type] = $item[2];
  			$data[self::field_value] = $item[3];
  			$data[self::field_description] = $item[4];
  			$data[self::field_update_when] = date('Y-m-d H:i:s');
  			$data[self::field_update_by] = 'SYSTEM';
  			if (!$this->sqlInsertRecord($data)) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  				return false;
  			}
  		}
  	}
  	return true;
  }
	
} // class dbEducatedConfig

?>