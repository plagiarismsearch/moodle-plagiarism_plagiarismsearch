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
 * submit a file to plagiarismsearch for analysis
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/lib.php');
global $CFG, $DB;

$userid = required_param('userid', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);
$filehash = required_param('filehash', PARAM_TEXT);
$force = required_param('force', PARAM_INT);
$return = urldecode(required_param('return', PARAM_TEXT));
// $return = $return . "&action=grading"; //this can fail !!check at the end of checkstatus.<?php$PAGE->set_url($return);

require_login();

if ($CFG->version < 2011120100) {
    $context = get_context_instance(CONTEXT_MODULE, $cmid);
} else {
    $context = context_module::instance($cmid);
}

require_capability('plagiarism/plagiarismsearch:viewlinks', $context);
require_sesskey();


if (!plagiarismsearch_config::get_settings('use')) {
    // Disabled at the site level
    print_error('disabledsite', 'plagiarism_plagiarismsearch');
}

// Retrieve the file and check everything is OK
/* @var $file \stored_file */
$fs = get_file_storage();
if (!$file = $fs->get_file_by_hash($filehash)) {
    print_error('invalidfilehash', 'plagiarism_plagiarismsearch');
}

if ($file->get_contextid() != $context->id) {
    print_error('wrongfilecontext', 'plagiarism_plagiarismsearch');
}

if ($file->get_userid() != $userid) {
    print_error('wrongfileuser', 'plagiarism_plagiarismsearch');
}

$values = array(
    'userid' => $userid,
    'cmid' => $cmid,
    'filehash' => $filehash,
    'filename' => $file->get_filename(),
);

$api = new plagiarismsearch_api_reports($values);
$page = $api->action_send_file($file, array('force' => $force));

$msg = 'Error';
if ($page) {
    if ($page->status and ! empty($page->data)) {
        $values['rid'] = $page->data->id;
        $values['status'] = $page->data->status;
        $values['plagiarism'] = $page->data->plagiat;
        $values['url'] = (string) $page->data->file;
        $msg = get_string('submit_ok', 'plagiarism_plagiarismsearch', $file->get_filename());
    } else {
        $values['status'] = plagiarismsearch_reports::STATUS_ERROR;
        $values['log'] = $page->message;

        $msg = get_string('submit_error', 'plagiarism_plagiarismsearch', $file->get_filename()) .
                (!empty($page->message) ? '. ' . $page->message : '');
    }
} else {
    $values['status'] = plagiarismsearch_reports::STATUS_SERVER_ERROR;
    $values['log'] = get_string('server_connection_error', 'plagiarism_plagiarismsearch');
    $msg = get_string('server_connection_error', 'plagiarism_plagiarismsearch') . ' ' . $api->apierror;
}

plagiarismsearch_reports::add($values);

redirect($return, $msg, 5);
die();
