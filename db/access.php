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
 * Contains Plagiarism plugin specific capabilities called by Modules.
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$capabilities = [
        'plagiarism/plagiarismsearch:viewlinks' => [
            // Ability to view links to plagiarism results.
                'captype' => 'read',
                'riskbitmask' => RISK_PERSONAL,
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'manager' => CAP_ALLOW,
                        'coursecreator' => CAP_ALLOW,
                        'editingteacher' => CAP_ALLOW,
                        'teacher' => CAP_ALLOW,
                        'student' => CAP_ALLOW,
                ],
        ],

        'plagiarism/plagiarismsearch:submitlinks' => [
            // Ability to submit links to plagiarismsearch.
                'captype' => 'read',
                'riskbitmask' => RISK_PERSONAL,
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'manager' => CAP_ALLOW,
                        'coursecreator' => CAP_ALLOW,
                        'editingteacher' => CAP_ALLOW,
                        'teacher' => CAP_ALLOW,
                        'student' => CAP_ALLOW,
                ],
        ],

        'plagiarism/plagiarismsearch:statuslinks' => [
            // Ability to check status.
                'captype' => 'read',
                'riskbitmask' => RISK_PERSONAL,
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'manager' => CAP_ALLOW,
                        'coursecreator' => CAP_ALLOW,
                        'editingteacher' => CAP_ALLOW,
                        'teacher' => CAP_ALLOW,
                        'student' => CAP_ALLOW,
                ],
        ],

        'plagiarism/plagiarismsearch:reviewlinks' => [
            // Ability to comment and review report sources.
                'captype' => 'read',
                'riskbitmask' => RISK_PERSONAL,
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'manager' => CAP_ALLOW,
                        'coursecreator' => CAP_ALLOW,
                        'editingteacher' => CAP_ALLOW,
                        'teacher' => CAP_ALLOW,
                ],
        ],

        'plagiarism/plagiarismsearch:isstudent' => [
            // Only students.
                'captype' => 'read',
                'riskbitmask' => RISK_PERSONAL,
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'student' => CAP_ALLOW,
                ],
        ],

        'plagiarism/plagiarismsearch:isadministrator' => [
            // Only administrator.
                'captype' => 'read',
                'riskbitmask' => RISK_PERSONAL,
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'administrator' => CAP_ALLOW,
                ],
        ],
];
