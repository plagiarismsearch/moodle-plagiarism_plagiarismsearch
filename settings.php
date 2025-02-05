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
 * settings.php
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(__FILE__)) . '/../config.php');
global $CFG, $OUTPUT, $USER, $PAGE;
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/plagiarismlib.php');
require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/lib.php');
require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/plagiarism_form.php');

require_login();

$url = new moodle_url('/plagiarism/plagiarismsearch/settings.php');
$context = context_system::instance();
$PAGE->set_url($url);
$PAGE->set_context($context);

require_capability('moodle/site:config', $context, $USER->id, true, 'nopermissions');

require_once('plagiarism_form.php');
$mform = new plagiarism_setup_form();

$plagiarismsettings = plagiarismsearch_config::load_settings();

$mform->set_data($plagiarismsettings);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/'));
}

$error = $result = null;

$data = $mform->get_data();
if ($data && confirm_sesskey()) {
    if (!isset($data->enabled)) {
        $data->enabled = 0;
    }

    $localonlysettings = plagiarismsearch_config::fields();

    $result = true;

    foreach ($localonlysettings as $field) {
        $value = isset($data->{$field}) ? $data->{$field} : null;
        if (isset($plagiarismsettings[$field]) && $plagiarismsettings[$field] == $value) {
            continue; // Setting unchanged.
        }

        // Save the setting.
        if (!plagiarismsearch_config::set_settings($field, $value)) {
            $result = false;
        }
        // Update the local copy.
        $plagiarismsettings[$field] = $value;
    }

    if (empty($plagiarismsettings->plagiarismsearch_student_show_reports)) {
        $plagiarismsettings['plagiarismsearch_student_show_percentage'] = 0;
        plagiarismsearch_config::set_settings('plagiarismsearch_student_show_percentage', 0);

        $plagiarismsettings['plagiarismsearch_student_student_submit'] = 0;
        plagiarismsearch_config::set_settings('plagiarismsearch_student_student_submit', 0);

        $plagiarismsettings['plagiarismsearch_student_student_resubmit'] = 0;
        plagiarismsearch_config::set_settings('plagiarismsearch_student_student_resubmit', 0);
    }
    if (empty($plagiarismsettings['plagiarismsearch_student_resubmit_numbers'])) {
        $plagiarismsettings['plagiarismsearch_student_resubmit_numbers'] = '';
    }

    if ($data->enabled) {
        plagiarismsearch_config::load_settings();
        $api = new plagiarismsearch_api();
        $page = $api->ping();
        if (!$page || !$page->status) {
            $error = get_string('settings_error_server', 'plagiarism_plagiarismsearch') .
                    (!empty($page->message) ? ' ' . $page->message : '');

            $plagiarismsettings['enabled'] = 0;
            plagiarismsearch_config::set_settings('enabled', 0);

            $result = false;
        }
    }
}

echo $OUTPUT->header();
echo $OUTPUT->box_start('generalbox boxaligncenter', 'intro');
if ($result !== null) {
    if (empty($result)) {
        if ($error) {
            echo $OUTPUT->notification($error);
        } else {
            echo $OUTPUT->notification(get_string('settings_error', 'plagiarism_plagiarismsearch'));
        }
    } else {
        echo $OUTPUT->notification(get_string('settings_saved', 'plagiarism_plagiarismsearch'), 'success');
    }
}

$mform->display();
echo $OUTPUT->box_end();
echo $OUTPUT->footer();
