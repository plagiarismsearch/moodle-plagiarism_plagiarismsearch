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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/lib.php');

global $CFG, $DB;

$cmid = optional_param('cmid', 0, PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);

if (!$cmid or ! $id) {
    print_error('no_cmid_or_id', 'plagiarism_plagiarismsearch');
}

if (!plagiarismsearch_config::get_settings('use')) {
    // Disabled at the site level
    print_error('disabledsite', 'plagiarism_plagiarismsearch');
}

require_sesskey();

$url = new moodle_url(dirname(__FILE__) . '/status.php');
$cm = get_coursemodule_from_id('', $cmid, 0, false, MUST_EXIST);

$PAGE->set_url($url);
require_login($cm->course, true, $cm);

$context = context_module::instance($cmid);
require_capability('plagiarism/plagiarismsearch:status', $context);

// Load local report by ID
$report = plagiarismsearch_reports::get_one(array('id' => $id));

if (empty($report->rid)) {
    print_error('report_not_found', 'plagiarism_plagiarismsearch');
}

$config = array(
    'userid' => $report->userid,
    'cmid' => $report->cmid,
    'filehash' => $report->filehash,
);

$api = new plagiarismsearch_api_reports($config);
$page = $api->action_status(array($report->rid));

$msg = '';
if ($page) {
    if ($page->status and ! empty($page->data)) {

        $msg = get_string('status_ok', 'plagiarism_plagiarismsearch');

        foreach ($page->data as $row) {
            $values['status'] = $row->status;
            $values['plagiarism'] = $row->plagiat;
            $values['url'] = (string) $row->file;

            $msg .= "\n #" . $row->id . ' is ' . plagiarismsearch_reports::$statuses[$row->status];

            plagiarismsearch_reports::update($values, $report->id);
        }
    } else {
        $values['status'] = plagiarismsearch_reports::STATUS_ERROR;
        $values['log'] = $page->message;

        plagiarismsearch_reports::update($values, $report->id);

        $msg = get_string('status_error', 'plagiarism_plagiarismsearch') . (!empty($page->message) ? '. ' . $page->message : '');
    }
} else {
    $values['status'] = plagiarismsearch_reports::STATUS_SERVER_ERROR;
    plagiarismsearch_reports::update($values, $report->id);

    $msg = get_string('server_connection_error', 'plagiarism_plagiarismsearch') . ' ' . $api->apierror;
}

// Safe back redirect
if ($cm->modname == 'assignment') {
    $redirect = new moodle_url('/mod/assignment/submissions.php', array('id' => $cmid));
} else if ($cm->modname == 'assign') {
    $redirect = new moodle_url('/mod/assign/view.php', array('id' => $cmid, 'action' => 'grading'));
} else {
    $redirect = $CFG->wwwroot;
}


redirect($return, $msg);
