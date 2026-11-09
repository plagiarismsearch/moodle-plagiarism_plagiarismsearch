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
