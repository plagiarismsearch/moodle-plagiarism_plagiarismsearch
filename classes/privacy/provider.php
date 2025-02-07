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
 * Privacy Subsystem implementation for plagiarism_plagiarismsearch
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2025 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_plagiarismsearch\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\{writer, helper, contextlist, approved_contextlist, approved_userlist, userlist};

/**
 * Privacy subsystem for plagiarism_plagiarismsearch.
 */
class provider implements
        \core_privacy\local\metadata\provider,
        \core_privacy\local\request\core_userlist_provider,
        \core_plagiarism\privacy\plagiarism_provider {

    /**
     * Return the fields which contain personal data.
     *
     * @param collection $collection a reference to the collection to use to store the metadata.
     * @return collection the updated collection of metadata items.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
                'plagiarism_ps_reports',
                [
                        'userid' => 'privacy:metadata:plagiarism_ps_reports:userid',
                        'senderid' => 'privacy:metadata:plagiarism_ps_reports:senderid',
                        'rid' => 'privacy:metadata:plagiarism_ps_reports:rid',
                        'rfileid' => 'privacy:metadata:plagiarism_ps_reports:rfileid',
                        'rserverurl' => 'privacy:metadata:plagiarism_ps_reports:rserverurl',
                        'rkey' => 'privacy:metadata:plagiarism_ps_reports:rkey',
                        'plagiarism' => 'privacy:metadata:plagiarism_ps_reports:plagiarism',
                        'ai_rate' => 'privacy:metadata:plagiarism_ps_reports:ai_rate',
                        'ai_probability' => 'privacy:metadata:plagiarism_ps_reports:ai_probability',
                        'status' => 'privacy:metadata:plagiarism_ps_reports:status',
                        'url' => 'privacy:metadata:plagiarism_ps_reports:url',
                        'cmid' => 'privacy:metadata:plagiarism_ps_reports:cmid',
                        'filehash' => 'privacy:metadata:plagiarism_ps_reports:filehash',
                        'filename' => 'privacy:metadata:plagiarism_ps_reports:filename',
                        'fileid' => 'privacy:metadata:plagiarism_ps_reports:fileid',
                        'log' => 'privacy:metadata:plagiarism_ps_reports:log',
                        'created_at' => 'privacy:metadata:plagiarism_ps_reports:created_at',
                        'modified_at' => 'privacy:metadata:plagiarism_ps_reports:modified_at',
                ], 'privacy:metadata:plagiarism_ps_reports'
        );

        $collection->link_external_location('plagiarism_plagiarismsearch', [
                'userid' => 'privacy:metadata:plagiarism_plagiarismsearch_client:userid',
                'cmid' => 'privacy:metadata:plagiarism_plagiarismsearch_client:cmid',
                'onlinetext' => 'privacy:metadata:plagiarism_plagiarismsearch_client:onlinetext',
                'fileid' => 'privacy:metadata:plagiarism_plagiarismsearch_client:fileid',
                'fileauthor' => 'privacy:metadata:plagiarism_plagiarismsearch_client:fileauthor',
                'filename' => 'privacy:metadata:plagiarism_plagiarismsearch_client:filename',
                'file' => 'privacy:metadata:plagiarism_plagiarismsearch_client:file',
        ], 'privacy:metadata:plagiarism_plagiarismsearch_client');

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid the userid.
     * @return contextlist the list of contexts containing user info for the user.
     */
    public static function get_contexts_for_userid($userid): contextlist {
        $contextlist = new contextlist();
        $sql = "SELECT DISTINCT cmid FROM {plagiarism_ps_reports} WHERE userid = :userid";
        $params = [
                'userid' => $userid,
        ];
        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Export all plagiarism data from each plagiarism plugin for the specified userid and context.
     *
     * @param int $userid The user to export.
     * @param \context $context The context to export.
     * @param array $subcontext The subcontext within the context to export this information to.
     * @param array $linkarray The weird and wonderful link array used to display information for a specific item
     */
    public static function export_plagiarism_user_data(int $userid, \context $context, array $subcontext, array $linkarray) {
        global $DB;
        if (empty($userid)) {
            return;
        }
        $user = $DB->get_record('user', ['id' => $userid]);
        $params = [
                'userid' => $user->id,
                'cmid' => $context->instanceid,
        ];

        $sql = "SELECT id, userid, senderid, cmid, rid, rfileid, rserverurl, rkey, plagiarism, ai_rate, ai_probability,
                status, url, filehash, filename, fileid, log, created_at, modified_at
                FROM {plagiarism_ps_reports}
                WHERE userid = :userid AND cmid = :cmid";

        $submissions = $DB->get_records_sql($sql, $params);
        foreach ($submissions as $submission) {
            $context = \context_module::instance($submission->cmid);
            $contextdata = helper::get_context_data($context, $user);
            // Merge with module data and write it.
            $contextdata = (object) array_merge((array) $contextdata, (array) $submission);
            writer::with_context($context)->export_data([], $contextdata);
            // Write generic module intro files.
            helper::export_context_files($context, $user);
        }
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context the context to delete in.
     */
    public static function delete_plagiarism_for_context(\context $context) {
        global $DB;
        if (empty($context)) {
            return;
        }
        if (!$context instanceof \context_module) {
            return;
        }
        // Delete all submissions.
        $DB->delete_records('plagiarism_ps_reports', ['cmid' => $context->instanceid]);
    }

    /**
     * Delete all user information for the provided user and context.
     *
     * @param int $userid The user to delete
     * @param \context $context The context to refine the deletion.
     */
    public static function delete_plagiarism_for_user(int $userid, \context $context) {
        global $DB;

        $DB->delete_records('plagiarism_ps_reports', ['userid' => $userid, 'cmid' => $context->instanceid]);
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();
        if (!$context instanceof \context_module) {
            return;
        }
        $params = [
                'cmid' => $context->instanceid,
        ];
        $sql = "SELECT DISTINCT userid FROM {plagiarism_ps_reports} WHERE cmid = :cmid";
        $userlist->add_from_sql('userid', $sql, $params);
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }
        $userid = $contextlist->get_user()->id;
        $userdatadocs = $DB->get_records('plagiarism_ps_reports', ['userid' => $userid]);
        foreach ($userdatadocs as $udd) {
            $context = \context_module::instance($udd->cmid);
            writer::with_context($context)->export_data([], (object) $udd);
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;
        $context = $userlist->get_context();

        if (!$context instanceof \context_module) {
            return;
        }
        // Prepare SQL to gather all completed IDs.
        $userids = $userlist->get_userids();
        list($insql, $inparams) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);

        $inparams['cmid'] = $context->instanceid;

        $DB->delete_records_select(
                'plagiarism_ps_reports',
                "cmid = :cmid AND userid $insql",
                $inparams
        );
    }
}
