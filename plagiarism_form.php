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
require_once($CFG->dirroot . '/lib/formslib.php');

class plagiarism_setup_form extends moodleform {

    /**
     * Define the form
     */
    public function definition() {
        // global $CFG;

        $mform = $this->_form;

        // $autostartoptions = array(0 => get_string('no'), 1 => get_string('yes'));
        $notoryes = array(0 => get_string('no'), 1 => get_string('yes'));

        $mform->addElement('html', get_string('text_plain', 'plagiarism_plagiarismsearch'));
        $mform->addElement('checkbox', 'plagiarismsearch_use', get_string('use', 'plagiarism_plagiarismsearch'));

        $mform->addElement('text', 'plagiarismsearch_api_url', get_string('api_url', 'plagiarism_plagiarismsearch'), array('size' => '40'));
        $mform->addRule('plagiarismsearch_api_url', null, 'required', null, 'client');
        $mform->setDefault('plagiarismsearch_api_url', 'https://plagiarismsearch.com/api/v3');

        $mform->addElement('text', 'plagiarismsearch_api_user', get_string('api_user', 'plagiarism_plagiarismsearch'), array('size' => '40'));
        $mform->addRule('plagiarismsearch_api_user', null, 'required', null, 'client');

        $mform->addElement('text', 'plagiarismsearch_api_key', get_string('api_key', 'plagiarism_plagiarismsearch'), array('size' => '40'));
        $mform->addRule('plagiarismsearch_api_key', null, 'required', null, 'client');

        $mform->addElement('select', 'plagiarismsearch_filter_chars', get_string("filter_chars", "plagiarism_plagiarismsearch"), $notoryes);
        $mform->setDefault('plagiarismsearch_filter_chars', 0);

        $mform->addElement('select', 'plagiarismsearch_filter_references', get_string("filter_references", "plagiarism_plagiarismsearch"), $notoryes);
        $mform->setDefault('plagiarismsearch_filter_references', 0);

        $mform->addElement('select', 'plagiarismsearch_filter_quotes', get_string("filter_quotes", "plagiarism_plagiarismsearch"), $notoryes);
        $mform->setDefault('plagiarismsearch_filter_quotes', 0);

        // $mform->addElement('select', 'plagiarismsearch_autostart', get_string("autostart", "plagiarism_plagiarismsearch"), $notoryes);
        // $mform->disabledIf('plagiarismsearch_autostart', 'plagiarismsearch_use', 'eq', 0);
        // $mform->addHelpButton('plagiarismsearch_student_disclosure', 'studentdisclosure', 'plagiarism_plagiarismsearch');
        $mform->setDefault('plagiarismsearch_student_disclosure', get_string('studentdisclosuredefault', 'plagiarism_plagiarismsearch'));
        $mform->addElement('textarea', 'plagiarismsearch_student_disclosure', get_string('studentdisclosure', 'plagiarism_plagiarismsearch'), 'wrap="virtual" rows="6" cols="50"');

        $this->add_action_buttons(true);
    }

}
