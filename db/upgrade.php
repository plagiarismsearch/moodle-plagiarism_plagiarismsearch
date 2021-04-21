<?php
// This file is part of the PlagiarismSearch plugin for Moodle - http://moodle.org/
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
 * lib.php - Contains Plagiarism plugin specific functions called by Modules.
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
function xmldb_plagiarism_plagiarismsearch_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2021042109) {

        $table = new xmldb_table('plagiarism_plagiarismsearchr');

        $field = new xmldb_field('rfileid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, '0', 'rid');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('rserverurl', XMLDB_TYPE_CHAR, '255', XMLDB_UNSIGNED, null, null, null, 'rfileid');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('rkey', XMLDB_TYPE_CHAR, '64', XMLDB_UNSIGNED, null, null, null, 'rserverurl');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Savepoint reached
        upgrade_plugin_savepoint(true, 2021042109, 'plagiarism', 'plagiarismsearch');
    }

    return true;
}
