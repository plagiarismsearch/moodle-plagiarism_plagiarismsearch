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
 * English language file for plagiarismsearch
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder
$string['plagiarismsearch'] = 'PlagiarismSearch';
$string['pluginname'] = 'PlagiarismSearch';

$string['auto_check'] = 'Auto check';
$string['manual_check'] = 'Manual check';
$string['add_to_storage'] = 'Add to Storage';

$string['detect_ai'] = 'Detect AI';
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
$string['report_language_ua'] = 'Ukrainian';
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
$string['review_report'] = 'Review report';
$string['show_review_link'] = 'Allow teachers review reports';
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
$string['ai'] = 'AI';
$string['ai_rate'] = 'Total AI rate';
$string['ai_probability'] = 'AI probability';
$string['empty_parameter'] = 'Empty \'{$a}\' parameter';
$string['report_not_found'] = 'Report was not found';
$string['no_cmid_or_id'] = 'No cmid or id';
$string['api_error'] = 'PlagiarismSearch API error';

$string['report'] = 'Report';
$string['save'] = 'Save';
$string['settings_error'] = 'There was an error while updating the PlagiarismSearch settings';
$string['settings_error_server'] = 'PlagiarismSearch settings API error';
$string['settings_saved'] = 'PlagiarismSearch settings have been successfully saved';
$string['use'] = 'Enable PlagiarismSearch';
$string['enabled'] = 'Enable PlagiarismSearch';
$string['yellow'] = 'Plagiarism level yellow starts at';
$string['red'] = 'Plagiarism level red starts at';

$string['text_plain'] = 'PlagiarismSearch.com – advanced online plagiarism checker. <br/>
PlagiarismSearch.com is a leading plagiarism checking website that will provide you with an accurate report during a short timeframe. <br/>
Find out how to register <a href="https://plagiarismsearch.com/account/signup">here</a> and ask us for a free trial <a href="mailto:support@plagiarismsearch.com">support@plagiarismsearch.com</a><br/><br/>';

$string['plagiarismsearch:viewlinks'] = 'Ability to view links for plagiarism results';
$string['plagiarismsearch:submitlinks'] = 'Ability to submit links to PlagiarismSearch.com';
$string['plagiarismsearch:statuslinks'] = 'Ability to check report status';
$string['plagiarismsearch:isstudent'] = 'Allow students only';

$string['privacy:metadata:plagiarism_ps_reports'] = 'Information about plagiarism reports.';
$string['privacy:metadata:plagiarism_ps_reports:userid'] = 'The ID of the user associated with the report.';
$string['privacy:metadata:plagiarism_ps_reports:senderid'] = 'The ID of the sender of the report.';
$string['privacy:metadata:plagiarism_ps_reports:rid'] = 'The remote ID of the plagiarism report.';
$string['privacy:metadata:plagiarism_ps_reports:rfileid'] = 'The remote ID of the file associated with the report.';
$string['privacy:metadata:plagiarism_ps_reports:rserverurl'] = 'The remote server URL for the plagiarism report.';
$string['privacy:metadata:plagiarism_ps_reports:rkey'] = 'The remote key associated with the plagiarism report.';
$string['privacy:metadata:plagiarism_ps_reports:plagiarism'] = 'Indicates the plagiarism status of the report.';
$string['privacy:metadata:plagiarism_ps_reports:ai_rate'] = 'The AI rate associated with the report.';
$string['privacy:metadata:plagiarism_ps_reports:ai_probability'] = 'The probability of AI-detected plagiarism.';
$string['privacy:metadata:plagiarism_ps_reports:status'] = 'The status of the plagiarism report.';
$string['privacy:metadata:plagiarism_ps_reports:url'] = 'The URL related to the plagiarism report.';
$string['privacy:metadata:plagiarism_ps_reports:cmid'] = 'The ID of the course module associated with the report.';
$string['privacy:metadata:plagiarism_ps_reports:filehash'] = 'The hash of the file associated with the report.';
$string['privacy:metadata:plagiarism_ps_reports:filename'] = 'The name of the file associated with the report.';
$string['privacy:metadata:plagiarism_ps_reports:fileid'] = 'The ID of the file in the plagiarism report.';
$string['privacy:metadata:plagiarism_ps_reports:log'] = 'The log data related to the plagiarism report.';
$string['privacy:metadata:plagiarism_ps_reports:created_at'] = 'The date and time when the report was created.';
$string['privacy:metadata:plagiarism_ps_reports:modified_at'] = 'The date and time when the report was last modified.';

$string['privacy:metadata:plagiarism_plagiarismsearch_client'] = 'Information sent to the plagiarism search client.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:userid'] = 'The ID of the user sending the data.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:cmid'] = 'The ID of the course module for the data.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:onlinetext'] = 'The online text submitted for plagiarism checking.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:fileid'] = 'The ID of the file sent for plagiarism checking.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:fileauthor'] = 'The author of the file sent for plagiarism checking.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:filename'] = 'The name of the file sent for plagiarism checking.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:file'] = 'The file content sent for plagiarism checking.';

// phpcs:enable moodle.Files.LangFilesOrdering.IncorrectOrder
