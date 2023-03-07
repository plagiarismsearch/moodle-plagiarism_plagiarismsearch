<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['plagiarismsearch'] = 'PlagiarismSearch';
$string['pluginname'] = 'PlagiarismSearch';

$string['auto_check'] = 'Autocheck';
$string['manual_check'] = 'Manual check';
$string['add_to_storage'] = 'Add to Storage';

$string['sources_type'] = 'Sources';
$string['sources_doc_web_storage'] = 'Doc vs Web + Storage';
$string['sources_doc_web'] = 'Doc vs Web';
$string['sources_doc_storage'] = 'Doc vs Storage';

$string['report_type'] = 'Report file type';
$string['report_show_no'] = 'Don\'t show';
$string['report_show_pdf'] = 'Only PDF report';
$string['report_show_html'] = 'Only HTML report';
$string['report_show_pdf_html'] = 'PDF and HTML reports';

$string['report_language'] = 'Report language';
$string['report_language_default'] = 'Default (English)';
$string['report_language_en'] = 'English';
$string['report_language_es'] = 'Spanish';
$string['report_language_pl'] = 'Polish';
$string['report_language_ru'] = 'Russian';

$string['student_show_percentage'] = 'Allow students view plagiarism percentage';
$string['student_show_reports'] = 'Allow students view reports';
$string['student_submit'] = 'Allow students submit papers';
$string['student_resubmit'] = 'Allow students re-submit papers';
$string['student_resubmit_numbers'] = 'The number of re-submits';
$string['student_disclosure'] = 'Student disclosure';
$string['student_disclosure_default'] = 'All files uploaded will be submitted to PlagiarismSearch.com detection service.';
$string['student_error_nopermission'] = 'No permission';

$string['api_url'] = 'API url';
$string['api_key'] = 'API key';
$string['api_user'] = 'API user';
$string['api_debug'] = 'API debug';
$string['api_version'] = 'API version';
$string['filter_chars'] = 'Only Latin characters';
$string['filter_references'] = 'Exclude references';
$string['filter_quotes'] = 'Exclude in-text citations';

$string['filter_plagiarism'] = 'Exclude self-plagiarism';
$string['filter_plagiarism_no'] = 'No (not recommended)';
$string['filter_plagiarism_user_course'] = 'Exclude user plagiarism within the same course';
$string['filter_plagiarism_user'] = 'Exclude user plagiarism';
$string['filter_plagiarism_course'] = 'Exclude course plagiarism (not recommended)';

$string['parse_text_url'] = 'Allow URL parsing in text';
$string['valid_parsed_text_url'] = 'Valid URLs list for parsing';

$string['submit'] = 'Submit to PlagiarismSearch';
$string['resubmit'] = 'Resubmit to PlagiarismSearch';
$string['processing'] = 'In progress';
$string['unknown_error'] = 'Unknown error';

$string['pdf_report'] = 'Download PDF report';
$string['html_report'] = 'View HTML report';
$string['link_title'] = 'PlagiarismSearch.com – advanced online plagiarism checker';
$string['check_status'] = 'Check status';
$string['temp_folder_not_exists'] = 'Temp folder does not exist';
$string['server_connection_error'] = 'Problem connecting to PlagiarismSearch server';
$string['submit_ok'] = 'Document \'{$a}\' submitted to PlagiarismSearch';
$string['submit_error'] = 'Document \'{$a}\' not submitted';
$string['submit_onlinetext_ok'] = 'Online text was submitted to PlagiarismSearch';
$string['submit_onlinetext_error'] = 'Online text was not submitted';
$string['status_ok'] = 'Document';
$string['status_error'] = 'Error. Document';
$string['status'] = 'status';
$string['is_in'] = 'is in';
$string['plagiarism'] = 'Plagiarism';
$string['empty_parameter'] = 'Empty \'{$a}\' parameter';
$string['report_not_found'] = 'Report was not found';
$string['no_cmid_or_id'] = 'No cmid or id';
$string['api_error'] = 'PlagiarismSearch API error';

$string['report'] = 'Report';
$string['save'] = 'Save';
$string['settings_error'] = 'There was an error while updating the PlagiarismSearch settings';
$string['settings_error_server'] = 'PlagiarismSearch settings API error';
$string['settings_saved'] = 'PlagiarismSearch settings have been successfully saved';
// $string['settings_cancelled'] = 'PlagiarismSearch settings have been canceled';
// $string['settings_saved'] = 'PlagiarismSearch settings have been saved successfully';
$string['submit'] = 'Submit to PlagiarismSearch';
$string['use'] = 'Enable PlagiarismSearch';
$string['yellow'] = 'Plagiarism level yellow starts at';
$string['red'] = 'Plagiarism level red starts at';


$string['text_plain'] = 'PlagiarismSearch.com – advanced online plagiarism checker. <br/>
PlagiarismSearch.com is a leading plagiarism checking website that will provide you with an accurate report during a short timeframe. <br/>
Find out how to register <a href="https://plagiarismsearch.com/account/signup.html">here</a> and ask us for a free trial <a href="mailto:support@plagiarismsearch.com">support@plagiarismsearch.com</a><br/><br/>';

$string['plagiarismsearch:viewlinks'] = 'Ability to view links for plagiarism results';
$string['plagiarismsearch:submitlinks'] = 'Ability to submit links to PlagiarismSearch.com';
$string['plagiarismsearch:statuslinks'] = 'Ability to check report status';
$string['plagiarismsearch:isstudent'] = 'Allow students only';