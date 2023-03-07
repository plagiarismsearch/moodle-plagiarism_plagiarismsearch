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
require_once(dirname(__FILE__) . '/../../config.php');
global $CFG, $DB, $PAGE;
require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/lib.php');

$cmid = required_param('cmid', PARAM_INT);
$id = required_param('id', PARAM_INT);

if (!$cmid || !$id) {
    throw new \moodle_exception('no_cmid_or_id', 'plagiarism_plagiarismsearch');
}

if (!plagiarismsearch_config::get_settings('use')) {
    // Disabled at the site level.
    throw new \moodle_exception('disabledsite', 'plagiarism_plagiarismsearch');
}

require_sesskey();

$url = new moodle_url('/plagiarism/plagiarismsearch/status.php');
$cm = get_coursemodule_from_id('', $cmid, 0, false, MUST_EXIST);

$PAGE->set_url($url);
require_login($cm->course, true, $cm);

$context = context_module::instance($cmid);
require_capability('plagiarism/plagiarismsearch:statuslinks', $context);

if (!plagiarism_plugin_plagiarismsearch::has_show_reports_link($cmid)) {
    throw new \moodle_exception('student_error_nopermission', 'plagiarism_plagiarismsearch');
}

// Load local report by id.
$report = plagiarismsearch_reports::get_one(array('id' => $id));

if (empty($report->rid)) {
    throw new \moodle_exception('report_not_found', 'plagiarism_plagiarismsearch');
}

// Check remote status.
$msg = plagiarismsearch_core::check_status(array($report->id => $report->rid));

// Safe back redirect.
$redirect = plagiarismsearch_core::redirect_url($cm, $context);
redirect($redirect, $msg);
