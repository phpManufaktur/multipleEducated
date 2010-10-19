<?php

/**
 * multipleEducated - create quizzes like "Bildungshappen" for WebsiteBaker
 * 
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de/cms/topics/multipleeducated.php
 * @copyright 2009 - 2010
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id$
 * 
 * IMPORTANT NOTE:
 * 
 * If you are editing this file or creating a new language file
 * you must ensure that you SAVE THIS FILE UTF-8 ENCODED.
 * Otherwise all special chars will be destroyed and displayed improper!
 * 
 * It is NOT NECESSARY to mask special chars as HTML entities!
 * 
 * The Original Source is GERMAN (DE) by Ralf Hertsch, please use
 * always this file as reference for translations.
 */

// module description for WebsiteBaker backend information
$module_description 	= 'multipleEducatd - create and organize quizzes like "Bildungshappen" (ZEIT) for WebsiteBaker';

// name of the person(s) who translated and edited this language file
$module_translation_by = 'Armin Ipfelkofer';


define('ed_btn_abort',									'Abort');
define('ed_btn_ok',											'OK');
define('ed_btn_check',									'Correct?');
define('ed_btn_new_question',						'New question ...');
define('ed_btn_retry',									'Try again...');

define('ed_desc_cfg_question_shuffle',	'Select and display questions in random order.');
define('ed_desc_cfg_developer_mode',		'Enables programmer to add configuration parameters.');
define('ed_desc_cfg_replies_count',			'This parameter defines the number of possible answers.');

define('ed_error_addon_version',     		'<p>Fatal error: <b>Educated</b> requires Website Baker addon <b>%s</b> version <b>%01.2f</b> or higher - installed version is <b>%01.2f</b>.</p><p>Please update this addon!</p>');
define('ed_error_missing_addon',    		'<p>FFatal error: <b>Educated</b> requires Website Baker addon <b>%s</b>. Execution of program was terminated.</p>');
define('ed_error_question_id_not_exists','<p>[%s - %s] A question with the <b>ID #%05d</b> does not exist!</p>');
define('ed_error_answer_id_not_exists',	'<p>[%s - %s] The answer with the <b>ID #%05d</b> does not exist!</p>');
define('ed_error_cfg_id',								'<p>The configuration datta record with the <b>ID #%05d</b> cannot be read!</p>');
define('ed_error_cfg_name',							'<p>No configuration data record found for identifier <b>%s</b>!</p>');
define('ed_error_random_fail',					'<p>Es liegt leider keine Frage vor.</p>');
define('ed_error_date_random_fail',			'<p>Sorry, no question available for <b>today</b>.</p>');
define('ed_error_to_less_answers',			'<p>Number of questions determined does not match default number!</p>');
define('ed_error_question_id_missing',	'<p>[%s - %s] Question ID not set - answer cannot be edited.</p>');

define('ed_header_question_edit',				'Edit question');
define('ed_header_prompt_error',    		'[Educated] error message');
define('ed_header_identifier',					'Identifier');
define('ed_header_typ',									'Type');
define('ed_header_value',								'Value');
define('ed_header_description',					'Description');
define('ed_header_label',								'Label');
define('ed_header_config',							'Configuration');
define('ed_header_list',								'Catalogue');
define('ed_header_question',						'Do you know the answer?');
define('ed_header_answer',							'Solution');

define('ed_intro_question_edit',				'Dialogue to create and edit questions');
define('ed_intro_question_list',				'Select the question to be edited.');
define('ed_intro_groups_edit',					'Dialogue to create and edit groups');
define('ed_intro_add_group',						'Add a new group');
define('ed_intro_cfg_add_item',					'<p>Adding entries to the configuration does only make sense when the values set correspond with the program.</p>');
define('ed_intro_cfg',									'<p>Edit configuration of multipeEducated.</p>');
define('ed_intro_answer_edit',					'<p>Specifiy <b>%d</b> answers for the above mentioned question and select exactly one as the correct answer.</p><p>In addition explain, why each individual answer is wrong or right!</p>');
define('ed_intro_question',							'Do you know the correct answer?');

