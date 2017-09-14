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
defined('MOODLE_INTERNAL') || die();


mtrace("\n\n");
mtrace("Starting the plagiarismsearch cron");

$msg = "";

$processingtimeinterval = 5 * 60; // 5 min
$errortimeinterval = 12 * 60 * 60; // 12 hours
// check status processing reports
if ($reports = plagiarismsearch_reports::get_processing_reports($processingtimeinterval) or $report = plagiarismsearch_reports::get_error_reports($errortimeinterval)) {
    $ids = array();

    foreach ($reports as $report) {
        $ids[] = $report->rid;
    }

    $api = new plagiarismsearch_api_reports($config);
    $page = $api->action_status($ids);

    if ($page) {

        if ($page->status) {

            $msg = "OK! " . get_string('status_ok', 'plagiarism_plagiarismsearch');

            if (!empty($page->data)) {
                foreach ($page->data as $row) {
                    $values['status'] = $row->status;
                    $values['plagiarism'] = $row->plagiat;
                    $values['url'] = (string) $row->file;

                    $msg .= " \n#" . $row->id . ' is ' . plagiarismsearch_reports::$statuses[$row->status];

                    plagiarismsearch_reports::update($values, $report->id);
                }
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
} else {
    $msg = "OK! Empty queue";
}

mtrace($msg);
