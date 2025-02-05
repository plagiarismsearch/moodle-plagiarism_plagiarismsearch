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
 * Load all classes
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__) . '/plagiarismsearch_base.php');
require_once(dirname(__FILE__) . '/plagiarismsearch_core.php');
require_once(dirname(__FILE__) . '/plagiarismsearch_table.php');
require_once(dirname(__FILE__) . '/plagiarismsearch_config.php');
require_once(dirname(__FILE__) . '/plagiarismsearch_api.php');
require_once(dirname(__FILE__) . '/plagiarismsearch_api_reports.php');
require_once(dirname(__FILE__) . '/plagiarismsearch_reports.php');
require_once(dirname(__FILE__) . '/plagiarismsearch_event_handler.php');
