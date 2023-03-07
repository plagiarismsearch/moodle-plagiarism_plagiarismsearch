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
 * Record the fact that scanning is now complete for a file on the server
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../config.php');
global $CFG;
require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/lib.php');

$rid = optional_param('id', null, PARAM_INT);
$key = optional_param('api_key', null, PARAM_TEXT);
$reportdata = optional_param('report', null, PARAM_TEXT);
$debug = optional_param('debug', null, PARAM_INT);

if (empty($rid) or empty($key) or empty($reportdata)) {
    die();
}

if ($key !== plagiarismsearch_config::get_settings('api_key')) {
    die();
}

$localreport = plagiarismsearch_reports::get_one(array('rid' => $rid));
if (!$localreport) {
    die();
}

$report = json_decode($reportdata, false);
if ($report) {
    $values = array(
        'plagiarism' => $report->plagiat,
        'status' => $report->status,
        'url' => $report->file,
        'rfileid' => $report->file_id,
        'rkey' => $report->auth_key,
        'rserverurl' => (isset($report->server_url) ? $report->server_url : ''),
    );

    if (plagiarismsearch_reports::update($values, $localreport->id)) {
        echo $localreport->id;
    }
} else {
    // JSON error

    $values = array(
        'status' => plagiarismsearch_reports::STATUS_ERROR,
        'log' => 'Sync JSON error'
    );

    plagiarismsearch_reports::update($values, $localreport->id);
}

if ($debug) {
    echo json_encode(array('jsonerror' => json_last_error(), 'localreport' => $localreport, 'report' => $report, 'reportdata' => $reportdata));
}