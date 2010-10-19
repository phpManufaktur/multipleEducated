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
$module_description 	= 'multipleEducatd - Frage- und Antwortspiele im Stil der "Bildungshappen" (ZEIT) erstellen und verwalten';

// name of the person(s) who translated and edited this language file
$module_translation_by = 'Ralf Hertsch (phpManufaktur)';


define('ed_btn_abort',									'Abbruch');
define('ed_btn_ok',											'Übernehmen');
define('ed_btn_check',									'Richtig?');
define('ed_btn_new_question',						'Neue Frage...');
define('ed_btn_retry',									'Noch mal vermuten...');

define('ed_desc_cfg_question_shuffle',	'Legen Sie fest, daß Fragen zufällig ausgewählt und angezeigt werden.');
define('ed_desc_cfg_developer_mode',		'Ermöglicht dem Programmierer das Hinzufügen von Konfigurationsparametern.');
define('ed_desc_cfg_replies_count',			'Mit diesem Parameter legen Sie die Anzahl der möglichen Antworten fest.');

define('ed_error_addon_version',     		'<p>Fataler Fehler: <b>Educated</b> benoetigt das Website Baker Addon <b>%s</b> ab der Version <b>%01.2f</b> - installiert ist die Version <b>%01.2f</b>.</p><p>Bitte aktualisieren Sie zunaechst dieses Addon.</p>');
define('ed_error_missing_addon',    		'<p>Fataler Fehler: <b>Educated</b> benoetigt das Website Baker Addon <b>%s</b>, die Programmausfuehrung wurde gestoppt.</p>');
define('ed_error_question_id_not_exists','<p>[%s - %s] Die Frage mit der <b>ID #%05d</b> existiert nicht!</p>');
define('ed_error_answer_id_not_exists',	'<p>[%s - %s] Die Antwort mit der <b>ID #%05d</b> existiert nicht!</p>');
define('ed_error_cfg_id',								'<p>Der Konfigurationsdatensatz mit der <b>ID #%05d</b> konnte nicht ausgelesen werden!</p>');
define('ed_error_cfg_name',							'<p>Zu dem Bezeichner <b>%s</b> wurde kein Konfigurationsdatensatz gefunden!</p>');
define('ed_error_random_fail',					'<p>Es liegt leider keine Frage vor.</p>');
define('ed_error_date_random_fail',			'<p>Für <b>Heute</b> liegt leider keine Frage vor.</p>');
define('ed_error_to_less_answers',			'<p>Die Anzahl der ermittelten Anworten entspricht nicht der Vorgabe!</p>');
define('ed_error_question_id_missing',	'<p>[%s - %s] Die Question ID ist nicht gesetzt, die Anfrage kann nicht bearbeitet werden.</p>');

define('ed_header_question_edit',				'Frage bearbeiten');
define('ed_header_prompt_error',    		'[Educated] Fehlermeldung');
define('ed_header_identifier',					'Bezeichner');
define('ed_header_typ',									'Typ');
define('ed_header_value',								'Wert');
define('ed_header_description',					'Beschreibung');
define('ed_header_label',								'Label');
define('ed_header_config',							'Einstellungen');
define('ed_header_list',								'Katalog');
define('ed_header_question',						'Wissen Sie die Antwort?');
define('ed_header_answer',							'Auflösung');

define('ed_intro_question_edit',				'Mit diesem Dialog können Sie Fragen erstellen und bearbeiten');
define('ed_intro_question_list',				'Wählen Sie die Frage aus, die Sie bearbeiten möchten.');
define('ed_intro_groups_edit',					'Mit diesem Dialog können Sie Gruppen erstellen und bearbeiten');
define('ed_intro_add_group',						'Fügen Sie eine neue Gruppe hinzu');
define('ed_intro_cfg_add_item',					'<p>Das Hinzufügen von Einträgen zur Konfiguration ist nur sinnvoll, wenn die angegebenen Werte mit dem Programm korrespondieren.</p>');
define('ed_intro_cfg',									'<p>Bearbeiten Sie die Einstellungen für multipeEducated.</p>');
define('ed_intro_answer_edit',					'<p>Formulieren Sie <b>%d</b> Antworten zu der o.a. Frage und wählen Sie genau eine Antwort als Richtige aus.</p><p>Erklären Sie zusätzlich, warum die jeweilige Antwort richtig bzw. falsch ist.</p>');
define('ed_intro_question',							'Kennen Sie die richtige Lösung?');

