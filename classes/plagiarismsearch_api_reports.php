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
class plagiarismsearch_api_reports extends plagiarismsearch_api {

    public function action_create($post = array(), $files = array()) {
        $url = $this->apiurl . '/reports/create';

        $default = array(
            'fields' => array('id', 'status', 'plagiat', 'file'),
            'remote_id' => $this->generate_remote_id(),
            'add_files_api' => $this->get_config('add_to_storage', 1),
            'filter_chars' => $this->get_config('filter_chars', 0),
            'filter_references' => $this->get_config('filter_references', 0),
            'filter_quotes' => $this->get_config('filter_quotes', 0),
            'callback_url' => new moodle_url('/plagiarism/plagiarismsearch/callback.php'),
            'moodle' => plagiarismsearch_config::get_release(),
        );
        $sources = $this->get_config('sources_type', plagiarismsearch_reports::SUBMIT_WEB);

        if (plagiarismsearch_reports::is_submit_web($sources)) {
            $default['search_web'] = 1;
        }
        if (plagiarismsearch_reports::is_submit_storage($sources)) {
            $default['search_files_api'] = 1;

            // Not search on user course documents
            $default['search_files_api_user_group'] = array($this->userid, $this->cmid);
        }

        return $this->post($url, array_merge($default, $post), $files);
    }

    public function action_create_file($filename, $post = array()) {
        $post['title'] = basename($filename);
        $files = array('file' => realpath($filename));

        return $this->action_create($post, $files);
    }

    /**
     * @param \stored_file $file
     * @param array $post
     * @return stdObject Json response
     */
    public function action_send_file($file, $post = array()) {
        /* @var $file \stored_file */

        $this->set_file($file);

        if ($tmpfile = $this->tmp_file($file->get_filename(), $file->get_content())) {

            $result = $this->action_create_file($tmpfile, $post);

            unlink($tmpfile);

            return $result;
        }
    }

    /**
     * @param string $text
     * @param array $post
     * @return stdObject Json response
     */
    public function action_send_text($text, $post = array()) {
        /* @var $text \stored_file */

        $this->set_text($text);

        $post['text'] = $text;

        return $this->action_create($post);
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

    protected function generate_remote_id() {
        $result = array(
            // Cource id
            'c:' . $this->cmid,
            // User id
            'u:' . $this->userid,
        );

        if ($file = $this->get_file()) {
            $result[] = 'f:' . $file->get_id();
            $result[] = 'h:' . $file->get_pathnamehash();
        } else if ($this->text) {
            $result[] = 'h:' . plagiarismsearch_core::get_text_hash($this->text);
        }

        return implode(',', $result);
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

}
