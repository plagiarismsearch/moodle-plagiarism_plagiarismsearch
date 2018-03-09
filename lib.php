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
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    // It must be included from a Moodle page
}

// define('LOG_SERVER_COMMUNICATION', 1);
// get global class
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

    public static function has_show_reports_link($cmid = null) {
        if (static::is_student($cmid)) {
            return (bool) plagiarismsearch_config::get_config_or_settings($cmid, 'student_show_reports');
        } else {
            return true;
        }
    }

    public static function has_show_reports_percentage($cmid = null) {
        if (static::is_student($cmid)) {
            return (bool) plagiarismsearch_config::get_config_or_settings($cmid, 'student_show_percentage');
        } else {
            return true;
        }
    }

    public static function has_show_submit_link($cmid = null) {
        $manualsubmit = plagiarismsearch_config::get_config_or_settings($cmid, 'manual_check');
        if (static::is_student($cmid)) {
            return $manualsubmit and (bool) plagiarismsearch_config::get_config_or_settings($cmid, 'student_submit');
        } else {
            return $manualsubmit;
        }
    }

    public static function has_show_resubmit_link($cmid = null, $userid = null, $filehash = null) {
        $manualsubmit = plagiarismsearch_config::get_config_or_settings($cmid, 'manual_check');
        $isstudent = static::is_student($cmid);

        if (!$isstudent) {
            return $manualsubmit;
        }

        // Is student
        if (!plagiarismsearch_config::get_config_or_settings($cmid, 'student_resubmit')) {
            return false;
        }

        // Check student resubmit numbers
        if ($cmid and
                $userid and
                $filehash and
                $limit = plagiarismsearch_config::get_config_or_settings($cmid, 'student_resubmit_numbers')
        ) {
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
            return;
        }

        if (!$this->has_capability_links($cmid)) {
            return;
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

        if (!empty($report) and ! plagiarismsearch_reports::is_processing($report)) {
            $urlconfig['force'] = 1;
        }

        $submiturl = new moodle_url('/plagiarism/plagiarismsearch/submit.php', $urlconfig);

        $result .= " \n";
        if ($report) {
            if (!plagiarismsearch_reports::is_processing($report) and $this->has_show_resubmit_link($cmid, $userid, $filehash)) {
                $result .= html_writer::empty_tag('br');
                $result .= html_writer::link($submiturl, get_string('resubmit', 'plagiarism_plagiarismsearch'));
            }
        } else if ($this->has_show_submit_link($cmid)) {
            $result .= html_writer::link($submiturl, get_string('submit', 'plagiarism_plagiarismsearch'));
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
        if ($report) {

            $checkurl = new moodle_url('/plagiarism/plagiarismsearch/status.php', array(
                'cmid' => $cmid,
                'id' => $report->id,
                'sesskey' => sesskey(),
            ));

            if (plagiarismsearch_reports::is_checked($report)) {
                if ($this->has_show_reports_percentage($cmid)) {
                    $result .= html_writer::tag('span', 'Plagiarism:&nbsp;' .
                                    html_writer::tag('span', round($report->plagiarism, 2) . '%', array(
                                        'class' => plagiarismsearch_reports::get_color_class($report))
                                    ), array('title' => get_string('link_title', 'plagiarism_plagiarismsearch'))
                    );
                }
                if ($report->url and $this->has_show_reports_link($cmid)) {
                    $result .= html_writer::empty_tag('br');
                    $result .= html_writer::link($report->url, get_string('pdf_report', 'plagiarism_plagiarismsearch'), array(
                                'target' => '_blank'
                                    )
                    );
                }
            } else if (plagiarismsearch_reports::is_processing($report)) {
                // add check status button
                if ($this->has_show_reports_link($cmid)) {
                    $result .= get_string('processing', 'plagiarism_plagiarismsearch') . "\n "
                            . html_writer::link($checkurl, get_string('check_status', 'plagiarism_plagiarismsearch'));
                }
            } else if ($this->has_show_reports_link($cmid)) {
                $result .= $report->log ? $report->log : get_string('unknown_error', 'plagiarism_plagiarismsearch');
            }
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

    /**
     * Hook to add plagiarism specific settings to a module settings page
     * @param object $mform  - Moodle form
     * @param object $context - current context
     */
    public function get_form_elements_module($mform, $context, $modulename = "") {
        if ($modulename == 'mod_assign') {
            $cmid = optional_param('update', 0, PARAM_INT);

            $notoryes = array(
                0 => get_string('no'),
                1 => get_string('yes'),
            );
            $submittype = array(
                plagiarismsearch_reports::SUBMIT_WEB_STORAGE => get_string('sources_doc_web_storage', 'plagiarism_plagiarismsearch'),
                plagiarismsearch_reports::SUBMIT_WEB => get_string('sources_doc_web', 'plagiarism_plagiarismsearch'),
                plagiarismsearch_reports::SUBMIT_STORAGE => get_string('sources_doc_storage', 'plagiarism_plagiarismsearch'),
            );

            $mform->addElement('header', 'plagiarismsearchdesc', get_string('plagiarismsearch', 'plagiarism_plagiarismsearch'));

            $mform->addElement('checkbox', 'plagiarismsearch_use', get_string('use', 'plagiarism_plagiarismsearch'));
            $mform->setDefault('plagiarismsearch_use', plagiarismsearch_config::get_config_or_settings($cmid, 'use'));

            $mform->addElement('select', 'plagiarismsearch_auto_check', get_string('auto_check', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_auto_check', plagiarismsearch_config::get_config_or_settings($cmid, 'auto_check'));

            $mform->addElement('select', 'plagiarismsearch_manual_check', get_string('manual_check', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_manual_check', plagiarismsearch_config::get_config_or_settings($cmid, 'manual_check'));

            $mform->addElement('select', 'plagiarismsearch_add_to_storage', get_string('add_to_storage', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_add_to_storage', plagiarismsearch_config::get_config_or_settings($cmid, 'add_to_storage'));

            $mform->addElement('select', 'plagiarismsearch_sources_type', get_string('sources_type', 'plagiarism_plagiarismsearch'), $submittype);
            $mform->setDefault('plagiarismsearch_sources_type', plagiarismsearch_config::get_config_or_settings($cmid, 'sources_type'));

            $mform->addElement('select', 'plagiarismsearch_filter_chars', get_string('filter_chars', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_filter_chars', plagiarismsearch_config::get_config_or_settings($cmid, 'filter_chars'));

            $mform->addElement('select', 'plagiarismsearch_filter_references', get_string('filter_references', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_filter_references', plagiarismsearch_config::get_config_or_settings($cmid, 'filter_references'));

            $mform->addElement('select', 'plagiarismsearch_filter_quotes', get_string('filter_quotes', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_filter_quotes', plagiarismsearch_config::get_config_or_settings($cmid, 'filter_quotes'));

            $mform->addElement('select', 'plagiarismsearch_student_show_reports', get_string('student_show_reports', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_student_show_reports', plagiarismsearch_config::get_config_or_settings($cmid, 'student_show_reports'));

            $mform->addElement('select', 'plagiarismsearch_student_show_percentage', get_string('student_show_percentage', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_student_show_percentage', plagiarismsearch_config::get_config_or_settings($cmid, 'student_show_percentage'));

            $mform->addElement('select', 'plagiarismsearch_student_submit', get_string('student_submit', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_student_submit', plagiarismsearch_config::get_config_or_settings($cmid, 'student_submit'));

            $mform->addElement('select', 'plagiarismsearch_student_resubmit', get_string('student_resubmit', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_student_resubmit', plagiarismsearch_config::get_config_or_settings($cmid, 'student_resubmit'));

            $mform->addElement('text', 'plagiarismsearch_student_resubmit_numbers', get_string('student_resubmit_numbers', 'plagiarism_plagiarismsearch'));
            $mform->setDefault('plagiarismsearch_student_resubmit_numbers', plagiarismsearch_config::get_config_or_settings($cmid, 'student_resubmit_numbers'));
            $mform->setType('plagiarismsearch_student_resubmit_numbers', PARAM_TEXT);
        }
    }

    /**
     * Hook to save plagiarism specific settings on a module settings page
     * @param object $data - data from an mform submission.
     */
    public function save_form_elements($data) {
        $cmid = $data->coursemodule;;

        $fields = plagiarismsearch_config::fields();
        foreach ($fields as $name => $field) {
            if (isset($data->{$field})) {
                $value = $data->{$field};
                $this->save_form_config($cmid, $name, $value);
            } else if(in_array($name, array('use'))) {
                // Checkboxes default set 0
                $this->save_form_config($cmid, $name, 0);
            }
        }
    }

    protected function save_form_config($cmid, $name, $value) {
        if ($config = plagiarismsearch_config::get_one(array('cmid' => $cmid, 'name' => $name))) {
            return plagiarismsearch_config::update(array('value' => $value), $config->id);
        } else {
            return plagiarismsearch_config::insert(array('cmid' => $cmid, 'name' => $name, 'value' => $value));
        }
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
        $running = time() + 2 * 60 * 60; // Timeout after 2

        set_config('plagiarismsearch_cron_running', $running, 'plagiarism_plagiarismsearch');

        require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/cron.php');

        set_config('plagiarismsearch_cron_running', 0, 'plagiarism_plagiarismsearch');

        return true;
    }

    public static function log() {
        global $CFG;
        $args = func_get_args();
        if ($args and $f = fopen($CFG->dirroot . '/plagiarism/plagiarismsearch/log.txt', 'a')) {
            foreach ($args as $arg) {
                fwrite($f, var_export($arg, true) . "\n------------\n");
            }
            fclose($f);

            return true;
        }
        return false;
    }

    public static function event_handler(core\event\base $event) {
        $handler = new plagiarismsearch_event_handler($event);
        return $handler->run();
    }

}
