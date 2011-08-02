<?php

/**
 * multipleEducated
 * 
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de
 * @copyright 2011
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id$
 * 
 * FOR VERSION- AND RELEASE NOTES PLEASE LOOK AT INFO.TXT!
 */

// try to include LEPTON class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	if (defined('LEPTON_VERSION')) include(WB_PATH.'/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
	include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php'); 
} else {
	$subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));	$dir = $_SERVER['DOCUMENT_ROOT'];
	$inc = false;
	foreach ($subs as $sub) {
		if (empty($sub)) continue; $dir .= '/'.$sub;
		if (file_exists($dir.'/framework/class.secure.php')) { 
			include($dir.'/framework/class.secure.php'); $inc = true;	break; 
		} 
	}
	if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include LEPTON class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}
// end include LEPTON class.secure.php

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');

global $parser;
global $rhTools;
global $dbEdQuestions;
global $dbEdItems;
global $dbEdGroups;
global $dbEdCfg; 

if (!is_object($parser)) $parser = new Dwoo();
if (!is_object($rhTools)) $rhTools = new rhTools();
if (!is_object($dbEdQuestions)) $dbEdQuestions = new dbEducatedQuestions();
if (!is_object($dbEdItems)) $dbEdItems = new dbEducatedItems();
if (!is_object($dbEdGroups)) $dbEdGroups = new dbEducatedGroups();
if (!is_object($dbEdCfg)) $dbEdCfg = new dbEducatedConfig();

class multipleEducated {
	
	const request_action				= 'eda';
	
	const action_default				= 'def';
	const action_question				= 'quest';
	const action_answer_check		= 'ac';
	
	private $page_link 					= '';
	private $template_path			= '';
	private $error							= '';
	private $message						= '';
	
	private $cfgQuestionShuffle		= 0;
	private $cfgRepliesCount			= 3;
	private $useGroupID						= -1;
	
