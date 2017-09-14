<?php

class plagiarismsearch_config extends plagiarismsearch_table
{
    const USE_NAME = 'plagiarismsearch_use';

    protected static $config = array();
    protected static $settings = array();

    public static function table_name()
    {
        return 'plagiarism_ps_config';
    }

    public static function fields()
    {
        return array(
            'use' => 'plagiarismsearch_use',
            'api_url' => 'plagiarismsearch_api_url',
            'api_user' => 'plagiarismsearch_api_user',
            'api_key' => 'plagiarismsearch_api_key',
            'filter_chars' => 'plagiarismsearch_filter_chars',
            'filter_references' => 'plagiarismsearch_filter_references',
            'filter_quotes' => 'plagiarismsearch_filter_quotes',
            //'autostart' => 'plagiarismsearch_autostart',
            'student_disclosure' => 'plagiarismsearch_student_disclosure',
        );
    }

    public static function get_config_or_settings($cmid, $name, $default = null)
    {
        $value = static::get_config($cmid, $name, null);
        if ($value === null) {
            $value = static::get_settings($name);
        }

        return ($value == null) ? $default : $value;
    }

    public static function get_config($cmid, $name, $default = false)
    {
        if (isset(static::$config[$cmid][$name])) {
            return static::$config[$cmid][$name];
        }

        global $DB;

        $condition = array(
            'cmid' => $cmid,
            'name' => $name,
        );

        if ($config = $DB->get_records(static::table_name(), $condition, '', 'cmid,name,value')) {
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
     * This function should be used to initialise settings and check if plagiarism is enabled.
     *
     * @param null $key
     *
     * @return array|bool
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    public static function get_settings($key = null)
    {
        if (!empty(static::$settings)) {
            return self::get_settings_item($key);
        }

        static::$settings = (array) get_config('plagiarism');

        // Check if enabled.
        if (isset(static::$settings[static::USE_NAME]) && static::$settings[static::USE_NAME]) {
            // Now check to make sure required settings are set!
            // TODO uncomment
//            if (empty(static::$settings['plagiarismsearch_api_key'])) {
//                throw new \coding_exception('PlagiarismSearch Api key not set!');
//            }

            return self::get_settings_item($key);
        } else {
            return false;
        }
    }

    /**
     * @param      $settings
     * @param null $key
     *
     * @return null
     */
    private static function get_settings_item($key = null)
    {
        if (is_null($key)) {
            return static::$settings;
        }

        $key = 'plagiarismsearch_' . $key;

        return isset(static::$settings[$key]) ? static::$settings[$key] : null;
    }

}
