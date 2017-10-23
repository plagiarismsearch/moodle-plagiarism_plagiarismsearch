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
     * @param \stored_file $file
     * @param int $cmid
     * @param array $params
     * @return string
     */
    public static function send_file($file, $cmid, $params = array()) {
        $values = array(
            'cmid' => $cmid,
            /**/
            'senderid' => static::get_sender_id(),
            'userid' => $file->get_userid(),
            'fileid' => $file->get_id(),
            'filename' => $file->get_filename(),
            'filehash' => $file->get_pathnamehash(),
        );

        $api = new plagiarismsearch_api_reports($values);
        $page = $api->action_send_file($file, $params);

        $msg = '';
        if ($page) {
            if ($page->status and ! empty($page->data)) {
                $values['rid'] = $page->data->id;
                $values['status'] = $page->data->status;
                $values['plagiarism'] = $page->data->plagiat;
                $values['url'] = (string) $page->data->file;
                $msg = get_string('submit_ok', 'plagiarism_plagiarismsearch', $file->get_filename());
            } else {
                $values['status'] = plagiarismsearch_reports::STATUS_ERROR;
                $values['log'] = (!empty($page->message) ? $page->message : '');

                $msg = get_string('submit_error', 'plagiarism_plagiarismsearch', $file->get_filename()) .
                        (!empty($page->message) ? '. ' . $page->message : '');
            }
        } else {
            $values['status'] = plagiarismsearch_reports::STATUS_SERVER_ERROR;
            $values['log'] = get_string('server_connection_error', 'plagiarism_plagiarismsearch');
            $msg = get_string('server_connection_error', 'plagiarism_plagiarismsearch') . ' ' . $api->apierror;
        }

        // Log submit result
        plagiarismsearch_reports::add($values);

        return $msg;
    }

    /**
     * @param string $text
     * @param int $cmid
     * @param int $userid
     * @param array $params
     * @return string
     */
    public static function send_text($text, $cmid, $userid, $params = array()) {
        $values = array(
            'cmid' => $cmid,
            'userid' => $userid,
            'senderid' => static::get_sender_id(),
            'text' => $text,
            'filehash' => static::get_text_hash($text),
        );

        $api = new plagiarismsearch_api_reports($values);
        $page = $api->action_send_text($text, $params);

        $msg = '';
        if ($page) {
            if ($page->status and ! empty($page->data)) {
                $values['rid'] = $page->data->id;
                $values['status'] = $page->data->status;
                $values['plagiarism'] = $page->data->plagiat;
                $values['url'] = (string) $page->data->file;
                $msg = get_string('submit_onlinetext_ok', 'plagiarism_plagiarismsearch');
            } else {
                $values['status'] = plagiarismsearch_reports::STATUS_ERROR;
                $values['log'] = (!empty($page->message) ? $page->message : '');

                $msg = get_string('submit_onlinetext_error', 'plagiarism_plagiarismsearch') .
                        (!empty($page->message) ? '. ' . $page->message : '');
            }
        } else {
            $values['status'] = plagiarismsearch_reports::STATUS_SERVER_ERROR;
            $values['log'] = get_string('server_connection_error', 'plagiarism_plagiarismsearch');
            $msg = get_string('server_connection_error', 'plagiarism_plagiarismsearch') . ' ' . $api->apierror;
        }

        // Log submit result
        plagiarismsearch_reports::add($values);

        return $msg;
    }

    /**
     * @param array $ids $key => primary id, $value => remote report id
     * @return string Result message
     */
    public static function check_status($ids) {

        $api = new plagiarismsearch_api_reports();
        $page = $api->action_status($ids);

        $msg = '';
        if ($page) {
            if ($page->status and ! empty($page->data)) {

                $msg = get_string('status_ok', 'plagiarism_plagiarismsearch');

                foreach ($page->data as $row) {
                    $values['status'] = $row->status;
                    $values['plagiarism'] = $row->plagiat;
                    $values['url'] = (string) $row->file;

                    $msg .= "\n #" . $row->id . ' is ' . plagiarismsearch_reports::$statuses[$row->status];

                    if ($id = array_search($row->id, $ids)) {
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

                $msg = get_string('status_error', 'plagiarism_plagiarismsearch') .
                        (!empty($page->message) ? '. ' . $page->message : '');
            }
        } else {
            $values['status'] = plagiarismsearch_reports::STATUS_SERVER_ERROR;
            $rids = array_keys($ids);
            foreach ($rids as $id) {
                plagiarismsearch_reports::update($values, $id);
            }

            $msg = get_string('server_connection_error', 'plagiarism_plagiarismsearch') . ' ' . $api->apierror;
        }

        return $msg;
    }

    /**
     * Safe back redirect url
     *
     * @global type $CFG
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