	public function __construct($groupID = -1) {
		$tools = new rhTools();
		$tools->getPageLinkByPageID(PAGE_ID, $this->page_link);
		$this->template_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/htt/' ;
		$dbEducatedConfig = new dbEducatedConfig();
		$this->useGroupID = $groupID;
		$this->cfgQuestionShuffle = $dbEducatedConfig->getValue(dbEducatedConfig::cfgQuestionShuffle);
		$this->cfgRepliesCount = $dbEducatedConfig->getValue(dbEducatedConfig::cfgRepliesCount);
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

  /**
   * Reset Error to empty String
   */
  public function clearError() {
  	$this->error = '';
  }

  /** Set $this->message to $message
    * 
    * @param STR $message
    */
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
   * Return Version of Module
   *
   * @return FLOAT
   */
  public function getVersion() {
    // read info.php into array
    $info_text = file(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/info.php');
    if ($info_text == false) {
      return -1; 
    }
    // walk through array
    foreach ($info_text as $item) {
      if (strpos($item, '$module_version') !== false) {
        // split string $module_version
        $value = split('=', $item);
        // return floatval
        return floatval(ereg_replace('([\'";,\(\)[:space:][:alpha:]])', '', $value[1])); 
      } 
    }
    return -1;
  } // getVersion()
  
  
  /**
   * Verhindert XSS Cross Site Scripting
   * 
   * @param REFERENCE $_REQUEST Array
   * @return $request
   */
	public function xssPrevent(&$request) {
  	if (is_string($request)) {
	    $request = html_entity_decode($request);
	    $request = strip_tags($request);
	    $request = trim($request);
	    $request = stripslashes($request);
  	}
	  return $request;
  } // xssPrevent()
	
  public function action($page_path = -1) {
  	if ($page_path != -1) {
  		$this->page_link = WB_URL.$page_path;
  	}
  	$html_allowed = array();
  	foreach ($_REQUEST as $key => $value) {
  		if (!in_array($key, $html_allowed)) {
    		$_REQUEST[$key] = $this->xssPrevent($value);
  		} 
  	}
    isset($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = self::action_default;
  	switch ($action):
  	case self::action_question:
  		$this->show($this->startQuestion());
  		break;
  	case self::action_answer_check:
  		$this->show($this->checkAnswer());
  		break;
  	default:
  		$this->show($this->startQuestion());
  		break;
  	endswitch;
  } // action
	
  public function show($content) {
  	global $parser;
  	if ($this->isError()) {
  		$content = $this->getError();
  		$class = 'ed_error';
  	}
  	else {
  		$class = 'ed_content';
  	}
  	$data = array(
  		'class'		=> $class,
  		'content'	=> $content
  	);
  	$parser->output($this->template_path.'frontend.body.htt', $data);
  } // show()
  
  /**
   * Zeigt die Eingangsfrage an
   */
  public function startQuestion() {
  	global $dbEdGroups;
  	global $dbEdQuestions;
  	global $parser;
  	global $dbEdItems;
  	
  	// Gruppe angegeben?
  	if ($this->useGroupID > 0) {
  		//  Pruefen, ob Gruppe existiert und aktiv ist...
  		$where = array();
  		$where[dbEducatedGroups::field_id] = $this->useGroupID;
  		$where[dbEducatedGroups::field_status] = dbEducatedGroups::status_active;
  		$groups = array();
  		if (!$dbEdGroups->sqlSelectRecord($where, $groups)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdGroups->getError()));
  			return false;
  		}
  		if (count($groups) < 1) {
  			// keine Gruppe gefunden oder Gruppe inaktiv
  			$this->useGroupID = -1;
  		}
  	}
  	else {
  		$this->useGroupID = -1;
  	}
  	if ((isset($_REQUEST[dbEducatedQuestions::field_id])) && ($_REQUEST[dbEducatedQuestions::field_id] > 0)) {
  		// Frage an Hand der ID auswaehlen
  		$where = array();
  		$where[dbEducatedQuestions::field_id] = $_REQUEST[dbEducatedQuestions::field_id];
  		$question = array();
  		if (!$dbEdQuestions->sqlSelectRecord($where, $question)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdQuestions->getError()));
  			return false;
  		}
  		if (sizeof($question) < 1) {
  			// Abfrage fehlgeschlagen
  			$this->setError(sprintf(ed_error_question_id_not_exists, __METHOD__, __LINE__, $_REQUEST[dbEducatedQuestions::field_id]));
  			return false;
  		}
  		$question = $question[0];
  	}
  	elseif ($this->cfgQuestionShuffle) {
  		// Frage zufaellig auswaehlen, Datum wird nicht beruecksichtigt
  		if ($this->useGroupID > 0) {
  			// Frage aus bestimmter Gruppe auswaehlen
  			$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s='%s' ORDER BY RAND() LIMIT 1",
  										$dbEdQuestions->getTableName(),
  										dbEducatedQuestions::field_status,
  										dbEducatedQuestions::status_active,
  										dbEducatedQuestions::field_group,
  										$this->useGroupID);
  		}
  		else {
  			// Frage aus beliebiger Gruppe
  			$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' ORDER BY RAND() LIMIT 1",
  										$dbEdQuestions->getTableName(),
  										dbEducatedQuestions::field_status,
  										dbEducatedQuestions::status_active);
  		}
  		
  		$question = array();
  		if (!$dbEdQuestions->sqlExec($SQL, $question)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdQuestions->getError()));
  			return false;	
  		}
  		if (sizeof($question) < 1) {
  			// Abfrage fehlgeschlagen
  			$this->setError(ed_error_random_fail);
  			return false;
  		}
  		$question = $question[0];
  	}
  	else {
  		// Frage aus einem Datumsbereich zufaellig auswaehlen
  		if ($this->useGroupID > 0) {
  			// Frage aus einer bestimmten Gruppe auswaehlen
  			$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s <= NOW() AND %s >= NOW() AND %s='%s' ORDER BY RAND() LIMIT 1",
  										$dbEdQuestions->getTableName(),
  										dbEducatedQuestions::field_status,
  										dbEducatedQuestions::status_active,
  										dbEducatedQuestions::field_date_start,
  										dbEducatedQuestions::field_date_stop,
  										dbEducatedQuestions::field_group,
  										$this->useGroupID);
  		}
  		else {
  			// Frage aus beliebiger Gruppe
  			$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s <= NOW() AND %s >= NOW() ORDER BY RAND() LIMIT 1",
  										$dbEdQuestions->getTableName(),
  										dbEducatedQuestions::field_status,
  										dbEducatedQuestions::status_active,
  										dbEducatedQuestions::field_date_start,
  										dbEducatedQuestions::field_date_stop);
  		}
  		
  		if (!$dbEdQuestions->sqlExec($SQL, $question)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdQuestions->getError()));
  			return false;	
  		}
  		if (sizeof($question) < 1) {
  			// Abfrage fehlgeschlagen
  			$this->setError(ed_error_date_random_fail);
  			return false;
  		}
  		$question = $question[0];
  	}
 
		$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' ORDER BY RAND()",
										$dbEdItems->getTableName(),
										dbEducatedItems::field_question_id,
										$question[dbEducatedQuestions::field_id]);
		$answers = array();
		if (!$dbEdItems->sqlExec($SQL, $answers)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdItems->getError()));
			return false;
		}
		if (sizeof($answers) < $this->cfgRepliesCount) {
			// Anzahl der Antworten ist zu gering
			$this->setError(ed_error_to_less_answers);
			return false;
		}
		$items = '';
		$row = new Dwoo_Template_File($this->template_path.'frontend.question.answer.htt');
		foreach ($answers as $answer) {
			$data = array(
				'radio_name'	=> dbEducatedItems::field_id,
				'radio_value'	=> $answer[dbEducatedItems::field_id],
				'answer'			=> $answer[dbEducatedItems::field_answer]
			);
			$items .= $parser->get($row, $data);
		}	
		// Mitteilungen anzeigen
		if ($this->isMessage()) {
			$intro = sprintf('<div class="ed_message">%s</div>', $this->getMessage());
		}
		else {
			$intro = '';
		}
  	
		$data = array(
			'header'					=> ed_header_question,
			'intro'						=> $intro,
			'form_name'				=> 'ed_question',
			'form_action'			=> $this->page_link,
			'action_name'			=> self::request_action,
			'action_value'		=> self::action_answer_check,
			'id_name'					=> dbEducatedQuestions::field_id,
			'id_value'				=> $question[dbEducatedQuestions::field_id],
			'question'				=> $question[dbEducatedQuestions::field_question],
			'answers'					=> $items,
			'btn_ok'					=> ed_btn_check,
			'btn_abort'				=> ed_btn_new_question,
			'abort_location'	=> sprintf('%s?%s=%s', $this->page_link, self::request_action, self::action_question),
		);
		return $parser->get($this->template_path.'frontend.question.htt', $data);
  } // startQuestion()

  public function checkAnswer() {
  	global $dbEdQuestions;
  	global $dbEdItems;
  	global $parser;
  	
  	if (!isset($_REQUEST[dbEducatedItems::field_id])) {
  		// Fehler: keine Auswahl getroffen
  		$this->setMessage(ed_msg_answer_no_selection);
  		return $this->startQuestion();
  	}
  	else {
  		$item_id = $_REQUEST[dbEducatedItems::field_id];
  	}
  	if (!isset($_REQUEST[dbEducatedItems::field_id])) {
  		// Fehler: keine Question ID gesetzt
  		$this->setError(ed_error_question_id_missing, __METHOD__, __LINE__);
  		return false;
  	}
  	else {
  		$id = $_REQUEST[dbEducatedQuestions::field_id];
  	}
  	$where = array();
  	$where[dbEducatedQuestions::field_id] = $id;
  	$question = array();
  	if (!$dbEdQuestions->sqlSelectRecord($where, $question)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdQuestions->getError()));
  		return false;
  	}
  	if (sizeof($question) < 1) {
  		$this->setError(sprintf(ed_error_question_id_not_exists, __METHOD__, __LINE__, $id));
  		return false;
  	}
  	$question = $question[0];
  	$where = array();
  	$where[dbEducatedItems::field_id] = $item_id;
  	$answer = array();
  	if (!$dbEdItems->sqlSelectRecord($where, $answer)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdItems->getError()));
  		return false;
  	}
  	if (sizeof($answer) < 1) {
  		$this->setError(sprintf(ed_error_answer_id_not_exists, __METHOD__, __LINE__, $item_id));
  		return false;
  	}
  	$answer = $answer[0];
  	// Antwort anzeigen
  	$antwort = sprintf(ed_str_answer_repeat, $answer[dbEducatedItems::field_answer]);
  	// Ist die Frage richtig beantwortet?
  	if ($answer[dbEducatedItems::field_truth] == dbEducatedItems::truth_true) {
  		// Frage ist richtig beantwortet
  		$answer_class = 'ed_answer_good';
  		$wertung = sprintf(ed_str_answer_good, $answer[dbEducatedItems::field_explain]);	
  		$id = -1;
  		$btn_ok = ed_btn_new_question;
  	}
  	else {
  		// Frage ist falsch beantwortet
  		$answer_class = 'ed_answer_bad';
  		$wertung = sprintf(ed_str_answer_bad, $answer[dbEducatedItems::field_explain]);
  		$btn_ok = ed_btn_retry;
  	}
  	  	
  	$data = array(
  		'header'					=> ed_header_answer,
  		'form_name'				=> 'ed_answer',
  		'form_action'			=> $this->page_link,
  		'action_name'			=> self::request_action,
  		'action_value'		=> self::action_question,
  		'id_name'					=> dbEducatedQuestions::field_id,
  		'id_value'				=> $id,
  		'question'				=> $question[dbEducatedQuestions::field_question],
  		'answer_class'		=> $answer_class,
  		'answer'					=> $antwort,
  		'explain'					=> $wertung,
  		'btn_ok'					=> $btn_ok,
  		'btn_abort'				=> ed_btn_abort,
  		'abort_location'	=> sprintf('%s?%s=%s&%s=-1', $this->page_link, self::request_action, self::action_question, dbEducatedQuestions::field_id)
  	);
  	return $parser->get($this->template_path.'frontend.answer.htt', $data);
  } // checkAnswer()
  
} // class multipleEducated

?>