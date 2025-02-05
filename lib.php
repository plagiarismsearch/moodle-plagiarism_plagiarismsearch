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

/**
 * Plagiarism plugin for PlagiarismSearch.
 */
class plagiarism_plugin_plagiarismsearch extends plagiarism_plugin {

    /**
     * Cache view links
     *
     * @var array
     */
    protected static $cacheviewlinks = [];
    /**
     * Cache student role id
     *
     * @var
     */
    protected static $cachestudentroleid;
    /**
     * Cache is student
     *
     * @var bool|null
     */
    protected static $cacheisstudent;

    /**
     * Check if the user is able to view links (and cache the result)
     *
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

    /**
     * Check if plagiarism search is enabled for a specific course module.
     *
     * @param int|null $cmid Course module ID. Default is null.
     * @return bool True if enabled, false otherwise.
     */
    public static function is_enabled($cmid = null) {
        return plagiarismsearch_config::is_enabled($cmid);
    }

    /**
     * Determine if the current user is a student in the given course module.
     *
     * @param int|null $cmid Course module ID. Default is null.
     * @return bool True if the user is a student, false otherwise.
     */
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

    /**
     * Get the type of report link to display based on the user's role.
     *
     * @param int|null $cmid Course module ID. Default is null.
     * @return int The type of report link.
     */
    protected static function get_reports_link_type($cmid = null) {
        if (static::is_student($cmid)) {
            return plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_STUDENT_SHOW_REPORTS);
        }
        return plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_REPORT_TYPE);
    }

    /**
     * Check if the PDF report link should be displayed.
     *
     * @param int|null $cmid Course module ID. Default is null.
     * @return bool True if the PDF report link should be displayed, false otherwise.
     */
    public static function has_show_reports_pdf_link($cmid = null) {
        $type = static::get_reports_link_type($cmid);
        return (bool) $type & plagiarismsearch_config::REPORT_PDF;
    }

    /**
     * Check if the HTML report link should be displayed.
     *
     * @param int|null $cmid Course module ID. Default is null.
     * @return bool True if the HTML report link should be displayed, false otherwise.
     */
    public static function has_show_reports_html_link($cmid = null) {
        $type = static::get_reports_link_type($cmid);
        return (bool) $type & plagiarismsearch_config::REPORT_HTML;
    }

    /**
     * Check if any report link should be displayed.
     *
     * @param int|null $cmid Course module ID. Default is null.
     * @return bool True if any report link should be displayed, false otherwise.
     */
    public static function has_show_reports_link($cmid = null) {
        return (bool) static::get_reports_link_type($cmid);
    }

    /**
     * Check if the plagiarism percentage should be displayed.
     *
     * @param int|null $cmid Course module ID. Default is null.
     * @return bool True if the percentage should be displayed, false otherwise.
     */
    public static function has_show_reports_percentage($cmid = null) {
        if (static::is_student($cmid)) {
            return (bool) plagiarismsearch_config::get_config_or_settings($cmid,
                    plagiarismsearch_config::FIELD_STUDENT_SHOW_PERCENTAGE);
        }
        return true;
    }

    /**
     * Check if the submit link should be displayed.
     *
     * @param int|null $cmid Course module ID. Default is null.
     * @return bool True if the submit link should be displayed, false otherwise.
     */
    public static function has_show_submit_link($cmid = null) {
        $manualsubmit = plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_MANUAL_CHECK);
        if (static::is_student($cmid)) {
            return $manualsubmit &&
                    plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_STUDENT_SUBMIT);
        }
        return (bool) $manualsubmit;
    }

    /**
     * Check if the resubmit link should be displayed for the given parameters.
     *
     * @param int|null $cmid Course module ID. Default is null.
     * @param int|null $userid User ID. Default is null.
     * @param string|null $filehash File hash. Default is null.
     * @return bool True if the resubmit link should be displayed, false otherwise.
     */
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
            $countreports = plagiarismsearch_reports::count_valid([
                    'cmid' => $cmid,
                    'userid' => $userid,
                    'filehash' => $filehash,
            ]);

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
     * @return string|null
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
        return null;
    }

    /**
     * Get links file
     *
     * @param array $linkarray
     * @return string
     * @throws moodle_exception
     */
    protected function get_links_file($linkarray) {
        $cmid = $linkarray['cmid'];
        $userid = $linkarray['userid'];
        /* @var $file \stored_file The file object to validate. */
        $file = $linkarray['file'];
        $filehash = $file->get_pathnamehash();

        $report = $this->get_top_report($cmid, $userid, $filehash);

        $result = " \n";
        $result .= $this->render_report_links($cmid, $report);

        $urlconfig = [
                'userid' => $userid,
                'cmid' => $cmid,
                'filehash' => $filehash,
                'sesskey' => sesskey(),
                'force' => 0,
        ];

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

    /**
     * Builds links
     *
     * @param array $linkarray
     * @return string
     */
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

    /**
     * Render report links
     *
     * @param int $cmid
     * @param object $report
     * @return string
     * @throws moodle_exception
     */
    protected function render_report_links($cmid, $report) {
        $result = '';
        if (empty($report)) {
            return $result;
        }

        if (plagiarismsearch_reports::is_checked($report)) {
            if ($this->has_show_reports_percentage($cmid)) {
                $result .= html_writer::tag('span', $this->translate('plagiarism') . ':&nbsp;' .
                        html_writer::tag('span', round($report->plagiarism, 2) . '%', [
                                        'class' => plagiarismsearch_reports::get_color_class($report)]
                        ), ['title' => $this->translate('link_title')]
                );

                if (plagiarismsearch_reports::is_checked_ai($report)) {
                    $aititle = $this->translate('ai_rate') . ': ' .
                            round($report->ai_rate, 2) . '%' . ', ' .
                            $this->translate('ai_probability') . ': ' .
                            round($report->ai_probability, 2) . '%';

                    $result .= html_writer::empty_tag('br');
                    $result .= html_writer::tag('span', $this->translate('ai') . ':&nbsp;' .
                            html_writer::tag('span', round($report->ai_rate, 2) . '%', [
                                            'class' => plagiarismsearch_reports::get_ai_color_class($report)]
                            ), ['title' => $aititle]
                    );
                }
            }
            if ($this->has_show_reports_pdf_link($cmid)) {
                $link = plagiarismsearch_reports::build_pdf_link($report, $cmid);
                if ($link) {
                    $result .= html_writer::empty_tag('br');
                    $result .= html_writer::link($link, $this->translate('pdf_report'), [
                                    'target' => '_blank',
                            ]
                    );
                }
            }
            if ($this->has_show_reports_html_link($cmid)) {
                $link = plagiarismsearch_reports::build_html_link($report, $cmid);
                if ($link) {
                    $result .= html_writer::empty_tag('br');
                    $result .= html_writer::link($link, $this->translate('html_report'), [
                                    'target' => '_blank',
                            ]
                    );
                }
            }
        } else if (plagiarismsearch_reports::is_processing($report)) {
            // Add check status button.
            if ($this->has_show_reports_link($cmid)) {
                $checkurl = new moodle_url('/plagiarism/plagiarismsearch/status.php', [
                        'cmid' => $cmid,
                        'id' => $report->id,
                        'sesskey' => sesskey(),
                ]);

                $result .= $this->translate('processing') . "\n "
                        . html_writer::link($checkurl, $this->translate('check_status'));
            }
        } else if ($this->has_show_reports_link($cmid)) {
            $result .= $report->log ? $report->log : $this->translate('unknown_error');
        }

        return $result;
    }

    /**
     * Find the newest report for a given user and file hash.
     *
     * @param int $cmid
     * @param int $userid
     * @param string $hash
     * @return false|mixed
     */
    protected function get_top_report($cmid, $userid, $hash) {
        return plagiarismsearch_reports::get_one_top([
                'cmid' => $cmid,
                'userid' => $userid,
                'filehash' => $hash,
        ]);
    }

    /**
     * Config wrapper to get the default value for a form element.
     *
     * @param int $cmid
     * @param string $field
     * @return array|bool|mixed|null
     */
    protected function get_form_element_default_value($cmid, $field) {
        return plagiarismsearch_config::get_config_or_settings($cmid, $field);
    }

    /**
     * Hook to add plagiarism specific settings to a module settings page
     *
     * @param object $mform
     * @param object $context
     * @param string $modulename
     * @return void
     * @throws coding_exception
     */
    public function get_form_elements_module($mform, $context, $modulename = "") {
        if ($modulename != 'mod_assign') {
            return;
        }

        $prefix = plagiarismsearch_config::CONFIG_PREFIX;

        $formfieds = isset($mform->_elementIndex) ? $mform->_elementIndex : [];
        if (isset($formfieds[$prefix . plagiarismsearch_config::FIELD_ENABLED])) {
            return;
        }
        $cmid = optional_param('update', 0, PARAM_INT);

        $notoryes = [
                0 => $this->translate('no', null),
                1 => $this->translate('yes', null),
        ];
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

        $field = plagiarismsearch_config::FIELD_DETECT_AI;
        $mform->addElement('select', $prefix . $field, $this->translate($field), $notoryes);
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
     *
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
            } else if (in_array($name, [$enabled])) {
                // Checkboxes default set 0.
                $this->save_form_config($cmid, $name, 0);
            }
        }
    }

    /**
     * Translate wrapper
     *
     * @param string $value
     * @param string $module
     * @return lang_string|mixed|string
     */
    protected function translate($value, $module = 'plagiarism_plagiarismsearch') {
        return plagiarismsearch_base::translate($value, $module);
    }

    /**
     * Save form config
     *
     * @param int $cmid
     * @param string $name
     * @param mixed $value
     * @return bool|int|null
     */
    protected function save_form_config($cmid, $name, $value) {
        return plagiarismsearch_config::set_config($cmid, $name, $value);
    }

    /**
     * Hook to allow a disclosure to be printed notifying users what will happen with their submission.
     *
     * @param int $cmid - course module id
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

    /**
     * Cron function for old versions of Moodle.
     *
     * @return mixed
     */
    public function plagiarism_cron() {
        return $this->cron();
    }

    /**
     * Cron function for PlagiarismSearch.
     *
     * @return true
     * @throws dml_exception
     */
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

    /**
     * Log function
     *
     * @return bool
     */
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

    /**
     * Event handler
     *
     * @param \core\event\base $event
     * @return void
     */
    public static function event_handler(core\event\base $event) {
        $handler = new plagiarismsearch_event_handler($event);
        $handler->run();
    }

}

/**
 * Add the PlagiarismSearch settings form to an add/edit activity page.
 *
 * @param moodleform_mod $formwrapper
 * @param MoodleQuickForm $mform
 * @return void
 */
function plagiarism_plagiarismsearch_coursemodule_standard_elements($formwrapper, $mform) {
    $psplugin = new plagiarism_plugin_plagiarismsearch();
    $course = $formwrapper->get_course();
    $context = context_course::instance(isset($course->id) ? $course->id : $course);
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
