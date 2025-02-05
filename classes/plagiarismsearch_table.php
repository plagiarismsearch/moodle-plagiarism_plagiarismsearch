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

    public static function table_name() {
        return get_called_class();
    }

    public static function get_all($conditions) {
        return static::db()->get_records(static::table_name(), $conditions);
    }

    public static function get_one($conditions) {
        return static::db()->get_record(static::table_name(), $conditions);
    }

    public static function count($conditions) {
        $row = static::db()->get_record(static::table_name(), $conditions, 'COUNT(*) AS count');
        return isset($row) ? $row->count : null;
    }

    public static function insert($values) {
        $values = static::before_insert($values);
        if ($values) {
            return static::db()->insert_record(static::table_name(), $values);
        }
    }

    protected static function before_insert($values) {
        return $values;
    }

    public static function update($values, $conditions = null) {
        if (!$conditions) {
            return;
        }
        $values = static::before_update($values);
        if ($values) {
            $values['id'] = $conditions;

            return static::db()->update_record(static::table_name(), $values);
        }
    }

    public static function before_update($values) {
        return $values;
    }

    public static function delete($conditions = null) {
        if ($conditions) {
            return static::db()->delete_records(static::table_name(), $conditions);
        }
    }

    protected static function build_conditions($conditions) {
        $where = array();
        $params = array();

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

        return array(
                $where ? implode(' AND ', $where) : '',
                $params,
        );
    }

}
