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
class plagiarismsearch_base {

    public function __construct($config = []) {
        $this->configure($config);
    }

    /**
     * Configure object
     *
     * @param $config
     * @return void
     */
    protected function configure($config = []) {
        if (empty($config)) {
            return;
        }
        if (!is_array($config)) {
            return;
        }
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Translate string
     *
     * @param $value
     * @param $module
     * @return lang_string|mixed|string
     * @throws coding_exception
     */
    public static function translate($value, $module = 'plagiarism_plagiarismsearch') {
        if (empty($value)) {
            return $value;
        }
        return get_string($value, $module);
    }

    /**
     * Json decode
     *
     * @param $json
     * @param $associative
     * @return mixed
     */
    public static function jsondecode($json, $associative = null) {
        $result = json_decode($json, $associative);
        $error = json_last_error();
        if ($error == JSON_ERROR_UTF8) {
            $result = json_decode(static::utf8ize($json), $associative);
        }
        return $result;
    }

    public static function utf8ize($mixed) {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = static::utf8ize($value);
            }
        } else if (is_object($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed->{$key} = static::utf8ize($value);
            }
        } else if (is_string($mixed)) {
            return utf8_encode($mixed);
        }
        return $mixed;
    }

}
