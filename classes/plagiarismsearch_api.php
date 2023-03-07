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
class plagiarismsearch_api extends plagiarismsearch_base {

    public $apiurl = 'https://plagiarismsearch.com/api/v3';
    public $apiuser;
    public $apikey;
    public $apidebug;
    /**/
    public $apisuccess;
    public $apidata;
    public $apierror;
    public $apiinfo;
    /**/
    public $cmid;
    public $userid;

    /**
     * @var  \stored_file
     */
    protected $file;

    /**
     * @var string
     */
    protected $text;

    public function __construct($config = array()) {
        $this->apiurl = plagiarismsearch_config::get_settings('api_url');
        $this->apiuser = plagiarismsearch_config::get_settings('api_user');
        $this->apikey = plagiarismsearch_config::get_settings('api_key');
        $this->apidebug = plagiarismsearch_config::get_settings('api_debug');

        parent::__construct($config);
    }

    public function post($url, $post = array(), $files = array()) {
        $curl = curl_init($url);

        $postfields = $this->build_post_fields($post, $files);
        if ($postfields) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
        }

        // HTTP basic authentication.
        curl_setopt($curl, CURLOPT_USERPWD, $this->apiuser . ':' . $this->apikey);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        if ($this->apidebug) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 7);

        $this->apidata = curl_exec($curl);
        $this->apiinfo = curl_getinfo($curl);
        $this->apierror = curl_error($curl);
        $this->apisuccess = $this->apiinfo >= 200 && $this->apiinfo < 300 && !$this->apierror;

        curl_close($curl);

        if ($this->apidata) {
            return $this->unpack($this->apidata);
        }
    }

    public function get_response() {
        if ($this->apidata) {
            return $this->unpack($this->apidata);
        }
    }

    private function unpack($data) {
        return json_decode($data, false);
    }

    private function build_post_fields($post, $files = array()) {
        $postfields = $this->build_post_files($post, $files);
        if (!$postfields) {
            $postfields = $this->build_post_to_string($post);
        }
        return $postfields;
    }

    private function build_post_to_string($post) {
        if (!empty($post)) {
            if (is_array($post)) {
                return http_build_query($post, '', '&');
            } else {
                return $post;
            }
        }
        return '';
    }

    private function build_post_files($post, $files) {
        $result = array();
        if (!empty($post) && is_array($post)) {
            foreach ($post as $key => $value) {
                if (is_array($value)) {
                    $result[$key] = http_build_query($value, '', '&');
                } else {
                    $result[$key] = $value;
                }
            }
        }
        if (!empty($files) && is_array($files)) {
            $result = array_merge($result, $this->build_files($files));
        }

        return $result;
    }

    private function build_files($files) {
        $result = array();

        if (empty($files)) {
            return $result;
        }

        foreach ($files as $key => $value) {
            if (is_string($value)) {
                $result[$key] = new \CURLFile(realpath($value));
            } else if (isset($value['tmp_name'])) {
                $file = $value['tmp_name'];
                $name = isset($value['name']) ? $value['name'] : null;
                $type = isset($value['type']) ? $value['type'] : null;

                $result[$key] = new \CURLFile($file, $type, $name);
            }
        }
        return $result;
    }

    public function ping() {
        $url = $this->apiurl . '/ping';

        return $this->post($url);
    }

    /**
     * @param \stored_file $file
     * @return $this
     */
    public function set_file($file) {
        $this->file = $file;
        return $this;
    }

    /**
     * @return \stored_file
     */
    public function get_file() {
        return $this->file;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function set_text($text) {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function get_text() {
        return $this->text;
    }

}
