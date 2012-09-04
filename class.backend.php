<?php

/**
 * multipleEducated
 *
 * @author Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @link http://phpmanufaktur.de
 * @copyright 2009 - 2012
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


global $dbEdQuestions;
global $dbEdItems;
global $dbEdGroups;
global $dbEdCfg;

if (!is_object($dbEdQuestions)) $dbEdQuestions = new dbEducatedQuestions();
if (!is_object($dbEdItems)) $dbEdItems = new dbEducatedItems();
if (!is_object($dbEdGroups)) $dbEdGroups = new dbEducatedGroups();
if (!is_object($dbEdCfg)) $dbEdCfg = new dbEducatedConfig();

class backendEducated {

	const request_action 						= 'act';
	const request_csv_export				= 'csvex';
	const request_items							= 'its';

	const action_default						= 'def';
	const action_question_edit			= 'qed';
	const action_question_check			= 'qc';
	const action_question_list			= 'ql';
	const action_groups_edit				= 'ge';
	const action_groups_check				= 'gc';
	const action_cfg								= 'cfg';
	const action_cfg_check					= 'cfgc';
	const action_info								= 'info';


	private $tab_navigation_array = array(
		self::action_question_edit			=> ed_tab_question_edit,
		self::action_question_list			=> ed_tab_question_list,
		self::action_groups_edit				=> ed_tab_groups_edit,
		self::action_cfg								=> ed_tab_config,
		self::action_info								=> ed_tab_info
	);

	private $page_link 					= '';
	private $template_path			= '';
	private $error							= '';
	private $message						= '';

	private $swNavHide					= array();

	public function __construct() {
		$this->page_link = ADMIN_URL.'/admintools/tool.php?tool=educated';
		$this->template_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/htt/' ;

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
        $value = explode('=', $item);
        // return floatval
        return floatval(preg_replace('([\'";,\(\)[:space:][:alpha:]])', '', $value[1]));
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

  public function action() {
  	$html_allowed = array();
  	foreach ($_REQUEST as $key => $value) {
  		if (!in_array($key, $html_allowed)) {
    		$_REQUEST[$key] = $this->xssPrevent($value);
  		}
  	}
    isset($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = self::action_default;
  	switch ($action):
  	case self::action_groups_edit:
  		$this->show(self::action_groups_edit, $this->dlgGroupsEdit());
  		break;
  	case self::action_question_check:
  		$this->show(self::action_question_edit, $this->QuestionEditCheck());
  		break;
  	case self::action_question_list:
  		$this->show(self::action_question_list, $this->dlgQuestionList());
  		break;
  	case self::action_groups_check:
  		$this->show(self::action_groups_edit, $this->GroupsEditCheck());
  		break;
  	case self::action_cfg:
  		$this->show(self::action_cfg, $this->editConfig());
  		break;
  	case self::action_cfg_check:
  		$this->show(self::action_cfg, $this->checkConfig());
  		break;
  	case self::action_info:
  		$this->show(self::action_info, $this->dlgInfo());
  		break;
  	case self::action_default:
  	default:
  		$this->show(self::action_question_edit, $this->dlgQuestionEdit());
  		break;
  	endswitch;
  } // action


  /**
   * Erstellt eine Navigationsleiste
   *
   * @param $action - aktives Navigationselement
   * @return STR Navigationsleiste
   */
  public function getNavigation($action) {
  	$result = '';
  	foreach ($this->tab_navigation_array as $key => $value) {
  		if (!in_array($key, $this->swNavHide)) {
	  		($key == $action) ? $selected = ' class="selected"' : $selected = '';
	  		$result .= sprintf(	'<li%s><a href="%s">%s</a></li>',
	  												$selected,
	  												sprintf('%s&%s=%s', $this->page_link, self::request_action, $key),
	  												$value
	  												);
  		}
  	}
  	$result = sprintf('<ul class="nav_tab">%s</ul>', $result);
  	return $result;
  } // getNavigation()

  /**
   * Ausgabe des formatieren Ergebnis mit Navigationsleiste
   *
   * @param $action - aktives Navigationselement
   * @param $content - Inhalt
   *
   * @return ECHO RESULT
   */
  public function show($action, $content) {
  	global $parser;
  	if ($this->isError()) {
  		$content = $this->getError();
  		$class = ' class="error"';
  	}
  	else {
  		$class = '';
  	}
  	$data = array(
  		'navigation'	=> $this->getNavigation($action),
  		'class'				=> $class,
  		'content'			=> $content
  	);
  	$parser->output($this->template_path.'backend.body.htt', $data);
  } // show()

  public function dlgInfo() {
  	global $parser;
  	$data = array(
  		'release'	=> $this->getVersion(),
  		'qrcode'	=> WB_URL.'/modules/'.basename(dirname(__FILE__)).'/images/qr-phpmanufaktur-135.png',
  		'img'			=> WB_URL.'/modules/'.basename(dirname(__FILE__)).'/images/multiple-educated-400.jpg'
  	);
  	return $parser->get($this->template_path.'backend.about.htt', $data);
  } // dlgInfo()

  public function dlgQuestionEdit() {
  	global $parser;
  	global $dbEdQuestions;
  	global $dbEdGroups;

  	$form_name = 'form_edit';
  	((isset($_REQUEST[dbEducatedQuestions::field_id])) && (!empty($_REQUEST[dbEducatedQuestions::field_id]))) ? $id = $_REQUEST[dbEducatedQuestions::field_id] : $id = -1;
  	$items = '';
  	// Gruppen auslesen
  	$SQL = sprintf(	"SELECT * FROM %s WHERE %s!='%s'",
  									$dbEdGroups->getTableName(),
  									dbEducatedGroups::field_status,
  									dbEducatedGroups::status_deleted);
		$groups = array();
		if (!$dbEdGroups->sqlExec($SQL, $groups)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdGroups->getError()));
			return false;
		}
		if (sizeof($groups) < 1) {
			// Es sind keine Gruppen definiert
			$this->setMessage(ed_msg_group_not_defined);
		}
  	else {
  		// Frage auslesen oder neue Frage erstellen?
  		if ($id != -1) {
  			// Vorhandene Frage auslesen
  			$where = array();
  			$where[dbEducatedQuestions::field_id] = $id;
  			$question = array();
  			if (!$dbEdQuestions->sqlSelectRecord($where, $question)) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdQuestions->getError()));
  				return false;
  			}
  			if (sizeof($question) < 1) {
  				// Fehler: gesuchte Frage existiert nicht
  				$this->setError(sprintf(ed_error_question_id_not_exists, __METHOD__, __LINE__, $id));
  				return false;
  			}
  			$question = $question[0];
  		}
  		else {
  			// NEUE Frage, DEFAULT Werte setzen
  			$question = $dbEdQuestions->getFields();
  			$question[dbEducatedQuestions::field_id] = $id;
  			$question[dbEducatedQuestions::field_status] = dbEducatedQuestions::status_active;
  		}

  		$row_1 = new Dwoo_Template_File($this->template_path.'backend.question.edit.row_1.htt');
  		$row_2 = new Dwoo_Template_File($this->template_path.'backend.question.edit.row_2.htt');
  		$row_3 = new Dwoo_Template_File($this->template_path.'backend.question.edit.row_3.htt');

