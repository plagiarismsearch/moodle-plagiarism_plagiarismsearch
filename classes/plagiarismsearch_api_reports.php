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

    public function action_create($post = [], $files = []) {
        $url = $this->apiurl . '/reports/create';

        $default = [
                'fields' => ['id', 'status', 'plagiat', 'file', 'ai_probability', 'ai_average_probability'],
                'remote_id' => $this->generate_remote_id(),
                'is_add_storage' => $this->get_config(plagiarismsearch_config::FIELD_ADD_TO_STORAGE, 1),
                'is_search_filter_chars' => $this->get_config(plagiarismsearch_config::FIELD_FILTER_CHARS, 0),
                'is_search_filter_references' => $this->get_config(plagiarismsearch_config::FIELD_FILTER_REFERENCES, 0),
                'is_search_filter_quotes' => $this->get_config(plagiarismsearch_config::FIELD_FILTER_QUOTES, 0),
                'callback_url' => new moodle_url('/plagiarism/plagiarismsearch/callback.php'),
                'moodle' => plagiarismsearch_config::get_plugin_release(),
                'moodle_release' => plagiarismsearch_config::get_release(),
                'storage_course_id' => $this->cmid,
                'storage_user_id' => $this->userid,
        ];

        $file = $this->get_file();
        if ($file) {
            $default['storage_file_id'] = $file->get_id();
            $default['storage_author'] = $file->get_author();
        }

        if (plagiarismsearch_config::is_submit_web($this->cmid)) {
            $default['is_search_web'] = 1;
        }
        if (plagiarismsearch_config::is_submit_ai($this->cmid)) {
            $default['is_search_ai'] = 1;
        }
        if (plagiarismsearch_config::is_submit_storage($this->cmid)) {
            $default['is_search_storage'] = 1;

            $filterplagiarism = $this->get_config(plagiarismsearch_config::FIELD_FILTER_PLAGIARISM, 0);
            if ($filterplagiarism == plagiarismsearch_config::FILTER_PLAGIARISM_USER_COURSE) {
                $default['search_storage_user_group'] = [$this->userid, $this->cmid];
            } else if ($filterplagiarism == plagiarismsearch_config::FILTER_PLAGIARISM_USER) {
                $default['search_storage_filter[user_id]'] = $this->userid;
            } else if ($filterplagiarism == plagiarismsearch_config::FILTER_PLAGIARISM_COURSE) {
                $default['search_storage_filter[group_id]'] = $this->cmid;
            } else if ($filterplagiarism !== plagiarismsearch_config::FILTER_PLAGIARISM_NO && $filterplagiarism !== '0') {
                // Don't search on user course documents.
                $default['search_storage_user_group'] = [$this->userid, $this->cmid];
            }
        }

        $parsedurls = plagiarismsearch_config::get_valid_parsed_text_url_as_array($this->cmid);
        if ($parsedurls) {
            $default['parsed_text_url'] = $parsedurls;
        }

        return $this->post($url, array_merge($default, $post), $files);
    }

    public function action_create_file($filename, $post = []) {
        $post['title'] = basename($filename);
        $files = ['file' => realpath($filename)];

        return $this->action_create($post, $files);
    }

    /**
     * @param \stored_file $file
     * @param array $post
     * @return stdClass Json response
     */
    public function action_send_file($file, $post = []) {
        $this->set_file($file);

        $tmpfile = $this->tmp_file($file->get_filename(), $file->get_content());
        if ($tmpfile) {

            $result = $this->action_create_file($tmpfile, $post);

            unlink($tmpfile);

            return $result;
        }
    }

    /**
     * @param string $text
     * @param array $post
     * @return \stdClass Json response
     */
    public function action_send_text($text, $post = []) {
        $this->set_text($text);

        $post['text'] = $text;

        return $this->action_create($post);
    }

    public function action_status($ids = []) {
        $url = $this->apiurl . '/reports';

        $post['fields'] = ['id', 'status', 'plagiat', 'file', 'ai_probability', 'ai_average_probability'];
        $post['ids'] = $ids;

        return $this->post($url, $post);
    }

    protected function get_config($name, $default = null) {
        return plagiarismsearch_config::get_config_or_settings($this->cmid, $name, $default);
    }

    protected function generate_remote_id() {
        $result = [
            // Context id.
                'c:' . $this->cmid,
            // User id.
                'u:' . $this->userid,
        ];

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
        $tmpdir = $CFG->dataroot . '/temp';
        if ($tmpdir && is_writable($tmpdir)) {
            return $tmpdir;
        } else {
            return sys_get_temp_dir();
        }
    }

    protected function tmp_file($filename, $content) {
        $tmpdir = $this->tmp_dir();
        if (!$tmpdir || !$filename) {
            return null;
        }

        $fullname = $tmpdir . DIRECTORY_SEPARATOR . $filename;
        $f = fopen($fullname, 'w');
        if (!$f) {
            return null;
        }
        fwrite($f, $content);
        fclose($f);

        return $fullname;
    }

}
