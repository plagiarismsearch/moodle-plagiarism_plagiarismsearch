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
class plagiarismsearch_api_reports extends plagiarismsearch_api {

    public function action_create_file($filename, $post = array()) {
        $url = $this->apiurl . '/reports/create';

        $post['fields'] = array('id', 'status', 'plagiat', 'file');

        $post['title'] = basename($filename);
        $post['filter_chars'] = $this->get_config('filter_chars', 0);
        $post['filter_references'] = $this->get_config('filter_references', 0);
        $post['filter_quotes'] = $this->get_config('filter_quotes', 0);

        $files = array('file' => realpath($filename));

        return $this->post($url, $post, $files);
    }

    public function action_send_file($file, $post = array()) {
        /* @var $file \stored_file */

        if ($tmpfile = $this->tmp_file($file->get_filename(), $file->get_content())) {
            $post['remote_id'] = $this->generate_remote_id($file);

            $result = $this->action_create_file($tmpfile, $post);

            unlink($tmpfile);

            return $result;
        }
    }

    public function action_status($ids = array()) {
        $url = $this->apiurl . '/reports';

        $post['fields'] = array('id', 'status', 'plagiat', 'file');
        $post['ids'] = $ids;

        return $this->post($url, $post);
    }

    protected function get_config($name, $default = null) {
        return plagiarismsearch_config::get_config_or_settings($this->cmid, $name, $default);
    }

    protected function tmp_dir() {
        global $CFG;
        if ($tmpdir = $CFG->dataroot . '/temp' and is_writable($tmpdir)) {
            return $tmpdir;
        } else {
            return sys_get_temp_dir();
        }
    }

    protected function tmp_file($filename, $content) {
        if (
                $tmpdir = $this->tmp_dir() and
                $filename and
                $filename = $tmpdir . DIRECTORY_SEPARATOR . $filename and
                $f = fopen($filename, 'w')
        ) {
            fwrite($f, $content);
            fclose($f);

            return $filename;
        }
    }

    protected function generate_remote_id($file = null) {
        /* @var $file \stored_file */
        if ($file) {
            return 'c:' . $this->cmid . 'u:' . $file->get_userid() . 'h:' . $file->get_pathnamehash() . 'id:' . $file->get_id();
        } else {
            return 'c:' . $this->cmid . 'u:' . $file->userid . 'h:' . $this->filehash;
        }
    }

}
