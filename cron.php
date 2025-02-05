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
defined('MOODLE_INTERNAL') || die();

mtrace("\n\n");
mtrace("Starting the plagiarismsearch cron\n");

$processingtimeinterval = 5 * 60; // 5 min
$errortimeinterval = 12 * 60 * 60; // 12 hours
// Check status processing reports.
$reports = plagiarismsearch_reports::get_processing_reports($processingtimeinterval);
if (!$reports) {
    $reports = plagiarismsearch_reports::get_error_reports($errortimeinterval);
}

if ($reports) {
    $ids = [];
    foreach ($reports as $report) {
        $ids[$report->id] = $report->rid;
    }
    $msg = plagiarismsearch_core::check_status($ids);
} else {
    $msg = "OK! Empty queue";
}

mtrace($msg);