//$row_1 = '<tr><td colspan="3">&nbsp;</td></tr><tr><td class="intro" colspan="3">%s</td></tr><tr><td colspan="3">&nbsp;</td></tr>'."\n";
//$row_2 = '<tr><td class="ed_label">%s</td><td colspan="2">%s</td></tr>'."\n";
//$row_3 = '<tr class="%s"><td class="ed_label">%s</td><td>%s</td><td>%s</td></tr>'."\n";
  		// ID und Erstellungsdatum
  		if ($id != -1) {
  			$data = array(
  				'label'	=> '',
  				'item'	=> sprintf('<b>#%05d</b> - %s',
  														$question[dbEducatedQuestions::field_id],
  														$dbEdQuestions->mySQLdate2datum($question[dbEducatedQuestions::field_created_when]))
  			);
  			$items .= $parser->get($row_2, $data);
  		}
  		// Bezeichner
  		(isset($_REQUEST[dbEducatedQuestions::field_name])) ? $name = $_REQUEST[dbEducatedQuestions::field_name] : $name = $question[dbEducatedQuestions::field_name];
  		$data = array(
  			'label'	=> ed_label_question_name,
  			'item'	=> sprintf(	'<input type="text" name="%s" value="%s" />',
  													dbEducatedQuestions::field_name,
  													$name)
  		);
  		$items .= $parser->get($row_2, $data);
  		(isset($_REQUEST[dbEducatedQuestions::field_question])) ? $quest = $_REQUEST[dbEducatedQuestions::field_question] : $quest = $question[dbEducatedQuestions::field_question];
  		$data = array(
  			'label'	=> ed_label_question_question,
  			'item'	=> sprintf(	'<textarea name="%s">%s</textarea>',
  													dbEducatedQuestions::field_question,
  													$quest)
  		);
  		$items .= $parser->get($row_2, $data);
  		// Gruppe
  		$grp = sprintf('<option value="-1">%s</option>', ed_str_select);
  		foreach ($groups as $group) {
  			($group[dbEducatedGroups::field_id] == $question[dbEducatedQuestions::field_group]) ? $selected = ' selected="selected"' : $selected = '';
  			$grp .= sprintf('<option value="%s"%s>%s</option>', $group[dbEducatedGroups::field_id], $selected, $group[dbEducatedGroups::field_name]);
  		}
  		$grp = sprintf('<select name="%s">%s</select>', dbEducatedQuestions::field_group, $grp);
  		$data = array(
  			'label'	=> ed_label_question_group,
  			'item'	=> $grp
  		);
  		$items .= $parser->get($row_2, $data);

			// Gueltigkeitsbereich
			if ((isset($_REQUEST[dbEducatedQuestions::field_date_start])) && (!empty($_REQUEST[dbEducatedQuestions::field_date_start]))) {
			  if (($dt = strtotime($_REQUEST[dbEducatedQuestions::field_date_start])) === false) {
			  	$date_start = '';
			  }
			  else {
			  	$date_start = date('d.m.Y', mktime(0, 0, 0, date('m', $dt), date('d', $dt), date('Y', $dt)));
			  }
			}
			else {
			 	if ($question[dbEducatedQuestions::field_date_start] != '0000-00-00 00:00:00') {
			 		if (($dt = strtotime($question[dbEducatedQuestions::field_date_start])) === false) {
			  		$date_start = '';
			  	}
			  	else {
			  		$date_start = date('d.m.Y', mktime(0, 0, 0, date('m', $dt), date('d', $dt), date('Y', $dt)));
			  	}
			 	}
			 	else {
			 		$date_start = '';
			 	}
			}
			if ((isset($_REQUEST[dbEducatedQuestions::field_date_stop])) && (!empty($_REQUEST[dbEducatedQuestions::field_date_stop]))) {
			  if (($dt = strtotime($_REQUEST[dbEducatedQuestions::field_date_stop])) === false) {
			  	$date_stop = '';
			  }
			  else {
			  	$date_stop = date('d.m.Y', mktime(0, 0, 0, date('m', $dt), date('d', $dt), date('Y', $dt)));
			  }
			}
			else {
			 	if ($question[dbEducatedQuestions::field_date_stop] != '0000-00-00 00:00:00') {
			 		if (($dt = strtotime($question[dbEducatedQuestions::field_date_stop])) === false) {
			  		$date_stop = '';
			  	}
			  	else {
			  		$date_stop = date('d.m.Y', mktime(0, 0, 0, date('m', $dt), date('d', $dt), date('Y', $dt)));
			  	}
			 	}
			 	else {
			 		$date_stop = '';
			 	}
			}
			$data = array(
				'label'	=> ed_label_question_date_range,
				'item'	=> sprintf(	'%s&nbsp;&nbsp;&nbsp;'.
														'<span class="date_picker">'.
    												'<input type="text" name="%s" value="%s" />'.
    												'<script language="JavaScript">'.
    												'new tcal ({ \'formname\': \'%s\', \'controlname\': \'%s\'	}, \'%s\');'.
    												'</script></span>'.
														'&nbsp;&nbsp;&nbsp;%s&nbsp;&nbsp;&nbsp;'.
														'<span class="date_picker">'.
    												'<input type="text" name="%s" value="%s" />'.
    												'<script language="JavaScript">'.
    												'new tcal ({ \'formname\': \'%s\', \'controlname\': \'%s\'	}, \'%s\');'.
    												'</script></span>',
														ed_str_valid_from,
														dbEducatedQuestions::field_date_start,
														$date_start,
														$form_name,
														dbEducatedQuestions::field_date_start,
														WB_URL. '/modules/'.basename(dirname(__FILE__)).'/images/calendar/',
														ed_str_valid_to,
														dbEducatedQuestions::field_date_stop,
														$date_stop,
														$form_name,
														dbEducatedQuestions::field_date_stop,
														WB_URL. '/modules/'.basename(dirname(__FILE__)).'/images/calendar/')
			);
			$items .= $parser->get($row_2, $data);
			// Status und letzte Aenderung
			(isset($_REQUEST[dbEducatedQuestions::field_status])) ? $stat = $_REQUEST[dbEducatedQuestions::field_status] : $stat = $question[dbEducatedQuestions::field_status];
			$status = '';
			foreach ($dbEdQuestions->status_array as $value => $name) {
				($value == $stat) ? $selected = ' selected="selected"' : $selected = '';
				$status .= sprintf('<option value="%s"%s>%s</option>', $value, $selected, $name);
			}
			$status = sprintf('<select style="width:150px;" name="%s">%s</select>', dbEducatedQuestions::field_status, $status);
			if ($id != -1) {
				$data = array(
					'label'	=> ed_label_question_status,
					'item'	=> sprintf(	'%s&nbsp;&nbsp;&nbsp;%s',
															$status,
															sprintf(ed_str_changed_by,
																			$question[dbEducatedQuestions::field_update_by],
																			$dbEdQuestions->mySQLdate2datum($question[dbEducatedQuestions::field_update_when])))
				);
				$items .= $parser->get($row_2, $data);
			}
			else {
				$data = array(
					'label'	=> ed_label_question_status,
					'item'	=> $status
				);
				$items .= $parser->get($row_2, $data);
			}
			// ANTWORTEN AUSLESEN
			$dbEducatedItems = new dbEducatedItems();
			$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s!='%s'",
											$dbEducatedItems->getTableName(),
											dbEducatedItems::field_question_id,
											$id,
											dbEducatedItems::field_status,
											dbEducatedItems::status_deleted);
			$replies = array();
			if (!$dbEducatedItems->sqlExec($SQL, $replies)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEducatedItems->getError()));
				return false;
			}
			$config = new dbEducatedConfig();
			$max_replies = $config->getValue(dbEducatedConfig::cfgRepliesCount);
			if (sizeof($replies) > $max_replies) {
				$max_replies = sizeof($replies);
			}
			// Zwischenzeile
			$items .= $parser->get($row_1, array('intro' => sprintf(ed_intro_answer_edit, $max_replies)));
			// Kopfzeile
			$data = array(
				'class'		=> 'flop',
				'label'		=> '',
				'item'		=> ed_label_answer_truth,
				'text'		=> ''
			);
			$items .= $parser->get($row_3, $data);
			$flipFlop = true;
			for ($i=0; $i < $max_replies; $i++) {
				if ($flipFlop) {
  		  	$flipFlop = false; $flip = 'flip';
  			}
  			else {
  		  	$flipFlop = true; $flip = 'flop';
  			}
  			// Richtig ???
				if (isset($_REQUEST[dbEducatedItems::field_truth])) {
					($_REQUEST[dbEducatedItems::field_truth] == $i) ? $truth = dbEducatedItems::truth_true : $truth = dbEducatedItems::truth_false;
				}
				elseif (isset($replies[$i][dbEducatedItems::field_truth])) {
					$truth = (bool) $replies[$i][dbEducatedItems::field_truth];
				}
				else {
					$truth = dbEducatedItems::truth_false;
				}
				($truth) ? $checked = ' checked="checked"' : $checked = '';
				// Antwort
				if ((isset($_REQUEST[dbEducatedItems::field_answer.'_'.$i])) && (!empty($_REQUEST[dbEducatedItems::field_answer.'_'.$i]))) {
					$answer = $_REQUEST[dbEducatedItems::field_answer.'_'.$i];
				}
				elseif (isset($replies[$i][dbEducatedItems::field_answer])) {
					$answer = $replies[$i][dbEducatedITems::field_answer];
				}
				else {
					$answer = '';
				}
				// Erklaerung
				if ((isset($_REQUEST[dbEducatedItems::field_explain.'_'.$i])) && (!empty($_REQUEST[dbEducatedItems::field_explain.'_'.$i]))) {
					$explain = $_REQUEST[dbEducatedItems::field_explain.'_'.$i];
				}
				elseif (isset($replies[$i][dbEducatedItems::field_explain])) {
					$explain = $replies[$i][dbEducatedItems::field_explain];
				}
				else {
					$explain = '';
				}
				if (isset($_REQUEST[dbEducatedItems::field_status.'_'.$i])) {
					$status = $_REQUEST[dbEducatedItems::field_status.'_'.$i];
				}
				elseif (isset($replies[$i][dbEducatedItems::field_status])) {
					$status = $replies[$i][dbEducatedItems::field_status];
				}
				else {
					$status = dbEducatedItems::status_active;
				}
				// ITEM ID
				(isset($replies[$i][dbEducatedItems::field_id])) ? $item_id = $replies[$i][dbEducatedItems::field_id] : $item_id = -1;
				// Frage
				$data = array(
					'class'	=> $flip,
					'label'	=> sprintf(ed_label_answer_answer_no, $i+1),
					'item'	=> sprintf(	'<div style="text-align:center;"><input type="radio" name="%s" value="%s"%s /></div>',
															dbEducatedItems::field_truth,
															$i,
															$checked),
					'text'	=> sprintf(	'<textarea name="%s_%d">%s</textarea><input type="hidden" name="%s" value="%s" />',
															dbEducatedItems::field_answer,
															$i,
															$answer,
															dbEducatedItems::field_id.'_'.$i,
															$item_id)
				);
				$items .= $parser->get($row_3, $data);
				$data = array(
					'class'	=> $flip,
					'label'	=> sprintf(ed_label_answer_explain_no, $i+1),
					'item'	=> '',
					'text'	=> sprintf(	'<textarea name="%s_%d">%s</textarea>',
															dbEducatedItems::field_explain,
															$i,
															$explain)
				);
				$items .= $parser->get($row_3, $data);
			}
  	}
  	// Mitteilungen anzeigen
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', ed_intro_question_edit);
		}
  	$data = array(
  		'header'						=> ed_header_question_edit,
  		'intro'							=> $intro,
  		'form_name'					=> $form_name,
  		'form_action'				=> $this->page_link,
  		'action_name'				=> self::request_action,
  		'action_value'			=> self::action_question_check,
  		'id_name'						=> dbEducatedQuestions::field_id,
  		'id_value'					=> $id,
  		'items'							=> $items,
  		'btn_ok'						=> ed_btn_ok,
  		'btn_abort'					=> ed_btn_abort,
  		'add_buttons'				=> '',
  		'abort_location'		=> $this->page_link
  	);
  	return $parser->get($this->template_path.'backend.question.edit.htt', $data);
  } // dlgQuestionEdit()

  public function QuestionEditCheck() {
  	global $dbEdQuestions;
  	global $dbEdItems;
  	global $dbEdCfg;

  	$message = '';
  	$update = false;
  	$error = false;
  	isset($_REQUEST[dbEducatedQuestions::field_id]) ? $id = $_REQUEST[dbEducatedQuestions::field_id] : $id = -1;
  	if ($id != -1) {
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
  	}
  	else {
  		$question = $dbEdQuestions->getFields();
  	}
  	// FRAGE pruefen
  	isset($_REQUEST[dbEducatedQuestions::field_name]) ? $name = $_REQUEST[dbEducatedQuestions::field_name] : $name = '';
  	isset($_REQUEST[dbEducatedQuestions::field_question]) ? $frage = $_REQUEST[dbEducatedQuestions::field_question] : $frage = '';
  	isset($_REQUEST[dbEducatedQuestions::field_group]) ? $group = $_REQUEST[dbEducatedQuestions::field_group] : $group = -1;
  	if ((isset($_REQUEST[dbEducatedQuestions::field_date_start])) && (!empty($_REQUEST[dbEducatedQuestions::field_date_start]))) {
  		// START Datum gesetzt
  		if (($dt = strtotime($_REQUEST[dbEducatedQuestions::field_date_start])) === false) {
			  $date_start = '0000-00-00 00:00:00';
			  $error = true;
			  $message .= sprintf(ed_msg_invalid_date, $_REQUEST[dbEducatedQuestions::field_date_start]);
			}
			else {
				$date_start = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m', $dt), date('d', $dt), date('Y', $dt)));
			}
  	}
  	else {
  		$date_start = '0000-00-00 00:00:00';
  	}
  	if ((isset($_REQUEST[dbEducatedQuestions::field_date_stop])) && (!empty($_REQUEST[dbEducatedQuestions::field_date_stop]))) {
  		// START Datum gesetzt
  		if (($dt = strtotime($_REQUEST[dbEducatedQuestions::field_date_stop])) === false) {
			  $date_stop = '0000-00-00 00:00:00';
			  $error = true;
			  $message .= sprintf(ed_msg_invalid_date, $_REQUEST[dbEducatedQuestions::field_date_stop]);
			}
			else {
				$date_stop = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m', $dt), date('d', $dt), date('Y', $dt)));
			}
  	}
  	else {
  		$date_stop = '0000-00-00 00:00:00';
  	}
  	isset($_REQUEST[dbEducatedQuestions::field_status]) ? $status = $_REQUEST[dbEducatedQuestions::field_status] : $status = dbEducatedQuestions::status_active;
  	// auf Fehler pruefen
  	if (empty($name)) {
  		$message .= ed_msg_quest_name_empty;
  	}
  	elseif ($name != $question[dbEducatedQuestions::field_name]) {
  		// pruefen, ob der Bezeichner bereits existiert
  		$SQL = sprintf(	"SELECT * FROM %s WHERE LOWER(%s) = '%s' AND %s!='%s' AND %s!='%s'",
  										$dbEdQuestions->getTableName(),
  										dbEducatedQuestions::field_name,
  										strtolower($name),
  										dbEducatedQuestions::field_id,
  										$id,
  										dbEducatedQuestions::field_status,
  										dbEducatedQuestions::status_deleted);
  		$check = array();
  		if (!$dbEdQuestions->sqlExec($SQL, $check)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdQuestions->getError()));
  			return false;
  		}
  		if (sizeof($check) > 0) {
  			// Bezeichner wird bereits verwendet
  			$error = true;
  			$message .= sprintf(ed_msg_quest_name_exists, $check[0][dbEducatedQuestions::field_name]);
  		}
  		else {
  			$update = true;
  		}
  	}
  	if (empty($frage)) {
  		$message .= ed_msg_quest_question_empty;
  	}
  	elseif ($frage != $question[dbEducatedQuestions::field_question]) {
  		$update = true;
  	}
  	if ($group == -1) {
  		$error = true;
  		$message .= ed_msg_quest_group_select;
  	}
  	elseif ($group != $question[dbEducatedQuestions::field_group]) {
  		$update = true;
  	}
  	if ($date_start != $question[dbEducatedQuestions::field_date_start]) {
  		$update = true;
  	}
  	if ($date_stop != $question[dbEducatedQuestions::field_date_stop]) {
  		$update = true;
  	}
  	if ($status != $question[dbEducatedQuestions::field_status]) {
  		$update = true;
  	}

  	// Datensatz schreiben?
  	if ($error) {
  		// keine Aktion wegen einem Fehler
  		$message .= ed_msg_quest_error;
  	}
  	elseif ($update) {
  		// Datensatz vorbereiten
  		$data = array();
  		$data[dbEducatedQuestions::field_name] = $name;
  		$data[dbEducatedQuestions::field_question] = $frage;
  		$data[dbEducatedQuestions::field_group] = $group;
  		if ($date_start != '0000-00-00 00:00:00') {
  			$data[dbEducatedQuestions::field_date_start] = $date_start;
  		}
  		else {
  			$data[dbEducatedQuestions::field_date_start] = '0000-00-00 00:00:00';
  		}
  		if ($date_stop != '0000-00-00 00:00:00') {
  			$data[dbEducatedQuestions::field_date_stop] = $date_stop;
  		}
  		else {
  			$data[dbEducatedQuestions::field_date_stop] = '0000-00-00 00:00:00';
  		}
  		$data[dbEducatedQuestions::field_status] = $status;
  		$data[dbEducatedQuestions::field_update_when] = date('Y-m-d H:i:s');
  		$data[dbEducatedQuestions::field_update_by] = isset($_SESSION['DISPLAY_NAME']) ? $_SESSION['DISPLAY_NAME'] : 'SYSTEM';

  		if ($id == -1) {
	  		// neuen Datensatz einfuegen
	  		$data[dbEducatedQuestions::field_created_when] = date('Y-m-d H:i:s');
  			if (!$dbEdQuestions->sqlInsertRecord($data, $id)) {
	  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdQuestions->getError()));
	  			return false;
	  		}
	  		$message .= sprintf(ed_msg_quest_insert_data, $name, $id);
	  		$_REQUEST[dbEducatedQuestions::field_id] = $id;
  		}
  		else  {
  			// Datensatz aktualisieren
  			$where = array();
  			$where[dbEducatedQuestions::field_id] = $id;
  			if (!$dbEdQuestions->sqlUpdateRecord($data, $where)) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdQuestions->getError()));
	  			return false;
  			}
  			$message .= sprintf(ed_msg_quest_update_data, $name, $id);
  		}
  		unset($_REQUEST[dbEducatedQuestions::field_name]);
  		unset($_REQUEST[dbEducatedQuestions::field_question]);
  		unset($_REQUEST[dbEducatedQuestions::field_group]);
  		unset($_REQUEST[dbEducatedQuestions::field_date_start]);
  		unset($_REQUEST[dbEducatedQuestions::field_date_stop]);
  		unset($_REQUEST[dbEducatedQuestions::field_status]);
  	}
  	elseif (!$update) {
  		$message .= ed_msg_quest_no_change;
  	}
  	// Flags zuruecksetzen
  	$update = false;
  	$error = false;

  	// ANTWORTEN AUSLESEN
		$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s!='%s'",
										$dbEdItems->getTableName(),
										dbEducatedItems::field_question_id,
										$id,
										dbEducatedItems::field_status,
										dbEducatedItems::status_deleted);
		$replies = array();
		if (!$dbEdItems->sqlExec($SQL, $replies)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdItems->getError()));
			return false;
		}
		$max_replies = $dbEdCfg->getValue(dbEducatedConfig::cfgRepliesCount);
		if (sizeof($replies) > $max_replies) {
			$max_replies = sizeof($replies);
		}
		if (isset($_REQUEST[dbEducatedItems::field_truth])) {
			// Genau EINE Antwort, Antworten weiter abarbeiten
			for ($i=0; $i < $max_replies; $i++) {
				$update = false;
				$item_error = false;
				if ((isset($_REQUEST[dbEducatedItems::field_id.'_'.$i])) && ($_REQUEST[dbEducatedItems::field_id.'_'.$i] == -1)) {
					// neuer Datensatz
					$item = $dbEdItems->getFields();
					$item[dbEducatedITems::field_id] = -1; // neuer Datensatz
					$item[dbEducatedItems::field_truth] = dbEducatedItems::truth_false;
					$item[dbEducatedItems::field_status] = dbEducatedItems::status_active;
				}
				else {
					// Datensatz existiert bereits
					$where = array();
					$where[dbEducatedItems::field_id] = $_REQUEST[dbEducatedItems::field_id.'_'.$i];
					$item = array();
					if (!$dbEdItems->sqlSelectRecord($where, $item)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdItems->getError()));
						return false;
					}
					if (sizeof($item) < 1) {
						$this->setError(sprintf(ed_error_answer_id_not_exists, __METHOD__, __LINE__, $_REQUEST[dbEducatedItems::field_id.'_'.$i]));
						return false;
					}
					$item = $item[0];
				}
				// Felder durchlaufen und vergleichen
				($_REQUEST[dbEducatedItems::field_truth] == $i) ? $truth = dbEducatedItems::truth_true : $truth = dbEducatedItems::truth_false;
				if ($truth != $item[dbEducatedItems::field_truth]) {
					$update = true;
					$item[dbEducatedItems::field_truth] = $truth;
				}
				// ANTWORT
				if ((isset($_REQUEST[dbEducatedItems::field_answer.'_'.$i])) && (!empty($_REQUEST[dbEducatedItems::field_answer.'_'.$i]))) {
					if ($_REQUEST[dbEducatedItems::field_answer.'_'.$i] != $item[dbEducatedItems::field_answer]) {
						$update = true;
						$item[dbEducatedItems::field_answer] = $_REQUEST[dbEducatedItems::field_answer.'_'.$i];
					}
				}
				else {
					// keine Antwort gesetzt
					$error = true;
					$item_error = true;
					$message .= sprintf(ed_msg_answer_answer_empty, $i+1);
				}
				// Erklaerung
				if ((isset($_REQUEST[dbEducatedItems::field_explain.'_'.$i])) && (!empty($_REQUEST[dbEducatedItems::field_explain.'_'.$i]))) {
					if ($_REQUEST[dbEducatedItems::field_explain.'_'.$i] != $item[dbEducatedItems::field_explain]) {
						$update = true;
						$item[dbEducatedItems::field_explain] = $_REQUEST[dbEducatedItems::field_explain.'_'.$i];
					}
				}
				else {
					$error = true;
					$item_error = true;
					$message .= sprintf(ed_msg_answer_explain_empty, $i+1);
				}
				if ($update && !$item_error) {
					// Datensatz ist neu oder wurde geaendert
					$item_id = $item[dbEducatedItems::field_id];
					unset($item[dbEducatedItems::field_id]);
					$item[dbEducatedItems::field_update_by] = isset($_SESSION['DISPLAY_NAME']) ? $_SESSION['DISPLAY_NAME'] : 'SYSTEM';
					$item[dbEducatedItems::field_update_when] = date('Y-m-d H:i:s');
					$item[dbEducatedItems::field_question_id] = $id;
					if ($item_id == -1) {
						// neuer Datensatz
						if (!$dbEdItems->sqlInsertRecord($item, $item_id)) {
							$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdItems->getError()));
							return false;
						}
						$message .= sprintf(ed_msg_answer_id_insert, $item_id);
					}
					else {
						// Datensatz aktualisieren
						$where = array();
						$where[dbEducatedItems::field_id] = $item_id;
						if (!$dbEdItems->sqlUpdateRecord($item, $where)) {
							$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdItems->getError()));
							return false;
						}
						$message .= sprintf(ed_msg_answer_id_update, $item_id);
					}
					// $_REQUEST zuruecksetzen
					unset($_REQUEST[dbEducatedItems::field_answer.'_'.$i]);
					unset($_REQUEST[dbEducatedItems::field_id.'_'.$i]);
					unset($_REQUEST[dbEducatedItems::field_explain.'_'.$i]);
				}
			} // Antworten durchlaufen
		}
  	else {
			// es muss genau EINE Antwort als richtig festgelegt werden
			$error = true;
			$message .= ed_msg_answer_truth_count;
		}
		// FEHLER?
		if ($error) {
			// Aufgrund von Fehler den Status der Frage auf GESPERRT setzen
			$where = array();
			$where[dbEducatedQuestions::field_id] = $id;
			$data = array();
			$data[dbEducatedQuestions::field_status] = dbEducatedQuestions::status_locked;
			$data[dbEducatedQuestions::field_update_by] = 'SYSTEM';
			$data[dbEducatedQuestions::field_update_when] = date('Y-m-d H:i:s');
			if (!$dbEdQuestions->sqlUpdateRecord($data, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdQuestions->getError()));
				return false;
			}
			$message .= sprintf(ed_msg_quest_error_locked, $id);
		}
		$this->setMessage($message);
  	return $this->dlgQuestionEdit();
  } // QuestionEditCheck()

  public function dlgQuestionList() {
  	global $dbEdQuestions;
  	global $dbEdGroups;
  	global $parser;

  	$SQL = sprintf(	"SELECT * FROM %s WHERE %s!='%s'",
  									$dbEdQuestions->getTableName(),
  									dbEducatedQuestions::field_status,
  									dbEducatedQuestions::status_deleted);
  	$questions = array();
  	if (!$dbEdQuestions->sqlExec($SQL, $questions)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdQuestions->getError()));
  		return false;
  	}
  	if (sizeof($questions) < 1) {
  		// es sind (noch) keine Fragen vorhanden
  		$this->setMessage(ed_msg_quest_no_list);
  	}
  	// Gruppen auslesen
  	$where = array();
  	$grps = array();
  	if (!$dbEdGroups->sqlSelectRecord($where, $grps)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdGroups->getError()));
  		return false;
  	}
  	$groups = array();
  	foreach ($grps as $grp) {
  		$groups[$grp[dbEducatedGroups::field_id]] = $grp[dbEducatedGroups::field_name];
  	}

  	$items = '';
 //$header = '<tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr>';
 //$row = '<tr class="%s"><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>';
  	$row = new Dwoo_Template_File($this->template_path.'backend.question.list.row.htt');
  	$data = array(
  		'id'				=> ed_label_id,
  		'locked'		=> '',
  		'created'		=> ed_label_created_when,
  		'group'			=> ed_label_question_group,
  		'name'			=> ed_label_question_name,
  		'question'	=> ed_label_question_question,
  		'start'			=> ed_label_date_from,
  		'end'				=> ed_label_date_to
  	);
  	$items .= $parser->get($this->template_path.'backend.question.list.header.htt', $data);
  	$flipFlop = true;
  	foreach ($questions as $question) {
  		if ($flipFlop) {
  		  $flipFlop = false; $flip = 'flip';
  		}
  		else {
  		  $flipFlop = true; $flip = 'flop';
  		}
  		if ($question[dbEducatedQuestions::field_status] == dbEducatedQuestions::status_locked) {
  			$locked = sprintf('<img src="%s" width="9" height="14" />', WB_URL . '/modules/' . basename(dirname(__FILE__)) . '/images/locked.png');
  		}
  		else {
  			$locked = '';
  		}
  		if ($question[dbEducatedQuestions::field_date_start] != '0000-00-00 00:00:00') {
  			$date_start = date('d.m.Y', strtotime($question[dbEducatedQuestions::field_date_start]));
  		}
  		else {
  			$date_start = '';
  		}
  		if ($question[dbEducatedQuestions::field_date_stop] != '0000-00-00 00:00:00') {
  			$date_stop = date('d.m.Y', strtotime($question[dbEducatedQuestions::field_date_stop]));
  		}
  		else {
  			$date_stop = '';
  		}
  		$data = array(
  			'class'			=> $flip,
  			'id'				=> sprintf('#%05d', $question[dbEducatedQuestions::field_id]),
  			'locked'		=> $locked,
  			'created'		=> date('d.m.Y', strtotime($question[dbEducatedQuestions::field_created_when])),
  			'group'			=> $groups[$question[dbEducatedQuestions::field_group]],
  			'name'			=> sprintf(	'<a href="%s&%s=%s&%s=%s">%s</a>',
  															$this->page_link,
  															self::request_action,
  															self::action_question_edit,
  															dbEducatedQuestions::field_id,
  															$question[dbEducatedQuestions::field_id],
  															$question[dbEducatedQuestions::field_name]),
  			'question'	=> $question[dbEducatedQuestions::field_question],
  			'start'			=> $date_start,
  			'end'				=> $date_stop
  		);
  		$items .= $parser->get($row, $data);
  	}
  	// Mitteilungen anzeigen
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', ed_intro_question_list);
		}
  	$data = array(
  		'header'				=> ed_header_list,
  		'intro'					=> $intro,
  		'items'					=> $items
  	);
  	return $parser->get($this->template_path.'backend.question.list.htt', $data);
  } // dlgQuestionList()

  /**
   * Dialog zum Bearbeiten und Hinzufuegen von Gruppen
   *
   * @return STR DIALOG
   */
  public function dlgGroupsEdit() {
  	global $dbEdGroups;
  	global $parser;

  	$form_name = 'grp_edit';
  	((isset($_REQUEST[dbEducatedGroups::field_id])) && (!empty($_REQUEST[dbEducatedGroups::field_id]))) ? $gid = $_REQUEST[dbEducatedGroups::field_id] : $gid = -1;
  	// Daten auslesen
  	$groups = array();
  	$SQL = sprintf("SELECT * FROM %s WHERE %s!=%s", $dbEdGroups->getTableName(), dbEducatedGroups::field_status, dbEducatedGroups::status_deleted);
  	if (!$dbEdGroups->sqlExec($SQL, $groups)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdGroups->getError()));
  		return false;
  	}
  	$items = '';
  	$row = new Dwoo_Template_File($this->template_path.'backend.groups.edit.row.htt');
  	// Kopfzeile einfuegen
  	$data = array(
  		'id'		 => ed_label_id,
  		'group'	 => ed_label_group_name,
  		'desc'	 => ed_label_group_description,
  		'status' => ed_label_status,
  		'update' => ed_label_last_update
  	);
  	$items .= $parser->get($this->template_path.'backend.groups.edit.header.htt', $data);

		// vorhandene Gruppen auflisten
  	foreach ($groups as $group) {
  		$id = $group[dbEducatedGroups::field_id];
  		(isset($_REQUEST[dbEducatedGroups::field_id.'_'.$id])) ? $name = $_REQUEST[dbEducatedGroups::field_name.'_'.$id] : $name = $group[dbEducatedGroups::field_name];
  		(isset($_REQUEST[dbEducatedGroups::field_description.'_'.$id])) ? $desc = $_REQUEST[dbEducatedGroups::field_description.'_'.$id] : $desc = $group[dbEducatedGroups::field_description];
  		$status = '';
  		(isset($_REQUEST[dbEducatedGroups::field_status.'_'.$id])) ? $stat = $_REQUEST[dbEducatedGroups::field_status.'_'.$id] : $stat = $group[dbEducatedGroups::field_status];
  		foreach ($dbEdGroups->status_array as $key => $value) {
 				($key == $stat) ? $selected = ' selected="selected"' : $selected = '';
 				$status .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $value);
  		}
  		$status = sprintf('<select name="%s">%s</select>', dbEducatedGroups::field_status.'_'.$id, $status);
  		$data = array(
  			'id'			=> sprintf('#%05d', $id),
  			'group'		=> sprintf('<input type="text" name="%s" value="%s" />', dbEducatedGroups::field_name.'_'.$id, $name),
  			'desc'		=> sprintf('<textarea name="%s">%s</textarea>', dbEducatedGroups::field_description.'_'.$id, $desc),
  			'status'	=> $status,
  			'update'	=> sprintf(ed_str_changed_by, $group[dbEducatedGroups::field_update_by], $dbEdGroups->mySQLdate2datum($group[dbEducatedGroups::field_update_when]))
  		);
  		$items .= $parser->get($row, $data);
  	}
  	// neue Gruppe hinzufuegen
  	$items .= $parser->get($this->template_path.'backend.groups.edit.add.htt', array('intro' => ed_intro_add_group));

  	(isset($_REQUEST[dbEducatedGroups::field_name.'_add'])) ? $name = $_REQUEST[dbEducatedGroups::field_name.'_add'] : $name = '';
  	(isset($_REQUEST[dbEducatedGroups::field_description.'_add'])) ? $desc = $_REQUEST[dbEducatedGroups::field_description.'_add'] : $desc = '';
  	$data = array(
  		'id'			=> '',
  		'group'		=> sprintf('<input type="text" name="%s" value="%s" />', dbEducatedGroups::field_name.'_add', $name),
  		'desc'		=> sprintf('<textarea name="%s">%s</textarea>', dbEducatedGroups::field_description.'_add', $desc),
  		'status'	=> '',
  		'update'	=> ''
  	);
  	$items .= $parser->get($row, $data);
  	// Mitteilungen anzeigen
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', ed_intro_groups_edit);
		}
  	$data = array(
  		'header'						=> ed_header_question_edit,
  		'intro'							=> $intro,
  		'form_name'					=> $form_name,
  		'form_action'				=> $this->page_link,
  		'action_name'				=> self::request_action,
  		'action_value'			=> self::action_groups_check,
  		'id_name'						=> dbEducatedQuestions::field_id,
  		'id_value'					=> $gid,
  		'items'							=> $items,
  		'btn_ok'						=> ed_btn_ok,
  		'btn_abort'					=> ed_btn_abort,
  		'abort_location'		=> $this->page_link
  	);
  	return $parser->get($this->template_path.'backend.groups.edit.htt', $data);
  } // dlgGroupsEdit()

  /**
   * Prueft Aenderungen an Gruppen und Neueintraege
   */
  public function GroupsEditCheck() {
  	global $dbEdGroups;

  	$message = '';
  	// Pruefen, ob Aenderungen an bestehenden Gruppen durchgefuehrt wurden
  	$groups = array();
  	$SQL = sprintf("SELECT * FROM %s WHERE %s!=%s", $dbEdGroups->getTableName(), dbEducatedGroups::field_status, dbEducatedGroups::status_deleted);
  	if (!$dbEdGroups->sqlExec($SQL, $groups)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdGroups->getError()));
  		return false;
  	}
  	foreach ($groups as $group) {
  		$update = false;
  		$id = $group[dbEducatedGroups::field_id];
  		(isset($_REQUEST[dbEducatedGroups::field_name.'_'.$id])) ? $name = $_REQUEST[dbEducatedGroups::field_name.'_'.$id] : $name = '';
  		if (strlen($name) < 4) {
  			$message .= sprintf(ed_msg_group_name_too_short_up, $id);
  		}
  		elseif ($name != $group[dbEducatedGroups::field_name]) {
  			// Bezeichner wurde geaendert
  			$SQL = sprintf(	"SELECT * FROM %s WHERE LOWER(%s) = '%s' AND %s!='%s' AND %s!='%s'",
  											$dbEdGroups->getTableName(),
  											dbEducatedGroups::field_name,
  											strtolower($name),
  											dbEducatedGroups::field_status,
  											dbEducatedGroups::status_deleted,
  											dbEducatedGroups::field_id,
  											$id);
  			$result = array();
				if (!$dbEdGroups->sqlExec($SQL, $result)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdGroups->getError()));
					return false;
				}
				if (sizeof($result) > 0) {
					$message .= sprintf(ed_msg_group_name_exists, $result[0][dbEducatedGroups::field_name]);
				}
				else {
					$update = true;
				}
  		}
  		// Beschreibung geaendert?
  		(isset($_REQUEST[dbEducatedGroups::field_description.'_'.$id])) ? $desc = $_REQUEST[dbEducatedGroups::field_description.'_'.$id] : $desc = '';
  		if (empty($desc)) {
  			$message .= sprintf(ed_msg_group_desc_empty_up, $id);
  		}
  		elseif ($desc != $group[dbEducatedGroups::field_description]) {
  			$update = true;
  		}
  		// Status geaendert?
  		(isset($_REQUEST[dbEducatedGroups::field_status.'_'.$id])) ? $status = $_REQUEST[dbEducatedGroups::field_status.'_'.$id] : $status = dbEducatedGroups::status_active;
  		if ($status != $group[dbEducatedGroups::field_status]) {
  			$message .= sprintf(ed_msg_group_status_changed, $group[dbEducatedGroups::field_name], $dbEdGroups->status_array[$status]);
  			$update = true;
  		}
  		if ($update) {
  			// Datensatz aendern
  			$where = array();
  			$where[dbEducatedGroups::field_id] = $id;
  			$data = array();
  			$data[dbEducatedGroups::field_name] = $name;
  			$data[dbEducatedGroups::field_description] = $desc;
  			$data[dbEducatedGroups::field_status] = $status;
  			$data[dbEducatedGroups::field_update_by] = isset($_SESSION['DISPLAY_NAME']) ? $_SESSION['DISPLAY_NAME'] : 'SYSTEM';
  			$data[dbEducatedGroups::field_update_when] = date('Y-m-d H:i:s');
  			if (!$dbEdGroups->sqlUpdateRecord($data, $where)) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdGroups->getError()));
  				return false;
  			}
  			$message .= sprintf(ed_msg_group_updated, $name);
  		}
  		unset($_REQUEST[dbEducatedGroups::field_name.'_'.$id]);
  		unset($_REQUEST[dbEducatedGroups::field_description.'_'.$id]);
  		unset($_REQUEST[dbEducatedGroups::field_status.'_'.$id]);
  	}

  	// Neue Gruppe?
  	if ((isset($_REQUEST[dbEducatedGroups::field_name.'_add'])) && (!empty($_REQUEST[dbEducatedGroups::field_name.'_add']))) {
  		// es soll eine neue Gruppe hinzugefuegt werden
  		$add = true;
  		$name = $_REQUEST[dbEducatedGroups::field_name.'_add'];
  		if (strlen($name) < 4) {
  			// Der Gruppen Name sollte mindestens 3 Zeichen enthalten
  			$message .= ed_msg_group_name_too_short;
  			$add = false;
  		}
  		(isset($_REQUEST[dbEducatedGroups::field_description.'_add'])) ? $desc = $_REQUEST[dbEducatedGroups::field_description.'_add'] : $desc = '';
  		if (empty($desc)) {
  			// Leere Beschreibung
  			$message .= ed_msg_group_desc_empty;
  			$add = false;
  		}
  		if ($add) {
  			// Pruefen, ob der Name fuer diese Gruppe bereits verwendet wird
  			$SQL = sprintf(	"SELECT * FROM %s WHERE LOWER(%s) = '%s' AND %s!='%s'",
  											$dbEdGroups->getTableName(),
  											dbEducatedGroups::field_name,
  											strtolower($name),
  											dbEducatedGroups::field_status,
  											dbEducatedGroups::status_deleted);
  			$result = array();
				if (!$dbEdGroups->sqlExec($SQL, $result)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdGroups->getError()));
					return false;
				}
				if (sizeof($result) > 0) {
					$message .= sprintf(ed_msg_group_name_exists, $result[0][dbEducatedGroups::field_name]);
					$add = false;
				}
				if ($add) {
	  			// neuen Datensatz einfuegen
	  			unset($_REQUEST[dbEducatedGroups::field_name.'_add']);
	  			unset($_REQUEST[dbEducatedGroups::field_description.'_add']);
	  			$data = array();
	  			$data[dbEducatedGroups::field_name] = $name;
	  			$data[dbEducatedGroups::field_description] = $desc;
	  			$data[dbEducatedGroups::field_status] = dbEducatedGroups::status_active;
	  			$data[dbEducatedGroups::field_update_by] = isset($_SESSION['DISPLAY_NAME']) ? $_SESSION['DISPLAY_NAME'] : 'SYSTEM';
	  			$data[dbEducatedGroups::field_update_when] = date('Y-m-d H:i:s');
	  			$new_id = -1;
	  			if (!$dbEdGroups->sqlInsertRecord($data, $new_id)) {
	  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdGroups->getError()));
	  				return false;
	  			}
	  			$message .= sprintf(ed_msg_group_add_group, $name, $new_id);
				}
  		}
  	}
  	// Mitteilungen uebernehmen
  	$this->setMessage($message);
  	return $this->dlgGroupsEdit();
  } // GroupsEditCheck()

  /**
	 * Dialog zum Bearbeiten der Konfigurationseinstellungen
	 *
	 * @return STR Dialog
	 */
	public function editConfig() {
		global $parser;
		global $dbEdCfg;

		$SQL = sprintf(	"SELECT * FROM %s WHERE NOT %s='%s' ORDER BY %s",
										$dbEdCfg->getTableName(),
										dbEducatedConfig::field_status,
										dbEducatedConfig::status_deleted,
										dbEducatedConfig::field_name);
		$config = array();
		if (!$dbEdCfg->sqlExec($SQL, $config)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdCfg->getError()));
			return false;
		}
		$count = array();
		$data = array(
			'name'	=> '',
			'value'	=> ed_header_value,
			'desc'	=> ed_header_description
		);
		$items = $parser->get($this->template_path.'backend.cfg.header.htt', $data);

		$row = new Dwoo_Template_File($this->template_path.'backend.cfg.row.htt');

		// bestehende Eintraege auflisten
		foreach ($config as $entry) {
			$id = $entry[dbEducatedConfig::field_id];
			$count[] = $id;
			$label = constant($entry[dbEducatedConfig::field_label]);
			$bezeichner = $entry[dbEducatedConfig::field_name];
			$typ = $dbEdCfg->type_array[$entry[dbEducatedConfig::field_type]];
			(isset($_REQUEST[dbEducatedConfig::field_value.'_'.$id])) ?
				$val = $_REQUEST[dbEducatedConfig::field_value.'_'.$id] :
				$val = $entry[dbEducatedConfig::field_value];
			$value = sprintf(	'<input type="text" name="%s_%s" value="%s" />', dbEducatedConfig::field_value, $id,	$val);
			$desc = constant($entry[dbEducatedConfig::field_description]);
			$data = array(
				'name'	=> $label,
				'value'	=> $value,
				'desc'	=> $desc
			);
			$items .= $parser->get($row, $data);
//$items .= sprintf($row, $label, $bezeichner, $typ, $value, $desc);
		}
		$items_value = implode(",", $count);

		// Mitteilungen anzeigen
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', ed_intro_cfg);
		}
		$data = array(
			'form_name'						=> 'konfiguration',
			'form_action'					=> $this->page_link,
			'action_name'					=> self::request_action,
			'action_value'				=> self::action_cfg_check,
			'items_name'					=> self::request_items,
			'items_value'					=> $items_value,
			'header'							=> ed_header_config,
			'intro'								=> $intro,
			'items'								=> $items,
			'btn_ok'							=> ed_btn_ok,
			'btn_abort'						=> ed_btn_abort,
			'abort_location'			=> $this->page_link
		);
		return $parser->get($this->template_path.'backend.cfg.htt', $data);
	} // editConfig()

	/**
	 * Ueberprueft Aenderungen die im Dialog editKonfiguration() vorgenommen wurden
	 * und aktualisiert die entsprechenden Datensaetze.
	 * Fuegt neue Datensaetze ein.
	 *
	 * @return STR DIALOG editConfig()
	 */
	public function checkConfig() {
		global $dbEdCfg;

		$message = '';
		// ueberpruefen, ob ein Eintrag geaendert wurde
		if ((isset($_REQUEST[self::request_items])) && (!empty($_REQUEST[self::request_items]))) {
			$ids = explode(",", $_REQUEST[self::request_items]);
			foreach ($ids as $id) {
				if (isset($_REQUEST[dbEducatedConfig::field_value.'_'.$id])) {
					$value = $_REQUEST[dbEducatedConfig::field_value.'_'.$id];
					$where = array();
					$where[dbEducatedConfig::field_id] = $id;
					$config = array();
					if (!$dbEdCfg->sqlSelectRecord($where, $config)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdCfg->getError()));
						return false;
					}
					if (sizeof($config) < 1) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(ed_error_cfg_id, $id)));
						return false;
					}
					$config = $config[0];
					if ($config[dbEducatedConfig::field_value] != $value) {
						// Wert wurde geaendert
						if (!$dbEdCfg->setValue($value, $id) && $dbEdCfg->isError()) {
							$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdCfg->getError()));
							return false;
						}
						elseif ($dbEdCfg->isMessage()) {
							$message .= $dbEdCfg->getMessage();
						}
						else {
							// Datensatz wurde aktualisiert
							$message .= sprintf(ed_msg_cfg_id_updated, $id, $config[dbEducatedConfig::field_name]);
						}
					}
				}
			}
		}
		// ueberpruefen, ob ein neuer Eintrag hinzugefuegt wurde
		if ((isset($_REQUEST[dbEducatedConfig::field_name])) && (!empty($_REQUEST[dbEducatedConfig::field_name]))) {
			// pruefen ob dieser Konfigurationseintrag bereits existiert
			$where = array();
			$where[dbEducatedConfig::field_name] = $_REQUEST[dbEducatedConfig::field_name];
			$where[dbEducatedConfig::field_status] = dbEducatedConfig::status_active;
			$result = array();
			if (!$dbEdCfg->sqlSelectRecord($where, $result)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdCfg->getError()));
				return false;
			}
			if (sizeof($result) > 0) {
				// Eintrag existiert bereits
				$message .= sprintf(ed_msg_cfg_add_exists, $where[dbEducatedConfig::field_name]);
			}
			else {
				// Eintrag kann hinzugefuegt werden
				$data = array();
				$data[dbEducatedConfig::field_name] = $_REQUEST[dbEducatedConfig::field_name];
				if (((isset($_REQUEST[dbEducatedConfig::field_type])) && ($_REQUEST[dbEducatedConfig::field_type] != dbEducatedConfig::type_undefined)) &&
						((isset($_REQUEST[dbEducatedConfig::field_value])) && (!empty($_REQUEST[dbEducatedConfig::field_value]))) &&
						((isset($_REQUEST[dbEducatedConfig::field_label])) && (!empty($_REQUEST[dbEducatedConfig::field_label]))) &&
						((isset($_REQUEST[dbEducatedConfig::field_description])) && (!empty($_REQUEST[dbEducatedConfig::field_description])))) {
					// Alle Daten vorhanden
					unset($_REQUEST[dbEducatedConfig::field_name]);
					$data[dbEducatedConfig::field_type] = $_REQUEST[dbEducatedConfig::field_type];
					unset($_REQUEST[dbEducatedConfig::field_type]);
					$data[dbEducatedConfig::field_value] = $_REQUEST[dbEducatedConfig::field_value];
					unset($_REQUEST[dbEducatedConfig::field_value]);
					$data[dbEducatedConfig::field_label] = $_REQUEST[dbEducatedConfig::field_label];
					unset($_REQUEST[dbEducatedConfig::field_label]);
					$data[dbEducatedConfig::field_description] = $_REQUEST[dbEducatedConfig::field_description];
					unset($_REQUEST[dbEducatedConfig::field_description]);
					$data[dbEducatedConfig::field_status] = dbEducatedConfig::status_active;
					$data[dbEducatedConfig::field_update_by] = isset($_SESSION['DISPLAY_NAME']) ? $_SESSION['DISPLAY_NAME'] : 'SYSTEM';
					$data[dbEducatedConfig::field_update_when] = date('Y-m-d H:i:s');
					$id = -1;
					if (!$dbEdCfg->sqlInsertRecord($data, $id)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdCfg->getError()));
						return false;
					}
					$message .= sprintf(ed_msg_cfg_add_success, $id, $data[dbEducatedConfig::field_name]);
				}
				else {
					// Daten unvollstaendig
					$message .= ed_msg_cfg_add_incomplete;
				}
			}
		}
		// Sollen Daten als CSV gesichert werden?
		if ((isset($_REQUEST[self::request_csv_export])) && ($_REQUEST[self::request_csv_export] == 1)) {
			// Daten sichern
			$where = array();
			$where[dbEducatedConfig::field_status] = dbEducatedConfig::status_active;
			$csv = array();
			$csvFile = WB_PATH.MEDIA_DIRECTORY.'/'.date('ymd-His').'-educated-cfg.csv';
			if (!$dbEdCfg->csvExport($where, $csv, $csvFile)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbEdCfg->getError()));
				return false;
			}
			$message .= sprintf(ed_msg_cfg_csv_export, basename($csvFile));
		}

		if (!empty($message)) $this->setMessage($message);
		return $this->editConfig();
	} // checkConfig()


} // class backendEducated

?>