<?php
// This file is part of the PlagiarismSearch plugin for Moodle - http://moodle.org/
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
 * lib.php - Contains Plagiarism plugin specific functions called by Modules.
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/plagiarism/lib.php');
require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/classes/map.php');

class plagiarism_plugin_plagiarismsearch extends plagiarism_plugin {

    protected static $cacheviewlinks = array();
    protected static $cachestudentroleid;
    protected static $cacheisstudent;

    /**
     * Check if the user is able to view links (and cache the result)
     * @param int $cmid
     * @return bool
     */
    public static function has_capability_links($cmid) {
        if (!isset(static::$cacheviewlinks[$cmid])) {
            $context = context_module::instance($cmid);
            static::$cacheviewlinks[$cmid] = has_capability('plagiarism/plagiarismsearch:viewlinks', $context);

            if (static::is_student($cmid)) {
                if (plagiarismsearch_config::get_config_or_settings($cmid, 'student_show_reports')) {
                    static::$cacheviewlinks[$cmid] = true;
                } else if (plagiarismsearch_config::get_config_or_settings($cmid, 'student_show_percentage')) {
                    static::$cacheviewlinks[$cmid] = true;
                } else if (plagiarismsearch_config::get_config_or_settings($cmid, 'student_submit')) {
                    static::$cacheviewlinks[$cmid] = true;
                } else if (plagiarismsearch_config::get_config_or_settings($cmid, 'student_resubmit')) {
                    static::$cacheviewlinks[$cmid] = true;
                } else {
                    static::$cacheviewlinks[$cmid] = false;
                }
            }
        }

        return !empty(static::$cacheviewlinks[$cmid]);
    }

    public static function is_enabled($cmid = null) {
        return plagiarismsearch_config::is_enabled($cmid);
    }

    public static function is_student($cmid = null) {
        if (static::$cacheisstudent === null) {
            if (is_siteadmin()) {
                static::$cacheisstudent = false;
            } else {
                $context = context_module::instance($cmid);
                static::$cacheisstudent = has_capability('plagiarism/plagiarismsearch:isstudent', $context);
            }
        }
        return static::$cacheisstudent;
    }


