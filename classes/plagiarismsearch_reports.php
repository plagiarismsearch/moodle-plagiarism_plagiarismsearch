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
 * Reports class for plagiarismsearch
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Reports class for plagiarismsearch
 */
class plagiarismsearch_reports extends plagiarismsearch_table {

    // phpcs:disable moodle.Commenting.MissingDocblock.Constant
    const STATUS_NOT_PAID = -13;
    const STATUS_SERVER_CORE_ERROR = -12;
    const STATUS_SERVER_ERROR = -11;
    const STATUS_ERROR = -10;
    const STATUS_INIT = -9;
    const STATUS_RESERVED__8 = -8;
    const STATUS_RESERVED__7 = -7;
    const STATUS_RESERVED__6 = -6;
    const STATUS_RESERVED__5 = -5;
    const STATUS_PROCESSING_STORAGE = -4;
    const STATUS_PROCESSING_STORAGE_CHECK = -3;
    const STATUS_PROCESSING_FILES = -2;
    const STATUS_PROCESSING_FILES_CHECK = -1;
    const STATUS_PROCESSING = 0;
    const STATUS_PRE_CHECKED = 1;
    const STATUS_SOURCES = 3;
    const STATUS_POST_CHECKED = 4;
    const STATUS_SNIPPETS = 5;
    const STATUS_RESERVED_6 = 6;
    const STATUS_RESERVED_7 = 7;
    const STATUS_RESERVED_8 = 8;
    const STATUS_RESERVED_9 = 9;
    const STATUS_CHECKED = 2;
    // phpcs:enable moodle.Commenting.MissingDocblock.Constant

    /**
     * Report statuses
     *
     * @var string[]
     */
    public static $statuses = [
            self::STATUS_NOT_PAID => 'not paid',
            self::STATUS_SERVER_CORE_ERROR => 'server error',
            self::STATUS_SERVER_ERROR => 'failed',
            self::STATUS_ERROR => 'error',
            self::STATUS_INIT => 'init',
            self::STATUS_RESERVED__8 => 'init',
            self::STATUS_RESERVED__7 => 'init',
            self::STATUS_RESERVED__6 => 'init',
            self::STATUS_RESERVED__5 => 'init',
            self::STATUS_PROCESSING_STORAGE => 'processing',
            self::STATUS_PROCESSING_STORAGE_CHECK => 'processing',
            self::STATUS_PROCESSING_FILES => 'processing',
            self::STATUS_PROCESSING_FILES_CHECK => 'processing',
            self::STATUS_PROCESSING => 'processing',
            self::STATUS_PRE_CHECKED => 'processing',
            self::STATUS_POST_CHECKED => 'processing',
            self::STATUS_SOURCES => 'processing',
            self::STATUS_SNIPPETS => 'processing',
            self::STATUS_RESERVED_6 => 'processing',
            self::STATUS_RESERVED_7 => 'processing',
            self::STATUS_RESERVED_8 => 'processing',
            self::STATUS_RESERVED_9 => 'processing',
            self::STATUS_CHECKED => 'checked',
    ];

    /**
     * Db table name
     *
     * @return string
     */
    public static function table_name() {
        // Moodle error: 'name is too long. Limit is 28 chars.'!
        return 'plagiarism_ps_reports';
    }

    /**
     * Hook before insert into table
     *
     * @param array $values
     * @return array
     */
    protected static function before_insert($values) {
        $values['created_at'] = $values['modified_at'] = time();
        return $values;
    }

    /**
     * Hook before update into table
     *
     * @param array $values
     * @return array
     */
    public static function before_update($values) {
        $values['modified_at'] = time();
        return $values;
    }

    /**
     * Add or update report
     *
     * @param array $values
     * @return bool|int|null
     */
    public static function add($values) {
        if (isset($values['rid'])) {
            $report = static::get_one(['rid' => $values['rid']]);
            if ($report) {
                return static::update(array_merge((array) $report, $values), $report->id);
            }
        }
        return static::insert($values);
    }

    /**
     * Count valid reports
     *
     * @param array|null $conditions
     * @return int|null
     * @throws dml_exception
     */
    public static function count_valid($conditions) {
        $statuses = static::get_processing_statuses();
        $statuses[] = static::STATUS_CHECKED;
        $conditions['status'] = $statuses;

        list($where, $params) = static::build_conditions($conditions);

        if (!$where) {
            return null;
        }

        $result = static::db()->get_record_sql("SELECT COUNT(*) AS count FROM {" . static::table_name() . "}"
                . " WHERE rid > 0 AND " . $where, $params);

        return isset($result) ? $result->count : null;
    }

    /**
     * Get one report by conditions
     *
     * @param array|null $conditions
     * @return false|mixed
     * @throws dml_exception
     */
    public static function get_one_top($conditions) {
        list($where, $params) = static::build_conditions($conditions);

        return static::db()->get_record_sql("SELECT * FROM {" . static::table_name() . "}"
                . ($where ? " WHERE " . $where : '')
                . " ORDER BY created_at DESC LIMIT 1", $params);
    }

