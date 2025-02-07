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
 * Plugin version config
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if (!isset($plugin)) {
    $plugin = new \stdClass();
}
$plugin->version = 2025020701; // YYYYMMDDVV.
$plugin->requires = 2014051200; // Requires Moodle 2.7.
$plugin->supported = [270, 450]; // Moodle 2.7 and 4.5 supported.
$plugin->cron = 60;
$plugin->maturity = MATURITY_STABLE;
$plugin->component = 'plagiarism_plagiarismsearch';
$plugin->release = '1.1.22';
$plugin->icon = 'pix/logo.png';
