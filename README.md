# my_external_backup_restore_courses admin tools
* This plugin is an extension of block_my_external_backup_restore_courses
  * https://moodle.org/plugins/block_my_external_backup_restore_courses
* It contains all the admin tools for the block plugin

## Installation
* unzip the code in your Moodle admin/tool directory

## Tools
* you'll find the tools under Site Administration -> General -> My external backup restore course admin tools
### Backup/restore task administration tool
* only for roles with admin/site:config 
* administrate the course restoration task
  * change values such as enrolment metho, status, cateogry, ...
### Restore course for user
* for user with role with both capabilities 
  * tool/my_external_backup_restore_courses:restore_course_for_user
  * moodle/site:configview

