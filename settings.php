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
require_once(dirname(dirname(__FILE__)) . '/../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/plagiarismlib.php');
require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/lib.php');
require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/plagiarism_form.php');

global $CFG, $OUTPUT, $USER, $PAGE;

require_login();
// admin_externalpage_setup('plagiarismplagiarismsearch');

if ($CFG->version < 2011120100) {
    $context = get_context_instance(CONTEXT_SYSTEM);
} else {
    $context = context_system::instance();
}

$PAGE->set_url('/plagiarism/plagiarismsearch/settings.php');
$PAGE->set_context($context);

require_capability('moodle/site:config', $context, $USER->id, true, "nopermissions");

require_once('plagiarism_form.php');
$mform = new plagiarism_setup_form();

$plagiarismsettings = get_config('plagiarism');
// $plagiarismsettings = get_config('plagiarism_plagiarismsearch')

$mform->set_data($plagiarismsettings);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/'));
}

$error = $result = null;

if (($data = $mform->get_data()) && confirm_sesskey()) {
    if (!isset($data->plagiarismsearch_use)) {
        $data->plagiarismsearch_use = 0;
    }

    $localonlysettings = plagiarismsearch_config::fields();

    $result = true;

    foreach ($localonlysettings as $field) {
        $value = $data->{$field};
        if (isset($plagiarismsettings->{$field}) && $plagiarismsettings->{$field} == $value) {
            continue; // Setting unchanged
        }

        // Save the setting
        if (!set_config($field, $value, 'plagiarism')) {
            $result = false;
        }
        // Update the local copy
        $plagiarismsettings->{$field} = $value;
    }


    if ($data->plagiarismsearch_use) {
        $api = new plagiarismsearch_api();
        if (!$page = $api->ping() or ! $page->status) {
            $error = get_string('settings_error_server', 'plagiarism_plagiarismsearch') . 
                    (!empty($page->message) ? ' ' . $page->message : '');

            $plagiarismsettings->plagiarismsearch_use = 0;
            set_config('plagiarismsearch_use', 0, 'plagiarism');

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
        echo $OUTPUT->notification(get_string('settings_error', 'plagiarism_plagiarismsearch'), 'success');
    }
}

$mform->display();
echo $OUTPUT->box_end();
echo $OUTPUT->footer();
