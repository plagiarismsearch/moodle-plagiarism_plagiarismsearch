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
class plagiarismsearch_config extends plagiarismsearch_table {

    const CONFIG_PREFIX = 'plagiarismsearch_';
    /**/
    const FIELD_USE = 'use';
    const FIELD_API_URL = 'api_url';
    const FIELD_API_USER = 'api_user';
    const FIELD_API_KEY = 'api_key';
    const FIELD_API_DEBUG = 'api_debug';
    const FIELD_AUTO_CHECK = 'auto_check';
    const FIELD_MANUAL_CHECK = 'manual_check';
    const FIELD_ADD_TO_STORAGE = 'add_to_storage';
    const FIELD_SOURCES_TYPE = 'sources_type';
    const FIELD_REPORT_LANGUAGE = 'report_language';
    const FIELD_REPORT_TYPE = 'report_type';
    const FIELD_FILTER_CHARS = 'filter_chars';
    const FIELD_FILTER_REFERENCES = 'filter_references';
    const FIELD_FILTER_QUOTES = 'filter_quotes';
    const FIELD_FILTER_PLAGIARISM = 'filter_plagiarism';
    const FIELD_STUDENT_SHOW_REPORTS = 'student_show_reports';
    const FIELD_STUDENT_SHOW_PERCENTAGE = 'student_show_percentage';
    const FIELD_STUDENT_SUBMIT = 'student_submit';
    const FIELD_STUDENT_RESUBMIT = 'student_resubmit';
    const FIELD_STUDENT_RESUBMIT_NUMBERS = 'student_resubmit_numbers';
    const FIELD_STUDENT_DISCLOSURE = 'student_disclosure';
    /**/
    const SUBMIT_WEB = 1;
    const SUBMIT_STORAGE = 2;
    // self::SUBMIT_WEB | self::SUBMIT_STORAGE;
    const SUBMIT_WEB_STORAGE = 3;
    /**/
    const REPORT_NO = 0;
    const REPORT_PDF = 1;
    const REPORT_HTML = 2;
    const REPORT_PDF_HTML = 3;
    /**/
    const LANGUAGE_DEFAULT = '';
    const LANGUAGE_EN = 'en';
    const LANGUAGE_ES = 'es';
    const LANGUAGE_PL = 'pl';
    const LANGUAGE_RU = 'ru';
    /**/
    const FILTER_PLAGIARISM_NO = 0;
    const FILTER_PLAGIARISM_USER_COURSE = 1;
    const FILTER_PLAGIARISM_USER = 2;
    const FILTER_PLAGIARISM_COURSE = 3;

    protected static $config = array();
    protected static $settings = array();
    protected static $fields = array(
        self::FIELD_USE,
        self::FIELD_API_URL, self::FIELD_API_USER, self::FIELD_API_KEY, self::FIELD_API_DEBUG,
        self::FIELD_AUTO_CHECK, self::FIELD_MANUAL_CHECK,
        self::FIELD_ADD_TO_STORAGE,
        self::FIELD_SOURCES_TYPE,
        self::FIELD_REPORT_LANGUAGE, self::FIELD_REPORT_TYPE,
        self::FIELD_FILTER_CHARS, self::FIELD_FILTER_PLAGIARISM, self::FIELD_FILTER_QUOTES, self::FIELD_FILTER_REFERENCES,
        self::FIELD_STUDENT_DISCLOSURE, self::FIELD_STUDENT_RESUBMIT, self::FIELD_STUDENT_RESUBMIT_NUMBERS,
        self::FIELD_STUDENT_SHOW_PERCENTAGE, self::FIELD_STUDENT_SHOW_REPORTS, self::FIELD_STUDENT_SUBMIT,
    );

    public static function table_name() {
        // Moodle error: 'name is too long. Limit is 28 chars.'
        return 'plagiarism_plagiarismsearchc';
    }

    public static function fields() {
        $result = array();
        foreach (static::$fields as $field) {
            $result[$field] = static::CONFIG_PREFIX . $field;
        }
        return $result;
    }

    public static function get_config_or_settings($cmid, $name, $default = null) {
        $value = static::get_config($cmid, $name, null);
        if ($value === null) {
            $value = static::get_settings($name);
        }

        return ($value == null) ? $default : $value;
    }

    public static function get_config($cmid, $name, $default = false) {
        if (isset(static::$config[$cmid][$name])) {
            return static::$config[$cmid][$name];
        }

        global $DB;

        $condition = array(
            'cmid' => $cmid,
            'name' => $name,
        );

        $config = $DB->get_records(static::table_name(), $condition, '', 'cmid,name,value');
        if ($config) {
            foreach ($config as $row) {
                static::$config[$row->cmid][$row->name] = $row->value;
            }
        }

        if (isset(static::$config[$cmid][$name])) {
            return static::$config[$cmid][$name];
        } else {
            return $default;
        }
    }

    /**
     * This function should be used to initialize settings and check if plagiarism is enabled.
     *
     * @param null $key
     *
     * @return array|bool
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    public static function get_settings($key = null) {
        if (!empty(static::$settings)) {
            return self::get_settings_item($key);
        }

        static::$settings = (array) get_config('plagiarism');

        // Check if enabled.
        $usefield = static::CONFIG_PREFIX . static::FIELD_USE;
        if (isset(static::$settings[$usefield]) && static::$settings[$usefield]) {
            return self::get_settings_item($key);
        } else {
            return false;
        }
    }

    /**
     * @param $settings
     * @param null $key
     *
     * @return null
     */
    private static function get_settings_item($key = null) {
        if (is_null($key)) {
            return static::$settings;
        }

        $key = static::CONFIG_PREFIX . $key;

        return isset(static::$settings[$key]) ? static::$settings[$key] : null;
    }

    public static function is_enabled($cmid = null) {
        return (bool) static::get_config_or_settings($cmid, static::FIELD_USE);
    }

    public static function is_enabled_auto($cmid = null) {
        return (bool) static::get_config_or_settings($cmid, static::FIELD_USE) and static::get_config_or_settings($cmid, static::FIELD_AUTO_CHECK);
    }

    public static function is_submit_web($cmid = null) {
        $value = static::get_config_or_settings($cmid, static::FIELD_SOURCES_TYPE, static::SUBMIT_WEB);
        return $value & static::SUBMIT_WEB;
    }

    public static function is_submit_storage($cmid = null) {
        $value = static::get_config_or_settings($cmid, static::FIELD_SOURCES_TYPE);
        return $value & static::SUBMIT_STORAGE;
    }

    public static function get_release() {
        global $CFG;
        global $plugin;
        require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/version.php');

        if (isset($plugin->release)) {
            return $plugin->release;
        }
    }

}
