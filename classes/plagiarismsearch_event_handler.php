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
class plagiarismsearch_event_handler extends plagiarismsearch_base {

    /**
     * @var core\event\base
     */
    protected $event;
    protected $allowedcomponents = array(
        'mod_assign',
        'assignsubmission_file',
        'assignsubmission_onlinetext',
    );

    public function __construct(core\event\base $event, $config = array()) {
        $this->event = $event;
        parent::__construct($config);
    }

    public function cmid() {
        return $this->event->get_context()->instanceid;
    }

    public function userid() {
        global $USER;
        $data = $this->event->get_data();

        if (!empty($data['userid'])) {
            return $data['userid'];
        } else if (!empty($USER->id)) {
            return $USER->id;
        } else {
            return 0;
        }
    }

    public function get_onlinetext_content() {
        return !empty($this->event->other['content']) ? $this->event->other['content'] : null;
    }

    public function run() {
        if (!$this->is_valid()) {
            return;
        }

        if ($this->is_upload()) {

            switch ($this->event->component) {
                case 'assignsubmission_onlinetext':
                    $this->handle_online_text();
                    break;
                case 'assignsubmission_file':
                    if (!empty($this->event->other['pathnamehashes'])) {
                        foreach ($this->event->other['pathnamehashes'] as $pathnamehash) {
                            $this->handle_uploaded_file($pathnamehash);
                        }
                    }
                    break;
            }
            // var_dump($this->event->component, $this->event->get_data(), $this->event);
            // die;
        }
    }

    protected function handle_uploaded_file($pathnamehash) {
        $file = get_file_storage()->get_file_by_hash($pathnamehash);
        if ($file->is_directory()) {
            return null;
        }

        plagiarismsearch_core::send_file($file, $this->cmid());
    }

    protected function handle_online_text() {
        if ($content = $this->get_onlinetext_content()) {
            plagiarismsearch_core::send_text($content, $this->cmid(), $this->userid());
        }
    }

    protected function is_valid() {
        return $this->is_allowed_component() and plagiarismsearch_config::is_enabled_auto($this->cmid());
    }

    protected function is_allowed_component() {
        return in_array($this->event->component, $this->allowedcomponents);
    }

    protected function is_upload() {
        $eventdata = $this->event->get_data();
        return in_array($eventdata['eventname'], array(
            '\assignsubmission_file\event\submission_updated',
            '\assignsubmission_file\event\assessable_uploaded',
            '\assignsubmission_onlinetext\event\assessable_uploaded',
        ));
    }

    protected function is_submition_draft() {
        global $CFG;

        if ($this->event->objecttable != 'assign_submission') {
            return false;
        }

        require_once($CFG->dirroot . '/mod/assign/locallib.php');

        //$submission = unplag_assign::get_user_submission_by_cmid($event->contextinstanceid);
//        if (!$submission) {
//            return true;
//        }

        return ($submission->status !== 'submitted');
    }

}