    /**
     * Get one report by conditions
     *
     * @param int $ttl
     * @param int $limit
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function get_processing_reports($ttl = 300, $limit = 50) {
        list($where, $status) = static::db()->get_in_or_equal(static::get_processing_statuses());

        return static::db()->get_records_sql("SELECT * FROM {" . static::table_name() . "}"
                . " WHERE modified_at < ? AND status " . $where
                . " LIMIT " . $limit, array_merge([time() - $ttl], $status));
    }

    /**
     * Get one report by conditions
     *
     * @param int $ttl
     * @param int $limit
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function get_error_reports($ttl = 43200, $limit = 50) {
        list($where, $status) = static::db()->get_in_or_equal(static::get_error_statuses());

        return static::db()->get_records_sql("SELECT * FROM {" . static::table_name() . "}"
                . " WHERE rid > 0 AND modified_at < ? AND status " . $where
                . " LIMIT " . $limit, array_merge([time() - $ttl], $status));
    }

    /**
     * Count reports by condition
     *
     * @param array|null $conditions
     * @return int|null
     * @throws dml_exception
     */
    public static function count($conditions) {
        $row = static::db()->get_record(static::table_name(), $conditions, 'COUNT(*) AS count');
        return isset($row) ? $row->count : null;
    }

    /**
     * Choose color class by report
     *
     * @param object $report
     * @return string
     * @throws dml_exception
     * @throws moodle_exception
     */
    public static function get_color_class($report) {
        if (!$report) {
            return '';
        }
        $yellow = plagiarismsearch_config::get_settings('yellow_percent');
        if (!$yellow) {
            $yellow = 2; // 2%.
        }
        $red = plagiarismsearch_config::get_settings('red_percent');

        if (!$red) {
            $red = 7; // 7%.
        }

        if ($report->plagiarism >= $red) {
            return 'plagiarismsearch-bad';
        } else if ($report->plagiarism >= $yellow) {
            return 'plagiarismsearch-warning';
        } else {
            return 'plagiarismsearch-good';
        }
    }

    /**
     * Choose color class by report
     *
     * @param object $report
     * @return string
     */
    public static function get_ai_color_class($report) {
        if (!$report) {
            return '';
        }
        return 'plagiarismsearch-ai';
    }

    /**
     * Get all report statuses
     *
     * @return int[]
     */
    public static function get_error_statuses() {
        return [
                self::STATUS_NOT_PAID,
                self::STATUS_SERVER_CORE_ERROR,
                self::STATUS_SERVER_ERROR,
                self::STATUS_ERROR,
        ];
    }

    /**
     * Check if report is error
     *
     * @param object $report
     * @return bool
     */
    public static function is_error($report) {
        return $report && in_array($report->status, static::get_error_statuses());
    }

    /**
     * Get processing report statuses
     *
     * @return int[]
     */
    public static function get_processing_statuses() {
        return [
                self::STATUS_INIT,
                self::STATUS_RESERVED__8,
                self::STATUS_RESERVED__7,
                self::STATUS_RESERVED__6,
                self::STATUS_RESERVED__5,
                self::STATUS_PROCESSING_STORAGE,
                self::STATUS_PROCESSING_STORAGE_CHECK,
                self::STATUS_PROCESSING_FILES,
                self::STATUS_PROCESSING_FILES_CHECK,
                self::STATUS_PROCESSING,
                self::STATUS_PRE_CHECKED,
                self::STATUS_POST_CHECKED,
                self::STATUS_SOURCES,
                self::STATUS_SNIPPETS,
                self::STATUS_RESERVED_6,
                self::STATUS_RESERVED_7,
                self::STATUS_RESERVED_8,
                self::STATUS_RESERVED_9,
        ];
    }

    /**
     * Check if report is processing
     *
     * @param object $report
     * @return bool
     */
    public static function is_processing($report) {
        return $report && in_array($report->status, static::get_processing_statuses());
    }

    /**
     * Check if report is checked
     *
     * @param object $report
     * @return bool
     */
    public static function is_checked($report) {
        return $report && $report->status == self::STATUS_CHECKED;
    }

    /**
     * Check if report is checked and has AI rate
     *
     * @param object $report
     * @return bool
     */
    public static function is_checked_ai($report) {
        return self::is_checked($report) && $report->ai_rate !== null;
    }

    /**
     * Build PDF link
     *
     * @param object $report
     * @param int|null $cmid
     * @return string|null
     */
    public static function build_pdf_link($report, $cmid = null) {
        if (empty($report->rkey)) {
            return $report->url;
        }

        $language = (string) plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_REPORT_LANGUAGE);

        return static::build_link($report, $language, '/download');
    }

    /**
     * Build HTML link
     *
     * @param object $report
     * @param int|null $cmid
     * @return string|null
     */
    public static function build_html_link($report, $cmid = null) {
        if (empty($report->rkey)) {
            return null;
        }

        $language = (string) plagiarismsearch_config::get_config_or_settings($cmid, plagiarismsearch_config::FIELD_REPORT_LANGUAGE);

        return static::build_link($report, $language, '');
    }

    /**
     * Build report link
     *
     * @param object $report
     * @param string $language
     * @param string $suffix
     * @return string
     */
    protected static function build_link($report, $language, $suffix = '') {
        if (!empty($report->rserverurl)) {
            $baseurl = $report->rserverurl;
        } else {
            $baseurl = 'https://plagiarismsearch.com';
        }
        if (empty($language)) {
            $route = '/reports';
        } else if ($language == plagiarismsearch_config::LANGUAGE_EN) {
            $route = '/r';
        } else {
            $route = '/' . $language . '/r';
        }

        return $baseurl . $route . $suffix . '/' . $report->rid . '?key=' . $report->rkey;
    }

}
