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

    protected $_viewlinks = array();

    // Check if the user is able to view links (and cache the result)
    public function has_capability_links($cmid) {
        global $CFG;
        if (!isset($this->_viewlinks[$cmid])) {
            if ($CFG->version < 2011120100) {
                $context = get_context_instance(CONTEXT_MODULE, $cmid);
            } else {
                $context = context_module::instance($cmid);
            }
            $viewlinks[$cmid] = has_capability('plagiarism/plagiarismsearch:viewlinks', $context);
        }

        return !empty($viewlinks[$cmid]);
    }

    public function is_enabled($cmid = null) {
        return (bool) plagiarismsearch_config::get_config_or_settings($cmid, 'use');
    }

    public function get_links($linkarray) {
        global $PAGE;

        $cmid = $linkarray['cmid'];

        if (!$this->is_enabled($cmid)) {
            return;
        }

        if (!$this->has_capability_links($cmid)) {
            return;
        }

        /* @var $file \stored_file */
        $userid = $linkarray['userid'];
        $file = $linkarray['file'];
        $filehash = $file->get_pathnamehash();

        $report = plagiarismsearch_reports::get_one_top(array(
                    'cmid' => $cmid,
                    'userid' => $userid,
                    'filehash' => $filehash,
        ));

        $pageurl = $PAGE->url;
        $return = urlencode($PAGE->url);

        $result = " \n";

        if ($report) {

            $checkurl = new moodle_url('/plagiarism/plagiarismsearch/status.php', array(
                'cmid' => $cmid,
                'id' => $report->id,
                'return' => $return,
            ));

            if (plagiarismsearch_reports::is_checked($report)) {
                $result .= html_writer::tag('span', 'Plagiarism:&nbsp;' . html_writer::tag('span', round($report->plagiarism, 2) .
                                        '%', array('class' => plagiarismsearch_reports::get_color_class($report))), array('title' => get_string('link_title', 'plagiarism_plagiarismsearch')));
                if ($report->url) {
                    $result .= html_writer::empty_tag('br');
                    $result .= html_writer::link($report->url, get_string('pdf_report', 'plagiarism_plagiarismsearch'), array('target' => '_blank'));
                }
            } else if (plagiarismsearch_reports::is_processing($report)) {
                // add check status button
                $result .= get_string('processing', 'plagiarism_plagiarismsearch') . "\n "
                        . html_writer::link($checkurl, get_string('check_status', 'plagiarism_plagiarismsearch'));
            } else {
                $result .= $report->log ? $report->log : get_string('unknown_error', 'plagiarism_plagiarismsearch');
            }
        }

        $urlconfig = array(
            'userid' => $userid,
            'cmid' => $cmid,
            'filehash' => $filehash,
            'return' => urlencode($pageurl),
            'sesskey' => sesskey(),
            'force' => 0,
        );

        if (!plagiarismsearch_reports::is_processing($report)) {
            $urlconfig['force'] = 1;
        }

        $submiturl = new moodle_url('/plagiarism/plagiarismsearch/submit.php', $urlconfig);

        $result .= " \n";
        if ($report) {
            if (!plagiarismsearch_reports::is_processing($report)) {
                $result .= html_writer::link($submiturl, get_string('resubmit', 'plagiarism_plagiarismsearch'));
            }
        } else {
            $result .= html_writer::link($submiturl, get_string('submit', 'plagiarism_plagiarismsearch'));
        }

        return $result;
    }

    /**
     * hook to add plagiarism specific settings to a module settings page
     * @param object $mform  - Moodle form
     * @param object $context - current context
     */
    public function get_form_elements_module($mform, $context, $modulename = "") {
        if ($modulename == 'mod_assign') {
            $cmid = optional_param('update', 0, PARAM_INT);

            $notoryes = array(0 => get_string('no'), 1 => get_string('yes'));

            $mform->addElement('header', 'plagiarismsearchdesc', get_string('plagiarismsearch', 'plagiarism_plagiarismsearch'));

            $mform->addElement('select', 'plagiarismsearch_use', get_string('use', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_use', plagiarismsearch_config::get_config_or_settings($cmid, 'use'));

            $mform->addElement('select', 'plagiarismsearch_filter_chars', get_string('filter_chars', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_filter_chars', plagiarismsearch_config::get_config_or_settings($cmid, 'filter_chars'));

            $mform->addElement('select', 'plagiarismsearch_filter_references', get_string('filter_references', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_filter_references', plagiarismsearch_config::get_config_or_settings($cmid, 'filter_references'));

            $mform->addElement('select', 'plagiarismsearch_filter_quotes', get_string('filter_quotes', 'plagiarism_plagiarismsearch'), $notoryes);
            $mform->setDefault('plagiarismsearch_filter_quotes', plagiarismsearch_config::get_config_or_settings($cmid, 'filter_quotes'));

            // $mform->addElement('select', 'plagiarismsearch_autostart', get_string('autostart', 'plagiarism_plagiarismsearch'), $notoryes);
            // $mform->setDefault('plagiarismsearch_autostart', plagiarismsearch_config::get_config_or_settings($cmid,'autostart'));
        }
    }

    /* hook to save plagiarism specific settings on a module settings page
     * @param object $data - data from an mform submission.
     */

    public function save_form_elements($data) {
        $cmid = $data->coursemodule;

        $fields = plagiarismsearch_config::fields();
        foreach ($fields as $name => $field) {
            if (isset($data->{$field})) {
                $value = $data->{$field};
                if ($config = plagiarismsearch_config::get_one(array('cmid' => $cmid, 'name' => $name))) {
                    plagiarismsearch_config::update(array('value' => $value), $config->id);
                } else {
                    plagiarismsearch_config::insert(array('cmid' => $cmid, 'name' => $name, 'value' => $value));
                }
            }
        }
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

}
