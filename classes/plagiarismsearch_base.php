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

    public function __construct($config = array()) {
        $this->configure($config);
    }

    protected function configure($config = array()) {
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

    public static function translate($value, $module = 'plagiarism_plagiarismsearch') {
        if (empty($value)) {
            return $value;
        }
        return get_string($value, $module);
    }

}
