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
class plagiarismsearch_event_handler extends plagiarismsearch_base {

    /**
     * @var core\event\base
     */
    protected $event;
    protected $allowedcomponents = [
            'mod_assign',
            'assignsubmission_file',
            'assignsubmission_onlinetext',
    ];
    protected $allowedevents = [
            '\assignsubmission_file\event\submission_updated',
            '\assignsubmission_file\event\assessable_uploaded',
            '\assignsubmission_onlinetext\event\assessable_uploaded',
    ];

    /**
     * Constructor
     *
     * @param \core\event\base $event
     * @param $config
     */
    public function __construct(core\event\base $event, $config = []) {
        $this->event = $event;
        parent::__construct($config);
    }

    /**
     * Parse cmid
     *
     * @return int|mixed
     */
    public function cmid() {
        $data = $this->event->get_data();

        return empty($data['contextinstanceid']) ? $data['contextinstanceid'] : $this->event->get_context()->instanceid;
    }

    /**
     * Parse course id
     *
     * @return int|mixed
     */
    public function courceid() {
        $data = $this->event->get_data();

        if (!empty($data['courseid'])) {
            return $data['courseid'];
        }

        return $this->cmid();
    }

    /**
     * Parse user id
     * @return int|mixed
     */
    public function userid() {
        global $USER;
        $data = $this->event->get_data();

        if (!empty($data['userid'])) {
            return $data['userid'];
        } else if (!empty($USER->id)) {
            return $USER->id;
        }
            return 0;
    }

    /**
     * Get online text content
     *
     * @return mixed|null
     */
    public function get_onlinetext_content() {
        return !empty($this->event->other['content']) ? $this->event->other['content'] : null;
    }

    /**
     * Run event handler
     *
     * @return void
     */
    public function run() {
        if (!$this->is_valid()) {
            return;
        }

        if (!$this->is_upload()) {
            return;
        }

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
    }

    /**
     * Handle uploaded file
     *
     * @param $pathnamehash
     * @return void|null
     */
    protected function handle_uploaded_file($pathnamehash) {
        $file = get_file_storage()->get_file_by_hash($pathnamehash);
        if ($file->is_directory()) {
            return null;
        }

        plagiarismsearch_core::send_file($file, $this->cmid(),
                ['submit' => 'auto', 'storage_subject_id' => $this->courceid()]);
    }

    /**
     * Handle online text
     *
     * @return void
     */
    protected function handle_online_text() {
        $content = $this->get_onlinetext_content();
        if ($content) {
            plagiarismsearch_core::send_text($content, $this->cmid(), $this->userid(),
                    ['submit' => 'auto', 'storage_subject_id' => $this->courceid()]);
        }
    }

    /**
     * Check if event is valid
     *
     * @return bool
     * @throws dml_exception
     * @throws moodle_exception
     */
    protected function is_valid() {
        return $this->is_allowed_component() && plagiarismsearch_config::is_enabled_auto($this->cmid());
    }

    /**
     * Check if component is allowed
     *
     * @return bool
     */
    protected function is_allowed_component() {
        return in_array($this->event->component, $this->allowedcomponents);
    }

    /**
     * Check if event is upload
     *
     * @return bool
     */
    protected function is_upload() {
        $eventdata = $this->event->get_data();
        return in_array($eventdata['eventname'], $this->allowedevents);
    }

}