define('ed_label_id',										'ID');
define('ed_label_group',								'Gruppe');
define('ed_label_date_from',						'Gültig von');
define('ed_label_date_to',							'Gültig bis');
define('ed_label_created_when',					'Erstellt');
define('ed_label_group_name',						'Bezeichner');
define('ed_label_group_description',		'Beschreibung');
define('ed_label_status',								'Status');
define('ed_label_last_update',					'Akualisiert');
define('ed_label_cfg_question_shuffle',	'Fragen zufällig auswählen');
define('ed_label_csv_export',						'Konfigurationsdaten als CSV-Datei im /MEDIA Verzeichnis sichern');
define('ed_label_cfg_developer_mode',		'Programmierer Modus');
define('ed_label_cfg_replies_count',		'Anzahl der Antworten');
define('ed_label_question_name',				'Bezeichner');
define('ed_label_question_question',		'Frage');
define('ed_label_question_group',				'Gruppe');
define('ed_label_question_date_range',	'Gültigkeit');
define('ed_label_question_status',			'Status');
define('ed_label_answer_answer_no',			'Antwort <b>#%02d</b>');
define('ed_label_answer_explain_no',		'Erklärung <b>#%02d</b>');
define('ed_label_answer_status_no',			'Status <b>#%02d</b>');
define('ed_label_answer_truth',					'<b>Richtig?</b>');

