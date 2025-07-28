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
 *
 * @package tool_my_external_backup_restore_courses
 * @subpackage setting file
 * @copyright  2025 Université de Strasbourg  {@link http://unistra.fr}
 * @author Celine Perves <cperves@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
$plugin = core_plugin_manager::instance()->get_plugin_info('block_my_external_backup_restore_courses');
$ADMIN->add('root', new admin_category('toolmyexternalbackuprestorecoursesfolder',
    new lang_string('pluginname', 'tool_my_external_backup_restore_courses'),
    )
);

if ($hassiteconfig) {
    // Admin page declaration.
    $ADMIN->add('toolmyexternalbackuprestorecoursesfolder',
        new admin_externalpage(
            'my_external_backup_restore_courses_admin',
            get_string('adminpage', 'tool_my_external_backup_restore_courses'),
            "$CFG->wwwroot/admin/tool/my_external_backup_restore_courses/index.php",
            'moodle/site:config'));
}
$ADMIN->add('toolmyexternalbackuprestorecoursesfolder',
    new admin_externalpage(
        'my_external_backup_restore_courses_restorecourseforuser',
        get_string('adminrestorecourseforuser', 'tool_my_external_backup_restore_courses'),
        "$CFG->wwwroot/admin/tool/my_external_backup_restore_courses/restorecourseforuser.php",
        'tool/my_external_backup_restore_courses:restore_course_for_user'));
