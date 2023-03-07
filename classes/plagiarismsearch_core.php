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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.ss
/**
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarismsearch_core extends plagiarismsearch_base {

    /**
     * Get sender ID
     *
     * @global type $USER
     * @return int
     */
    public static function get_sender_id() {
        global $USER;
        return (!empty($USER)) ? $USER->id : 0; // 0 - system
    }

    /**
     * @param      $cmid
     * @param null $userid
     *
     * @return bool|\stdClass
     */
    public static function get_user_submission_by_cmid($cmid, $userid = null) {
        global $USER;

        try {
            $modulecontext = context_module::instance($cmid);
            $assign = new assign($modulecontext, false, false);
        } catch (\Exception $ex) {
            return false;
        }

        return ($assign->get_user_submission(($userid !== null) ? $userid : $USER->id, false));
    }

    /**
     * Send file to scanning
     *
     * @param \stored_file $file
     * @param int $cmid
     * @param array $params
     * @return string
     */
    public static function send_file($file, $cmid, $params = array()) {
        $filename = $file->get_filename();
        $apivalues = array(
            'cmid' => $cmid,
            /**/
            'senderid' => static::get_sender_id(),
            'userid' => $file->get_userid(),
            'fileid' => $file->get_id(),
            'filename' => $filename,
            'filehash' => $file->get_pathnamehash(),
        );

        $api = new plagiarismsearch_api_reports($apivalues);
        $page = $api->action_send_file($file, $params);

        $msg = '';
        if ($page) {
            if ($page->status and ! empty($page->data)) {
                $values = static::fill_report_values($page->data);
                $msg = get_string('submit_ok', 'plagiarism_plagiarismsearch', $filename);
            } else {

                $apierror = get_string('api_error', 'plagiarism_plagiarismsearch');
                $errormessage = (!empty($page->message) ? $page->message : '');
                $values['status'] = plagiarismsearch_reports::STATUS_ERROR;
                $values['log'] = $apierror . ($errormessage ? ': ' . $errormessage : '');

                $msg = get_string('submit_error', 'plagiarism_plagiarismsearch', $filename) .
                    $errormessage;
            }
        } else {
            $values['status'] = plagiarismsearch_reports::STATUS_SERVER_ERROR;
            $values['log'] = static::translate('server_connection_error');
            $msg = static::translate('server_connection_error') . ' ' . $api->apierror;
        }

        // Log submit result
        plagiarismsearch_reports::add(array_merge($apivalues, $values));

        return $msg;
    }

    /**
     * Send text to scanning
     *
     * @param string $text
     * @param int $cmid
     * @param int $userid
     * @param array $params
     * @return string
     */
    public static function send_text($text, $cmid, $userid, $params = array()) {
        $apivalues = array(
            'cmid' => $cmid,
            'userid' => $userid,
            'senderid' => static::get_sender_id(),
            // Unique text hash
            'filehash' => static::get_text_hash($text),
            'text' => $text,
        );

        $api = new plagiarismsearch_api_reports($apivalues);
        $page = $api->action_send_text($text, $params);

        $msg = '';
        if ($page) {
            if ($page->status and ! empty($page->data)) {
                $values = static::fill_report_values($page->data);
                $msg = static::translate('submit_onlinetext_ok');
            } else {
                $values['status'] = plagiarismsearch_reports::STATUS_ERROR;
                $values['log'] = (!empty($page->message) ? $page->message : '');

                $msg = static::translate('submit_onlinetext_error') .
                        (!empty($page->message) ? '. ' . $page->message : '');
            }
        } else {
            $values['status'] = plagiarismsearch_reports::STATUS_SERVER_ERROR;
            $values['log'] = static::translate('server_connection_error');
            $msg = static::translate('server_connection_error') . ' ' . $api->apierror;
        }

        // Log submit result
        plagiarismsearch_reports::add(array_merge($apivalues, $values));

        return $msg;
    }

    /**
     * Check report status
     *
     * @param array $ids $key => primary id, $value => remote report id
     * @return string Result message
     */
    public static function check_status($ids) {

        $api = new plagiarismsearch_api_reports();
        $page = $api->action_status($ids);

        $msg = '';
        if (!$page) {
            $values = array(
                'status' => plagiarismsearch_reports::STATUS_SERVER_ERROR,
            );
            $rids = array_keys($ids);
            foreach ($rids as $id) {
                plagiarismsearch_reports::update($values, $id);
            }

            $msg = static::translate('server_connection_error') . ' ' . $api->apierror;

            return $msg;
        }

        if ($page->status and ! empty($page->data)) {

            $msg = static::translate('status_ok');

            foreach ($page->data as $report) {
                $values = static::fill_report_values($report);

                $statuslabel = '';
                if (isset(plagiarismsearch_reports::$statuses[$report->status])) {
                    $statuslabel = plagiarismsearch_reports::$statuses[$report->status];
                } else {
                    $statuslabel = static::translate('unknown_error');
                }

                $msg .= "\n #" . $report->id . ' ' . static::translate('is_in') . ' '
                        . $statuslabel . ' ' . static::translate('status');

                if ($id = array_search($report->id, $ids)) {
                    plagiarismsearch_reports::update($values, $id);
                }
            }
        } else {
            $values['status'] = plagiarismsearch_reports::STATUS_ERROR;
            $values['log'] = (!empty($page->message) ? $page->message : '');

            $rids = array_keys($ids);
            foreach ($rids as $id) {
                plagiarismsearch_reports::update($values, $id);
            }

            $msg = static::translate('status_error') .
                    (!empty($page->message) ? '. ' . $page->message : '');
        }

        return $msg;
    }

    /**
     * Fill report values
     *
     * @param mixed $report
     * @return array
     */
    protected static function fill_report_values($report) {
        $values = array();
        if (property_exists($report, 'id')) {
            $values['rid'] = $report->id;
        }
        if (property_exists($report, 'status')) {
            $values['status'] = $report->status;
        }
        if (property_exists($report, 'plagiat')) {
            $values['plagiarism'] = $report->plagiat;
        }
        if (property_exists($report, 'file')) {
            $values['url'] = (string) $report->file;
        }
        if (property_exists($report, 'auth_key')) {
            $values['rkey'] = $report->auth_key;
        }
        if (property_exists($report, 'file_id')) {
            $values['rfileid'] = $report->file_id;
        }
        if (property_exists($report, 'server_url')) {
            $values['rserverurl'] = $report->server_url;
        }

        return $values;
    }

    /**
     * Safe back redirect url
     *
     * @global stdClass $CFG
     * @param stdClass $coursemodule
     * @param context_module $context
     * @return string
     */
    public static function redirect_url($coursemodule, $context) {
        global $CFG;
        $isstudent = plagiarism_plugin_plagiarismsearch::is_student($context->id);

        if ($coursemodule->modname == 'assignment') {
            $redirect = new moodle_url('/mod/assignment/submissions.php', array('id' => $coursemodule->id));
        } else if ($coursemodule->modname == 'assign') {
            $redirectparams = array('id' => $coursemodule->id);
            if (!$isstudent) {
                $redirectparams['action'] = 'grading';
            }

            $redirect = new moodle_url('/mod/assign/view.php', $redirectparams);
        } else {
            $redirect = $CFG->wwwroot;
        }
        return $redirect;
    }

    public static function get_text_hash($text) {
        return md5(strip_tags($text));
    }

}