define('ed_label_id',										'ID');
define('ed_label_group',								'Group');
define('ed_label_date_from',						'valid from');
define('ed_label_date_to',							'valid till');
define('ed_label_created_when',					'created');
define('ed_label_group_name',						'Identifier');
define('ed_label_group_description',		'Description');
define('ed_label_status',								'Status');
define('ed_label_last_update',					'Actualized');
define('ed_label_cfg_question_shuffle',	'Selection of questions in random order');
define('ed_label_csv_export',						'Save configuration data in a CSV-file in directory /MEDIA');
define('ed_label_cfg_developer_mode',		'Programming mode');
define('ed_label_cfg_replies_count',		'Number of answers');
define('ed_label_question_name',				'Identifier');
define('ed_label_question_question',		'Question');
define('ed_label_question_group',				'Group');
define('ed_label_question_date_range',	'Validity time range');
define('ed_label_question_status',			'Status');
define('ed_label_answer_answer_no',			'Answer <b>#%02d</b>');
define('ed_label_answer_explain_no',		'Explanation <b>#%02d</b>');
define('ed_label_answer_status_no',			'Status <b>#%02d</b>');
define('ed_label_answer_truth',					'<b>Correct?</b>');
define('ed_msg_group_name_too_short',		'<p>Group identifier is too short! A minimum of three characters is required!</p>');
define('ed_msg_group_desc_empty',				'<p>Please enter a description for the group!</p>');
define('ed_msg_group_desc_empty_up',		'<p>The description field for group <b>ID #%05d</b> must not be empty.</p>');
define('ed_msg_group_add_group',				'<p>Group <b>%s</b> with <b>ID #%05d</b> has been added.</p>');
define('ed_msg_group_name_exists',			'<p>A group with the identifier <b>%s</b> is already existing. Please define another identifier!</p>');
define('ed_msg_group_name_too_short_up','<p>Identifier for group <b>ID #%05d</b> is too short! A minimum of three characters is required!</p>');
define('ed_msg_group_status_changed',		'<p>Status of group <b>%s</b> has been changed to  <b>%s</b>.</p>');
define('ed_msg_group_updated',					'<p>Group with identifier <b>%s</b> has been updated.</p>');
define('ed_msg_group_not_defined',			'<p>No groups defined yet. Please define at least one group.</p>');
define('ed_msg_cfg_id_updated',					'<p>Configuration data record with <b>ID #%05d</b> and identifier <b>%s</b> has been updated.</p>');
define('ed_msg_cfg_add_exists',					'<p>Configuration data record with identifier <b>%s</b> is already existing and cannot be added again!</p>');
define('ed_msg_cfg_add_success',				'<p>Configuration data record with <b>ID #%05d</b> and identifier <b>%s</b> has been added.</p>');
define('ed_msg_cfg_add_incomplete',			'<p>The configuration data record just added is incomplete! Please check your entries!</p>');
define('ed_msg_cfg_csv_export',					'<p>Configuration data have been saved as <b>%s</b> in directory /MEDIA.</p>');
define('ed_msg_invalid_date',						'<p>Date <b>%s</b> is invalid. Please checck your entry!</p>');
define('ed_msg_quest_name_empty',				'<p>Identifier field must not be empty!</p>');
define('ed_msg_quest_question_empty',		'<p>You forgot to define a question.</p>');
define('ed_msg_quest_group_select',			'<p>Please select the group this question is to be assigned to.</p>');
define('ed_msg_quest_name_exists',			'<p>A question with the identifier <b>%s</b> is already existing. Please define another identifier!</p>');
define('ed_msg_quest_no_list',					'<p>There are no questions available (yet) to be displayed.</p>');
define('ed_msg_quest_error',						'<p style="color:#800000;">Question could not be inserted resp. updated. Errors occured during internal check!</p>');
define('ed_msg_quest_insert_data',			'<p>Question with identifier <b>%s</b> and <b>ID #%05d</b> has been added.</p>');
define('ed_msg_quest_update_data',			'<p>Question with identifier <b>%s</b> and <b>ID #%05d</b> has been updated.</p>');
define('ed_msg_quest_no_change',				'<p>Question has not been updated!</p>');
define('ed_msg_quest_error_locked',			'<p style="color:#800000;">Question with <b>ID #%05d</b> has been <b>LOCKED</b>! There are either logical errors or required entries are not complete!</p>');
define('ed_msg_answer_truth_count',			'<p>Please check your entries! Exactly one answer has to defined as correct!!</p>');
define('ed_msg_answer_answer_empty',		'<p>Answer <b>#%02d</b> must not be empty!</p>');
define('ed_msg_answer_explain_empty',		'<p>Description for answer <b>#%02d</b> must not be empty!</p>');
define('ed_msg_answer_id_insert',				'<p>Answer with <b>ID #%02d</b> has been added.</p>');
define('ed_msg_answer_id_update',				'<p>Answer with <b>ID #%02d</b> has been updated.</p>');
define('ed_msg_answer_no_selection',		'<p>You forgot to select an answer!</p>');

define('ed_status_active',							'Active');
define('ed_status_deleted',							'Deleted');
define('ed_status_locked',							'Locked');

define('ed_str_true',										'Correct');
define('ed_str_false',									'Wrong');
define('ed_str_changed_by',							'%s, at %s');
define('ed_str_undefined',							'- not defined -');
define('ed_str_select',									'- please select -');
define('ed_str_valid_from',							'from');
define('ed_str_valid_to',								'till');
define('ed_str_answer_repeat',					'<p><b>Your answer is:</b>&nbsp;%s</p>');
define('ed_str_answer_good',						'<p><b>Your answer is correct!</b></p><p>%s</p>');
define('ed_str_answer_bad',							'<p><b>Sorry, your answer is wrong!</b></p><p>%s</p>');

define('ed_tab_groups_edit',						'Groups');
define('ed_tab_question_edit',					'Question');
define('ed_tab_question_list',					'Catalogue');
define('ed_tab_config',									'Configuration');
define('ed_tab_info',										'?');

define('ed_template_error',        		 	'<div style="margin:15px;padding:15px;border:1px solid #cc0000;color: #cc0000; background-color:#ffffdd;"><h1>%s</h1>%s</div>');


?>