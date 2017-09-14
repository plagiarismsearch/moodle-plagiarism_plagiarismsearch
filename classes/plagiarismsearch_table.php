<?php

class plagiarismsearch_table
{

    /**
     * @return \moodle_database
     */
    public static function db()
    {
        global $DB;
        return $DB;
    }

    public static function table_name()
    {
        return get_called_class();
    }

    public static function get_all($conditions)
    {
        return static::db()->get_records(static::table_name(), $conditions);
    }

    public static function get_one($conditions)
    {
        return static::db()->get_record(static::table_name(), $conditions);
    }

    public static function insert($values)
    {
        if ($values = static::before_insert($values)) {
            return static::db()->insert_record(static::table_name(), $values);
        }
    }

    protected static function before_insert($values)
    {
        return $values;
    }

    public static function update($values, $conditions = null)
    {
        if ($conditions and $values = static::before_update($values)) {
            $values['id'] = $conditions;

            return static::db()->update_record(static::table_name(), $values);
        }
    }

    public static function before_update($values)
    {
        return $values;
    }

    public static function delete($conditions = null)
    {
        if ($conditions) {
            return static::db()->delete_records(static::table_name(), $conditions);
        }
    }

}
