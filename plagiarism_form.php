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

global $CFG;
require_once($CFG->dirroot . '/lib/formslib.php');

class plagiarism_setup_form extends moodleform {

    /**
     * Define the form
     */
    public function definition() {

        $mform = $this->_form;

        $prefix = plagiarismsearch_config::CONFIG_PREFIX;
        $reporttypes = plagiarismsearch_config::get_report_types();
        $notoryes = array(
                0 => $this->translate('no', null),
                1 => $this->translate('yes', null),
        );

        $field = plagiarismsearch_config::FIELD_ENABLED;
        $mform->addElement('html', $this->translate('text_plain'));
        $mform->addElement('checkbox', $field, $this->translate($field));

        $field = plagiarismsearch_config::FIELD_API_URL;
        $mform->addElement('text', $prefix . $field, $this->translate($field), array('size' => '40'));
        $mform->addRule($prefix . $field, null, 'required', null, 'client');
        $mform->setDefault($prefix . $field, 'https://plagiarismsearch.com/api/v3');
        $mform->setType($prefix . $field, PARAM_TEXT);

        $field = plagiarismsearch_config::FIELD_API_USER;
        $mform->addElement('text', $prefix . $field, $this->translate($field), array('size' => '40'));
        $mform->addRule($prefix . $field, null, 'required', null, 'client');
        $mform->setType($prefix . $field, PARAM_TEXT);

        $field = plagiarismsearch_config::FIELD_API_KEY;
        $mform->addElement('text', $prefix . $field, $this->translate($field), array('size' => '40'));
        $mform->addRule($prefix . $field, null, 'required', null, 'client');
        $mform->setType($prefix . $field, PARAM_TEXT);

        $field = plagiarismsearch_config::FIELD_API_DEBUG;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 0);

        $field = plagiarismsearch_config::FIELD_AUTO_CHECK;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 1);

        $field = plagiarismsearch_config::FIELD_MANUAL_CHECK;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 0);

        $field = plagiarismsearch_config::FIELD_ADD_TO_STORAGE;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 1);

        $field = plagiarismsearch_config::FIELD_SOURCES_TYPE;
        $mform->addElement('select', $prefix . $field, $this->translate($field), plagiarismsearch_config::get_submit_types());
        $mform->setDefault($prefix . $field, plagiarismsearch_config::SUBMIT_WEB_STORAGE);

        $field = plagiarismsearch_config::FIELD_DETECT_AI;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 0);

        $field = plagiarismsearch_config::FIELD_FILTER_CHARS;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 0);

        $field = plagiarismsearch_config::FIELD_FILTER_REFERENCES;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 0);

        $field = plagiarismsearch_config::FIELD_FILTER_QUOTES;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 0);

        $field = plagiarismsearch_config::FIELD_FILTER_PLAGIARISM;
        $mform->addElement('select', $prefix . $field, $this->translate($field), plagiarismsearch_config::get_plagiarism_filters());
        $mform->setDefault($prefix . $field, plagiarismsearch_config::FILTER_PLAGIARISM_USER_COURSE);

        $field = plagiarismsearch_config::FIELD_REPORT_LANGUAGE;
        $mform->addElement('select', $prefix . $field, $this->translate($field), plagiarismsearch_config::get_report_languages());
        $mform->setDefault($prefix . $field, plagiarismsearch_config::REPORT_PDF);

        $field = plagiarismsearch_config::FIELD_REPORT_TYPE;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $reporttypes);
        $mform->setDefault($prefix . $field, plagiarismsearch_config::REPORT_PDF);

        $field = plagiarismsearch_config::FIELD_STUDENT_SHOW_REPORTS;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $reporttypes);
        $mform->setDefault($prefix . $field, plagiarismsearch_config::REPORT_PDF);

        $field = plagiarismsearch_config::FIELD_STUDENT_SHOW_PERCENTAGE;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 1);

        $field = plagiarismsearch_config::FIELD_STUDENT_SUBMIT;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 0);

        $field = plagiarismsearch_config::FIELD_STUDENT_RESUBMIT;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 0);

        $field = plagiarismsearch_config::FIELD_STUDENT_RESUBMIT_NUMBERS;
        $mform->addElement('text', $prefix . $field, $this->translate($field));
        $mform->setDefault($prefix . $field, '');
        $mform->setType($prefix . $field, PARAM_TEXT);

        $field = plagiarismsearch_config::FIELD_STUDENT_DISCLOSURE;
        $mform->addElement('textarea', $prefix . $field, $this->translate($field), 'wrap="virtual" rows="6" cols="50"');
        $mform->setDefault($prefix . $field, $this->translate('student_disclosure_default'));

        $field = plagiarismsearch_config::FIELD_PARSE_TEXT_URLS;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, 0);

        $field = plagiarismsearch_config::FIELD_VALID_PARSED_TEXT_URLS;
        $mform->addElement('textarea', $prefix . $field, $this->translate($field), 'wrap="virtual" rows="3" cols="50"');
        $mform->setDefault($prefix . $field, plagiarismsearch_config::get_default_valid_parsed_text_urls());

        $this->add_action_buttons(true);
    }

    protected function translate($value, $module = 'plagiarism_plagiarismsearch') {
        return plagiarismsearch_base::translate($value, $module);
    }

}