define('ed_msg_group_name_too_short',		'<p>Der Bezeichner für die Gruppe ist zu kurz, er sollte aus mindestens 3 Zeichen bestehen.</p>');
define('ed_msg_group_desc_empty',				'<p>Bitte fügen Sie eine Beschreibung für die Gruppe ein.</p>');
define('ed_msg_group_desc_empty_up',		'<p>Die Beschreibung für die Gruppe mit der <b>ID #%05d</b> darf nicht leer sein.</p>');
define('ed_msg_group_add_group',				'<p>Die Gruppe <b>%s</b> mit der <b>ID #%05d</b> wurde hinzugefügt.</p>');
define('ed_msg_group_name_exists',			'<p>Eine Gruppe mit dem Bezeichner <b>%s</b> existiert bereits, bitte verwenden Sie einen anderen Bezeichner!</p>');
define('ed_msg_group_name_too_short_up','<p>Der Bezeichner für die Gruppe mit der <b>ID #%05d</b> ist zu kurz, er sollte aus mindestens 3 Zeichen bestehen.</p>');
define('ed_msg_group_status_changed',		'<p>Der Status der Gruppe <b>%s</b> wurde auf <b>%s</b> geändert.</p>');
define('ed_msg_group_updated',					'<p>Die Gruppe mit dem Bezeichner <b>%s</b> wurde aktualisiert.</p>');
define('ed_msg_group_not_defined',			'<p>Es sind noch keine Gruppen definiert, bitte legen Sie zunächst mindestens eine Gruppe fest.</p>');
define('ed_msg_cfg_id_updated',					'<p>Der Konfigurationsdatensatz mit der <b>ID #%05d</b> und dem Bezeichner <b>%s</b> wurde aktualisiert.</p>');
define('ed_msg_cfg_add_exists',					'<p>Der Konfigurationsdatensatz mit dem Bezeichner <b>%s</b> existiert bereits und kann nicht noch einmal hinzugefügt werden!</p>');
define('ed_msg_cfg_add_success',				'<p>Der Konfigurationsdatensatz mit der <b>ID #%05d</b> und dem Bezeichner <b>%s</b> wurde hinzugefügt.</p>');
define('ed_msg_cfg_add_incomplete',			'<p>Der neu hinzuzufügende Konfigurationsdatensatz ist unvollständig! Bitte prüfen Sie Ihre Angaben!</p>');
define('ed_msg_cfg_csv_export',					'<p>Die Konfigurationsdaten wurden als <b>%s</b> im /MEDIA Verzeichnis gesichert.</p>');
define('ed_msg_invalid_date',						'<p>Das Datum <b>%s</b> ist ungültig, bitte prüfen Sie Ihre Eingabe!</p>');
define('ed_msg_quest_name_empty',				'<p>Der Bezeichner darf nicht leer sein.</p>');
define('ed_msg_quest_question_empty',		'<p>Sie haben vergessen eine Frage zu formulieren.</p>');
define('ed_msg_quest_group_select',			'<p>Bitte wählen Sie die Gruppe aus, zu der die Frage gehören soll.</p>');
define('ed_msg_quest_name_exists',			'<p>Eine Frage mit dem Bezeichner <b>%s</b> existiert bereits, bitte wählen Sie einen anderen Bezeichner.</p>');
define('ed_msg_quest_no_list',					'<p>Es sind (noch) keine Fragen zum Anzeigen vorhanden.</p>');
define('ed_msg_quest_error',						'<p style="color:#800000;">Die Frage wurde nicht eingefügt bzw. aktualisiert, da bei der Prüfung Fehler festgestellt wurden!</p>');
define('ed_msg_quest_insert_data',			'<p>Die Frage mit dem Bezeichner <b>%s</b> wurde mit der <b>ID #%05d</b> neu angelegt.</p>');
define('ed_msg_quest_update_data',			'<p>Die Frage mit dem Bezeichner <b>%s</b> und der <b>ID #%05d</b> wurde aktualisiert.</p>');
define('ed_msg_quest_no_change',				'<p>Die Frage wurde nicht verändert.</p>');
define('ed_msg_quest_error_locked',			'<p style="color:#800000;">Die Frage mit der <b>ID #%05d</b> wurde <b>GESPERRT</b>, da logische Fehler vorliegen oder erforderliche Angaben unvollständig sind.</p>');
define('ed_msg_answer_truth_count',			'<p>Bitte prüfen Sie Ihre Eingaben, es muss genau eine Antwort als richtige festgelegt werden!</p>');
define('ed_msg_answer_answer_empty',		'<p>Die Antwort <b>#%02d</b> darf nicht leer sein!</p>');
define('ed_msg_answer_explain_empty',		'<p>Die Erklärung zu Antwort <b>#%02d</b> darf nicht leer sein!</p>');
define('ed_msg_answer_id_insert',				'<p>Die Antwort mit der <b>ID #%02d</b> wurde hinzugefügt.</p>');
define('ed_msg_answer_id_update',				'<p>Die Antwort mit der <b>ID #%02d</b> wurde aktualisiert.</p>');
define('ed_msg_answer_no_selection',		'<p>Sie haben vergessen eine Antwort auszuwählen!</p>');

define('ed_status_active',							'Aktiv');
define('ed_status_deleted',							'Gelöscht');
define('ed_status_locked',							'Gesperrt');

define('ed_str_true',										'Richtig');
define('ed_str_false',									'Falsch');
define('ed_str_changed_by',							'%s, am %s Uhr');
define('ed_str_undefined',							'- nicht definiert -');
define('ed_str_select',									'- bitte auswählen -');
define('ed_str_valid_from',							'vom');
define('ed_str_valid_to',								'bis zum');
define('ed_str_answer_repeat',					'<p><b>Ihre Antwort lautet:</b>&nbsp;%s</p>');
define('ed_str_answer_good',						'<p><b>Ihre Antwort ist richtig!</b></p><p>%s</p>');
define('ed_str_answer_bad',							'<p><b>Ihre Antwort ist leider falsch!</b></p><p>%s</p>');

define('ed_tab_groups_edit',						'Gruppen');
define('ed_tab_question_edit',					'Frage');
define('ed_tab_question_list',					'Katalog');
define('ed_tab_config',									'Einstellungen');
define('ed_tab_info',										'?');

define('ed_template_error',        		 	'<div style="margin:15px;padding:15px;border:1px solid #cc0000;color: #cc0000; background-color:#ffffdd;"><h1>%s</h1>%s</div>');


?>