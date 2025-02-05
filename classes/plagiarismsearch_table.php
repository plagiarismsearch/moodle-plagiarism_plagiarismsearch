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
class plagiarismsearch_table extends plagiarismsearch_base {

    /**
     * @return \moodle_database
     */
    public static function db() {
        global $DB;
        return $DB;
    }

    /**
     * Table name
     *
     * @return string
     */
    public static function table_name() {
        return get_called_class();
    }

    /**
     * Get all records by conditions
     *
     * @param $conditions
     * @return array
     * @throws dml_exception
     */
    public static function get_all($conditions) {
        return static::db()->get_records(static::table_name(), $conditions);
    }

    /**
     * Get one record by conditions
     *
     * @param $conditions
     * @return false|mixed|stdClass
     * @throws dml_exception
     */
    public static function get_one($conditions) {
        return static::db()->get_record(static::table_name(), $conditions);
    }

    /**
     * Count records by conditions
     *
     * @param $conditions
     * @return int|null
     * @throws dml_exception
     */
    public static function count($conditions) {
        $row = static::db()->get_record(static::table_name(), $conditions, 'COUNT(*) AS count');
        return isset($row) ? $row->count : null;
    }

    /**
     * Insert record
     *
     * @param $values
     * @return bool|int|void
     * @throws dml_exception
     */
    public static function insert($values) {
        $values = static::before_insert($values);
        if ($values) {
            return static::db()->insert_record(static::table_name(), $values);
        }
    }

    /**
     * Before insert
     *
     * @param $values
     * @return mixed
     */
    protected static function before_insert($values) {
        return $values;
    }

    /**
     * Update record
     *
     * @param $values
     * @param $conditions
     * @return bool|null
     * @throws dml_exception
     */
    public static function update($values, $conditions = null) {
        if (!$conditions) {
            return null;
        }
        $values = static::before_update($values);
        if (!$values) {
            return null;
        }
        $values['id'] = $conditions;

        return static::db()->update_record(static::table_name(), $values);
    }

    /**
     * Before update
     *
     * @param $values
     * @return mixed
     */
    public static function before_update($values) {
        return $values;
    }

    /**
     * Delete record
     *
     * @param $conditions
     * @return bool|null
     * @throws dml_exception
     */
    public static function delete($conditions = null) {
        if (!$conditions) {
            return null;
        }
        return static::db()->delete_records(static::table_name(), $conditions);

    }

    /**
     * Build conditions
     *
     * @param $conditions
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    protected static function build_conditions($conditions) {
        $where = [];
        $params = [];

        if ($conditions) {
            foreach ($conditions as $key => $value) {
                if ($value === null) {
                    $where[] = $key . ' = NULL';
                } else if (is_array($value)) {
                    list($w, $p) = static::db()->get_in_or_equal($value);
                    $where[] = $key . ' ' . $w;
                    $params = array_merge($params, $p);
                } else {
                    $where[] = $key . ' = ?';
                    $params[$key] = $value;
                }
            }
        }

        return [
                $where ? implode(' AND ', $where) : '',
                $params,
        ];
    }

}
