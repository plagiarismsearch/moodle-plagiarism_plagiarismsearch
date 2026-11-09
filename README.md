# moodle-plagiarism_plagiarismsearch  

[//]: # ([![Build Status]&#40;https://api.travis-ci.org/plagiarismsearch/moodle-plagiarism_plagiarismsearch.svg?branch=master&#41;]&#40;https://travis-ci.org/plagiarismsearch/moodle-plagiarism_plagiarismsearch&#41;)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/plagiarismsearch/moodle-plagiarism_plagiarismsearch/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/plagiarismsearch/moodle-plagiarism_plagiarismsearch/?branch=master)

PlagiarismSearch Plagiarism plugin for Moodle

**Supported Moodle versions:** 2.7 - 5.3  
**Supported PHP versions:** 7.3 - 8.4  
**Moodle marketplace directory:** https://marketplace.moodle.com/plugins/plagiarism_plagiarismsearch

Author: Alex Crosby <developer@plagiarismsearch.com>  
Copyright: PlagiarismSearch.com, https://plagiarismsearch.com  

 > PlagiarismSearch is an advanced plagiarism detection service that operates since 2008. 
PlagiarismSearch is a commercial product owned by Plagiarismsearch.com.


## Quick install

1. Get latest release (zip file) on [GitHub](https://github.com/plagiarismsearch/moodle-plagiarism_plagiarismsearch/releases) or [Moodle marketplace directory](https://marketplace.moodle.com/plugins/plagiarism_plagiarismsearch)
2. Follow the instructions described [here](https://docs.moodle.org/31/en/Installing_plugins#Installing_via_uploaded_ZIP_file) to install plugin
3. Enable the Plagiarism API under admin > Advanced Features  
4. Configure the PlagiarismSearch plugin under admin > plugins > Plagiarism > PlagiarismSearch  


## Allow only selected teachers to submit documents for checking

The Submit and Resubmit links are controlled by the `plagiarism/plagiarismsearch:submitlinks` capability.
Site administrators always have it. To allow only some teachers to submit documents, use an extra "Plagiarism checker" role:

1. **Take the capability away from the teacher roles.**
   Go to *Site administration > Users > Permissions > Define roles*. For **Teacher** and **Non-editing teacher**,
   set `plagiarism/plagiarismsearch:submitlinks` to **Not set**.
   > Don't use **Prohibit**. Prohibit overrides Allow from every other role, so the teachers you want to allow wouldn't get it back.
2. **Create a new role.** Click *Add a new role* and name it, for example, "Plagiarism checker".
   - Context types: tick **Course** (and **Category** if you need it).
   - Archetype: **none**.
   - Permissions: **Allow** `plagiarism/plagiarismsearch:submitlinks` and nothing else.
3. **Give that role to the chosen teachers.**
   In the course, go to *Participants > (teacher) > Roles* and add "Plagiarism checker". They keep their normal Teacher role as well.
   To give it across a whole category, assign it at category level: *Category > Assign roles*.

Now teachers with the extra role can submit and resubmit documents, and all other teachers can't.


## Database table names (upgrade to 1.1.31)

Moodle requires plugin table names to start with the component name. Starting with version 1.1.31 (`2026110902`),
the plugin tables are renamed back:

| Old name (1.1.x before 1.1.31) | New name                       |
|--------------------------------|--------------------------------|
| `plagiarism_ps_reports`        | `plagiarism_plagiarismsearchr` |
| `plagiarism_ps_config`         | `plagiarism_plagiarismsearchc` |

The rename runs automatically during the normal Moodle upgrade (*Site administration > Notifications*
or `php admin/cli/upgrade.php`). Each table is renamed only if the old one exists, so the step is safe to run again.
No data is changed or lost.

### Manual fallback (MySQL / MariaDB)

If the upgrade stops with an error on this step (for example, the database user has no `ALTER` privilege,
or a table with the new name already exists), rename the tables by hand and then run the upgrade again.

1. Back up the database.
2. Check which tables exist. Replace `mdl_` with your `$CFG->prefix` from `config.php`:
   ```sql
   SHOW TABLES LIKE 'mdl\_plagiarism\_ps\_%';
   SHOW TABLES LIKE 'mdl\_plagiarism\_plagiarismsearch%';
   ```
3. If a new-name table already exists and is empty (for example, it was left over from a failed install), drop it first:
   ```sql
   SELECT COUNT(*) FROM mdl_plagiarism_plagiarismsearchr;
   SELECT COUNT(*) FROM mdl_plagiarism_plagiarismsearchc;
   -- Only if the count is 0:
   DROP TABLE mdl_plagiarism_plagiarismsearchr;
   DROP TABLE mdl_plagiarism_plagiarismsearchc;
   ```
4. Rename the tables:
   ```sql
   RENAME TABLE
       mdl_plagiarism_ps_reports TO mdl_plagiarism_plagiarismsearchr,
       mdl_plagiarism_ps_config  TO mdl_plagiarism_plagiarismsearchc;
   ```
5. Run the upgrade again (*Site administration > Notifications* or `php admin/cli/upgrade.php`).
   The step finds that the old tables no longer exist, skips the rename, and finishes the upgrade.
6. Purge caches: *Site administration > Development > Purge all caches* or `php admin/cli/purge_caches.php`.
