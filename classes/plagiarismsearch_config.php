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
    const FIELD_ENABLED = 'enabled';
    const FIELD_USE = 'use';
    const FIELD_API_URL = 'api_url';
    const FIELD_API_USER = 'api_user';
    const FIELD_API_KEY = 'api_key';
    const FIELD_API_DEBUG = 'api_debug';
    const FIELD_AUTO_CHECK = 'auto_check';
    const FIELD_MANUAL_CHECK = 'manual_check';
    const FIELD_ADD_TO_STORAGE = 'add_to_storage';
    const FIELD_SOURCES_TYPE = 'sources_type';
    const FIELD_DETECT_AI = 'detect_ai';
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
    const FIELD_PARSE_TEXT_URLS = 'parse_text_url';
    const FIELD_VALID_PARSED_TEXT_URLS = 'valid_parsed_text_url';
    /**/
    const SUBMIT_WEB = 1;
    const SUBMIT_STORAGE = 2;
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
    const LANGUAGE_UA = 'ua';
    const LANGUAGE_PL = 'pl';
    const LANGUAGE_RU = 'ru';
    /**/
    const FILTER_PLAGIARISM_NO = 0;
    const FILTER_PLAGIARISM_USER_COURSE = 1;
    const FILTER_PLAGIARISM_USER = 2;
    const FILTER_PLAGIARISM_COURSE = 3;

    protected static $config = [];
    protected static $settings = [];
    protected static $fields = [
            self::FIELD_USE,
            self::FIELD_API_URL, self::FIELD_API_USER, self::FIELD_API_KEY, self::FIELD_API_DEBUG,
            self::FIELD_AUTO_CHECK, self::FIELD_MANUAL_CHECK,
            self::FIELD_ADD_TO_STORAGE,
            self::FIELD_SOURCES_TYPE,
            self::FIELD_DETECT_AI,
            self::FIELD_REPORT_LANGUAGE, self::FIELD_REPORT_TYPE,
            self::FIELD_FILTER_CHARS, self::FIELD_FILTER_PLAGIARISM, self::FIELD_FILTER_QUOTES, self::FIELD_FILTER_REFERENCES,
            self::FIELD_STUDENT_DISCLOSURE, self::FIELD_STUDENT_RESUBMIT, self::FIELD_STUDENT_RESUBMIT_NUMBERS,
            self::FIELD_STUDENT_SHOW_PERCENTAGE, self::FIELD_STUDENT_SHOW_REPORTS, self::FIELD_STUDENT_SUBMIT,
            self::FIELD_PARSE_TEXT_URLS, self::FIELD_VALID_PARSED_TEXT_URLS,
    ];

    public static function table_name() {
        // Moodle error: 'name is too long. Limit is 28 chars.'.
        return 'plagiarism_ps_config';
    }

    public static function fields() {
        $result = [self::FIELD_ENABLED => self::FIELD_ENABLED];
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

        return ($value === null) ? $default : $value;
    }

    public static function get_config($cmid, $name, $default = null) {
        if (!isset(static::$config[$cmid])) {
            static::load_config($cmid);
        }

        return isset(static::$config[$cmid][$name]) ? static::$config[$cmid][$name] : $default;
    }

    private static function load_config($cmid) {
        static::$config = [];

        $config = static::get_all(['cmid' => (int) $cmid]);
        if ($config) {
            foreach ($config as $row) {
                static::$config[$row->cmid][$row->name] = $row->value;
            }
        }

        return static::$config;
    }

    public static function set_config($cmid, $name, $value) {
        $config = static::get_one(['cmid' => $cmid, 'name' => $name]);
        if ($config) {
            return static::update(['value' => $value], $config->id);
        }
        return static::insert(['cmid' => $cmid, 'name' => $name, 'value' => $value]);
    }

    /**
     * This function should be used to initialize and get settings.
     *
     * @param string $key
     *
     * @return array|bool
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    public static function get_settings($key) {
        if (empty(self::$settings)) {
            self::load_settings();
        }
        return self::get_settings_item($key);
    }

    public static function set_settings($key, $value) {
        return set_config($key, $value, 'plagiarism_plagiarismsearch');
    }

    public static function load_settings() {
        $settings = (array) get_config('plagiarism_plagiarismsearch');
        self::$settings = $settings;
        self::$settings['is_loaded'] = true;
        return $settings;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    private static function get_settings_item($key) {
        $index = $key;
        if ($key !== self::FIELD_ENABLED) {
            $index = self::CONFIG_PREFIX . $key;
        }

        return isset(self::$settings[$index]) ? self::$settings[$index] : null;
    }

    public static function is_plugin_enabled() {
        return (bool) self::get_settings(self::FIELD_ENABLED);
    }

    public static function is_enabled($cmid = null) {
        return self::is_plugin_enabled() && self::get_config($cmid, self::FIELD_ENABLED, self::get_config($cmid, self::FIELD_USE));
    }

    public static function is_enabled_auto($cmid = null) {
        return self::is_plugin_enabled() && self::get_config_or_settings($cmid, self::FIELD_AUTO_CHECK);
    }

    public static function is_submit_web($cmid = null) {
        $value = self::get_config_or_settings($cmid, self::FIELD_SOURCES_TYPE, self::SUBMIT_WEB);
        return $value & self::SUBMIT_WEB;
    }

    public static function is_submit_storage($cmid = null) {
        $value = self::get_config_or_settings($cmid, self::FIELD_SOURCES_TYPE);
        return $value & static::SUBMIT_STORAGE;
    }

    public static function is_submit_ai($cmid = null) {
        return self::get_config_or_settings($cmid, self::FIELD_DETECT_AI);
    }

    public static function get_valid_parsed_text_url_as_array($cmid = null) {
        $enabled = self::get_config_or_settings($cmid, self::FIELD_PARSE_TEXT_URLS);
        if (empty($enabled)) {
            return [];
        }

        $urls = self::get_config_or_settings($cmid, self::FIELD_VALID_PARSED_TEXT_URLS);
        if (empty($urls)) {
            return [];
        }
        return explode("\n", trim($urls));
    }

    public static function get_release() {
        global $CFG;

        if (isset($CFG->release)) {
            return $CFG->release;
        }
    }

    public static function get_plugin_release() {
        global $CFG;
        global $plugin;
        require_once($CFG->dirroot . '/plagiarism/plagiarismsearch/version.php');

        if (isset($plugin->release)) {
            return $plugin->release;
        }
    }

    public static function get_submit_types() {
        return [
                static::SUBMIT_WEB_STORAGE => static::translate('sources_doc_web_storage'),
                static::SUBMIT_WEB => static::translate('sources_doc_web'),
                static::SUBMIT_STORAGE => static::translate('sources_doc_storage'),
        ];
    }

    public static function get_report_types() {
        return [
                static::REPORT_NO => static::translate('report_show_no'),
                static::REPORT_PDF => static::translate('report_show_pdf'),
                static::REPORT_HTML => static::translate('report_show_html'),
                static::REPORT_PDF_HTML => static::translate('report_show_pdf_html'),
        ];
    }

    public static function get_report_languages() {
        return [
                static::LANGUAGE_DEFAULT => static::translate('report_language_default'),
                static::LANGUAGE_EN => static::translate('report_language_en'),
                static::LANGUAGE_ES => static::translate('report_language_es'),
                static::LANGUAGE_UA => static::translate('report_language_ua'),
                static::LANGUAGE_PL => static::translate('report_language_pl'),
                static::LANGUAGE_RU => static::translate('report_language_ru'),
        ];
    }

    public static function get_plagiarism_filters() {
        return [
                static::FILTER_PLAGIARISM_NO => static::translate('filter_plagiarism_no'),
                static::FILTER_PLAGIARISM_USER_COURSE => static::translate('filter_plagiarism_user_course'),
                static::FILTER_PLAGIARISM_USER => static::translate('filter_plagiarism_user'),
                static::FILTER_PLAGIARISM_COURSE => static::translate('filter_plagiarism_course'),
        ];
    }

    public static function get_default_valid_parsed_text_urls() {
        return "docs.google.com/\ndrive.google.com/";
    }

}