    protected static function get_reports_link_type($cmid = null) {
        if (static::is_student($cmid)) {
            return plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_STUDENT_SHOW_REPORTS);
        } else {
            return plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_REPORT_TYPE);
        }
    }

    public static function has_show_reports_pdf_link($cmid = null) {
        $type = static::get_reports_link_type($cmid);
        return $type & plagiarismsearch_config::REPORT_PDF;
    }

    public static function has_show_reports_html_link($cmid = null) {
        $type = static::get_reports_link_type($cmid);
        return $type & plagiarismsearch_config::REPORT_HTML;
    }

    public static function has_show_reports_link($cmid = null) {
        return (bool) static::get_reports_link_type($cmid);
    }

    public static function has_show_reports_percentage($cmid = null) {
        if (static::is_student($cmid)) {
            return (bool) plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_STUDENT_SHOW_PERCENTAGE);
        } else {
            return true;
        }
    }

    public static function has_show_submit_link($cmid = null) {
        $manualsubmit = plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_MANUAL_CHECK);
        if (static::is_student($cmid)) {
            return $manualsubmit && plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_STUDENT_SUBMIT);
        } else {
            return $manualsubmit;
        }
    }

    public static function has_show_resubmit_link($cmid = null, $userid = null, $filehash = null) {
        $manualsubmit = plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_MANUAL_CHECK);
        $isstudent = static::is_student($cmid);

        if (!$isstudent) {
            return $manualsubmit;
        }

        // Is student?
        if (!plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_STUDENT_RESUBMIT)) {
            return false;
        }

        // Check student resubmit numbers.
        if (!$cmid || !$userid || !$filehash) {
            return $manualsubmit;
        }

        $limit = plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_STUDENT_RESUBMIT_NUMBERS);
        if ($limit) {
            $countreports = plagiarismsearch_reports::count_valid(array(
                        'cmid' => $cmid,
                        'userid' => $userid,
                        'filehash' => $filehash,
            ));

            if ($countreports > $limit) {
                return false;
            }
        }

        return $manualsubmit;
    }

    /**
     * Hook to allow plagiarism specific information to be displayed beside a submission.
     *
     * @param $linkarray
     *
     * @return string
     * @internal param array $linkarraycontains all relevant information for the plugin to generate a link.
     *
     */
    public function get_links($linkarray) {

        $cmid = $linkarray['cmid'];

        if (!$this->is_enabled($cmid)) {
            return null;
        }

        if (!$this->has_capability_links($cmid)) {
            return null;
        }

        if (!empty($linkarray['file'])) {
            return $this->get_links_file($linkarray);
        } else if (!empty($linkarray['content'])) {
            return $this->get_links_content($linkarray);
        }
    }

    protected function get_links_file($linkarray) {
        $cmid = $linkarray['cmid'];
        $userid = $linkarray['userid'];
        /* @var $file \stored_file */
        $file = $linkarray['file'];
        $filehash = $file->get_pathnamehash();

        $report = $this->get_top_report($cmid, $userid, $filehash);

        $result = " \n";
        $result .= $this->render_report_links($cmid, $report);

        $urlconfig = array(
            'userid' => $userid,
            'cmid' => $cmid,
            'filehash' => $filehash,
            'sesskey' => sesskey(),
            'force' => 0,
        );

        if (!empty($report) && !plagiarismsearch_reports::is_processing($report)) {
            $urlconfig['force'] = 1;
        }

        $submiturl = new moodle_url('/plagiarism/plagiarismsearch/submit.php', $urlconfig);

        $result .= " \n";
        if ($report) {
            if (!plagiarismsearch_reports::is_processing($report) && $this->has_show_resubmit_link($cmid, $userid, $filehash)) {
                $result .= html_writer::empty_tag('br');
                $result .= html_writer::link($submiturl, $this->translate('resubmit'));
            }
        } else if ($this->has_show_submit_link($cmid)) {
            $result .= html_writer::link($submiturl, $this->translate('submit'));
        }

        return $result;
    }

    protected function get_links_content($linkarray) {
        $cmid = $linkarray['cmid'];
        $userid = $linkarray['userid'];
        $hash = plagiarismsearch_core::get_text_hash($linkarray['content']);

        $report = $this->get_top_report($cmid, $userid, $hash);
        // If a text submission has been made, we can only display links for current attempts so don't show links previous attempts.
        // This will need to be reworked when linkarray contains submission id.

        $result = " \n";
        $result .= $this->render_report_links($cmid, $report);
        $result .= " \n";

        return $result;
    }

    protected function render_report_links($cmid, $report) {
        $result = '';
        if (empty($report)) {
            return $result;
        }

        if (plagiarismsearch_reports::is_checked($report)) {
            if ($this->has_show_reports_percentage($cmid)) {
                $result .= html_writer::tag('span', $this->translate('plagiarism').':&nbsp;' .
                                html_writer::tag('span', round($report->plagiarism, 2) . '%', array(
                                    'class' => plagiarismsearch_reports::get_color_class($report))
                                ), array('title' => $this->translate('link_title'))
                );
            }
            if ($this->has_show_reports_pdf_link($cmid)) {
                $link = plagiarismsearch_reports::build_pdf_link($report, $cmid);
                if ($link) {
                    $result .= html_writer::empty_tag('br');
                    $result .= html_writer::link($link, $this->translate('pdf_report'), array(
                                'target' => '_blank'
                                    )
                    );
                }
            }
            if ($this->has_show_reports_html_link($cmid)) {
                $link = plagiarismsearch_reports::build_html_link($report, $cmid);
                if ($link) {
                    $result .= html_writer::empty_tag('br');
                    $result .= html_writer::link($link, $this->translate('html_report'), array(
                                'target' => '_blank'
                                    )
                    );
                }
            }
        } else if (plagiarismsearch_reports::is_processing($report)) {
            // Add check status button.
            if ($this->has_show_reports_link($cmid)) {
                $checkurl = new moodle_url('/plagiarism/plagiarismsearch/status.php', array(
                    'cmid' => $cmid,
                    'id' => $report->id,
                    'sesskey' => sesskey(),
                ));

                $result .= $this->translate('processing') . "\n "
                        . html_writer::link($checkurl, $this->translate('check_status'));
            }
        } else if ($this->has_show_reports_link($cmid)) {
            $result .= $report->log ? $report->log : $this->translate('unknown_error');
        }

        return $result;
    }

    protected function get_top_report($cmid, $userid, $hash) {
        return plagiarismsearch_reports::get_one_top(array(
                    'cmid' => $cmid,
                    'userid' => $userid,
                    'filehash' => $hash,
        ));
    }

    protected function get_form_element_default_value($cmid, $field) {
        return plagiarismsearch_config::get_config_or_settings($cmid, $field);
    }

    /**
     * Hook to add plagiarism specific settings to a module settings page
     * @param object $mform  - Moodle form
     * @param object $context - current context
     */
    public function get_form_elements_module($mform, $context, $modulename = "") {
        if ($modulename != 'mod_assign') {
            return;
        }
        $cmid = optional_param('update', 0, PARAM_INT);

        $prefix = plagiarismsearch_config::CONFIG_PREFIX;
        $notoryes = array(
            0 => $this->translate('no', null),
            1 => $this->translate('yes', null),
        );
        $reporttypes = plagiarismsearch_config::get_report_types();

        $mform->addElement('header', 'plagiarismsearchdesc', $this->translate('plagiarismsearch'));

        $field = plagiarismsearch_config::FIELD_ENABLED;
        $mform->addElement('checkbox', $prefix . $field, $this->translate($field));
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_AUTO_CHECK;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_MANUAL_CHECK;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_ADD_TO_STORAGE;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_SOURCES_TYPE;
        $mform->addElement('select', $prefix . $field, $this->translate($field), plagiarismsearch_config::get_submit_types());
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_FILTER_CHARS;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_FILTER_REFERENCES;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_FILTER_QUOTES;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_FILTER_PLAGIARISM;
        $mform->addElement('select', $prefix . $field, $this->translate($field), plagiarismsearch_config::get_plagiarism_filters());
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_REPORT_LANGUAGE;
        $mform->addElement('select', $prefix . $field, $this->translate($field), plagiarismsearch_config::get_report_languages());
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_REPORT_TYPE;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $reporttypes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_STUDENT_SHOW_REPORTS;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $reporttypes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_STUDENT_SHOW_PERCENTAGE;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_STUDENT_SUBMIT;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_STUDENT_RESUBMIT;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));

        $field = plagiarismsearch_config::FIELD_STUDENT_RESUBMIT_NUMBERS;
        $mform->addElement('text', $prefix . $field, $this->translate($field));
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));
        $mform->setType($prefix . $field, PARAM_INT);

        $field = plagiarismsearch_config::FIELD_PARSE_TEXT_URLS;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
        $mform->setDefault($prefix . $field, $this->get_form_element_default_value($cmid, $field));
    }

    /**
     * Hook to save plagiarism specific settings on a module settings page
     * @param object $data - data from an mform submission.
     */
    public function save_form_elements($data) {
        $cmid = $data->coursemodule;
        $enabled = plagiarismsearch_config::FIELD_ENABLED;

        $fields = plagiarismsearch_config::fields();
        $fields[$enabled] = plagiarismsearch_config::CONFIG_PREFIX . $enabled;
        foreach ($fields as $name => $field) {
            if (isset($data->{$field})) {
                $value = $data->{$field};
                $this->save_form_config($cmid, $name, $value);
            } else if (in_array($name, array($enabled))) {
                // Checkboxes default set 0.
                $this->save_form_config($cmid, $name, 0);
            }
        }
    }

    protected function translate($value, $module = 'plagiarism_plagiarismsearch') {
        return plagiarismsearch_base::translate($value, $module);
    }

    protected function save_form_config($cmid, $name, $value) {
        return plagiarismsearch_config::set_config($cmid, $name, $value);
    }

    /**
     * Hook to allow a disclosure to be printed notifying users what will happen with their submission.
     *
     * @param int $cmid - course module id
     *
     * @return string
     */
    public function print_disclosure($cmid) {
        global $OUTPUT;

        $outputhtml = '';
        $disclosure = plagiarismsearch_config::get_config_or_settings($cmid, 'student_disclosure');

        if ($this->is_enabled($cmid) && !empty($disclosure)) {
            $outputhtml .= $OUTPUT->box_start('generalbox boxaligncenter', 'intro');
            $formatoptions = new stdClass;
            $formatoptions->noclean = true;
            $outputhtml .= format_text($disclosure, FORMAT_MOODLE, $formatoptions);
            $outputhtml .= $OUTPUT->box_end();
        }

        return $outputhtml;
    }

    public function plagiarism_cron() {
        return cron();
    }

    public function cron() {
        global $CFG;

        $running = get_config('plagiarism_plagiarismsearch', 'plagiarismsearch_cronrunning');

        if ($running && $running > time()) {
            mtrace("Plagiarismsearch cron still running");
            return true; // Already running.
        }
        $running = time() + 2 * 60 * 60; // Timeout after 2.

        set_config('plagiarismsearch_cron_running', $running, 'plagiarism_plagiarismsearch');

        require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/cron.php');

        set_config('plagiarismsearch_cron_running', 0, 'plagiarism_plagiarismsearch');

        return true;
    }

    public static function log() {
        global $CFG;
        $args = func_get_args();
        if (!$args) {
            return false;
        }
        $f = fopen($CFG->dirroot . '/plagiarism/plagiarismsearch/log.txt', 'a');
        if (!$f) {
            return false;
        }
        foreach ($args as $arg) {
            fwrite($f, var_export($arg, true) . "\n------------\n");
        }
        fclose($f);

        return true;
    }

    public static function event_handler(core\event\base $event) {
        $handler = new plagiarismsearch_event_handler($event);
        return $handler->run();
    }

}

/**
 * Add the PlagiarismSearch settings form to an add/edit activity page.
 *
 * @param moodleform_mod $formwrapper
 * @param MoodleQuickForm $mform
 * @return mixed
 */
function plagiarism_plagiarismsearch_coursemodule_standard_elements($formwrapper, $mform) {
    $psplugin = new plagiarism_plugin_plagiarismsearch();
    $course = $formwrapper->get_course();
    $context = context_course::instance($course->id);
    $modulename = $formwrapper->get_current()->modulename;

    $psplugin->get_form_elements_module(
        $mform,
        $context,
        isset($modulename) ? 'mod_' . $modulename : ''
    );
}


/**
 * Handle saving data from the PlagiarismSearch settings form.
 *
 * @param stdClass $data
 * @param stdClass $course
 */
function plagiarism_plagiarismsearch_coursemodule_edit_post_actions($data, $course) {
    $psplugin = new plagiarism_plugin_plagiarismsearch();
    $psplugin->save_form_elements($data, $course);

    return $data;
}
