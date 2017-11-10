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
class plagiarismsearch_reports extends plagiarismsearch_table {

    const STATUS_SERVER_ERROR = -11;
    const STATUS_ERROR = -10;
    const STATUS_PROCESSING_STORAGE = -4;
    const STATUS_PROCESSING_STORAGE_CHECK = -3;
    const STATUS_PROCESSING_FILES = -2;
    const STATUS_PROCESSING_FILES_CHECK = -1;
    const STATUS_PROCESSING = 0;
    const STATUS_PRE_CHECKED = 1;
    const STATUS_SOURCES = 3;
    const STATUS_POST_CHECKED = 4;
    const STATUS_RESERVED_5 = 5;
    const STATUS_RESERVED_6 = 6;
    const STATUS_RESERVED_7 = 7;
    const STATUS_RESERVED_8 = 8;
    const STATUS_RESERVED_9 = 9;
    const STATUS_CHECKED = 2;
    /**/
    const SUBMIT_WEB = 1;
    const SUBMIT_STORAGE = 2;
    // self::SUBMIT_WEB | self::SUBMIT_STORAGE;
    const SUBMIT_WEB_STORAGE = 3;

    public static $statuses = array(
        self::STATUS_SERVER_ERROR => 'server error',
        self::STATUS_ERROR => 'error',
        self::STATUS_PROCESSING_STORAGE => 'processing',
        self::STATUS_PROCESSING_STORAGE_CHECK => 'processing',
        self::STATUS_PROCESSING_FILES => 'processing',
        self::STATUS_PROCESSING_FILES_CHECK => 'processing',
        self::STATUS_PROCESSING => 'processing',
        self::STATUS_PRE_CHECKED => 'processing',
        self::STATUS_POST_CHECKED => 'processing',
        self::STATUS_SOURCES => 'processing',
        self::STATUS_RESERVED_5 => 'processing',
        self::STATUS_RESERVED_6 => 'processing',
        self::STATUS_RESERVED_7 => 'processing',
        self::STATUS_RESERVED_8 => 'processing',
        self::STATUS_RESERVED_9 => 'processing',
        self::STATUS_CHECKED => 'checked',
    );

    public static function table_name() {
        // Moodle error: 'name is too long. Limit is 28 chars.'
        return 'plagiarism_plagiarismsearchr';
    }

    public static function is_submit_web($type) {
        return $type & self::SUBMIT_WEB;
    }

    public static function is_submit_storage($type) {
        return $type & self::SUBMIT_STORAGE;
    }

    protected static function before_insert($values) {
        $values['created_at'] = $values['modified_at'] = time();
        return $values;
    }

    public static function before_update($values) {
        $values['modified_at'] = time();
        return $values;
    }

    public static function add($values) {
        if (isset($values['rid']) and $report = static::get_one(array('rid' => $values['rid']))) {
            return static::update(array_merge((array) $report, $values), $report->id);
        } else {
            return static::insert($values);
        }
    }

    public static function count_valid($conditions) {
        $statuses = static::get_processing_statuses();
        $statuses[] = static::STATUS_CHECKED;
        $conditions['status'] = $statuses;

        list($where, $params) = static::build_conditions($conditions);

        $result = static::db()->get_record_sql("SELECT COUNT(*) AS count FROM {" . static::table_name() . "}"
                . ($where ? " WHERE " . $where : ''), $params);

        return isset($result) ? $result->count : null;
    }

    public static function get_one_top($conditions) {
        list($where, $params) = static::build_conditions($conditions);

        return static::db()->get_record_sql("SELECT * FROM {" . static::table_name() . "}"
                        . ($where ? " WHERE " . $where : '')
                        . " ORDER BY created_at DESC LIMIT 1", $params);
    }

    public static function get_processing_reports($ttl = 300, $limit = 50) {
        list($where, $status) = static::db()->get_in_or_equal(static::get_processing_statuses());

        return static::db()->get_records_sql("SELECT * FROM {" . static::table_name() . "}"
                        . " WHERE modified_at < ? AND status " . $where
                        . " LIMIT " . $limit, array_merge(array(time() - $ttl), $status));
    }

    public static function get_error_reports($ttl = 43200, $limit = 50) {
        list($where, $status) = static::db()->get_in_or_equal(static::get_error_statuses());

        return static::db()->get_records_sql("SELECT * FROM {" . static::table_name() . "}"
                        . " WHERE rid > 0 AND modified_at < ? AND status " . $where
                        . " LIMIT " . $limit, array_merge(array(time() - $ttl), $status));
    }

    public static function count($conditions) {
        $row = static::db()->get_record(static::table_name(), $conditions, 'COUNT(*) AS count');
        return isset($row) ? $row->count : null;
    }

    public static function get_color_class($report) {
        if ($report) {
            $yellow = plagiarismsearch_config::get_settings('yellow_percent');
            if (!$yellow) {
                $yellow = 2; // 2%
            }
            $red = plagiarismsearch_config::get_settings('red_percent');

            if (!$red) {
                $red = 7; // 7%
            }

            if ($report->plagiarism >= $red) {
                return 'plagiarismsearch-bad';
            } else if ($report->plagiarism >= $yellow) {
                return 'plagiarismsearch-warning';
            } else {
                return 'plagiarismsearch-good';
            }
        }
    }

    public static function get_error_statuses() {
        return array(
            self::STATUS_SERVER_ERROR,
            self::STATUS_ERROR,
        );
    }

    public static function is_error($report) {
        return $report and in_array($report->status, static::get_error_statuses());
    }

    public static function get_processing_statuses() {
        return array(
            self::STATUS_PROCESSING_STORAGE,
            self::STATUS_PROCESSING_STORAGE_CHECK,
            self::STATUS_PROCESSING_FILES,
            self::STATUS_PROCESSING_FILES_CHECK,
            self::STATUS_PROCESSING,
            self::STATUS_PRE_CHECKED,
            self::STATUS_POST_CHECKED,
            self::STATUS_SOURCES,
            self::STATUS_RESERVED_5,
            self::STATUS_RESERVED_6,
            self::STATUS_RESERVED_7,
            self::STATUS_RESERVED_8,
            self::STATUS_RESERVED_9,
        );
    }

    public static function is_processing($report) {
        return $report and in_array($report->status, static::get_processing_statuses());
    }

    public static function is_checked($report) {
        return $report and $report->status == self::STATUS_CHECKED;
    }

}
