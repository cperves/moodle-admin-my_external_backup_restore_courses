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
 *  Admin page to program course restoration for a user.
 *
 * @package   tool_my_external_backup_restore_courses
 * @copyright  2025 Université de Strasbourg  {@link http://unistra.fr}
 * @author Celine Perves <cperves@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_reportbuilder\external\conditions\reset;
use core_reportbuilder\local\filters\user;
use core_reportbuilder\system_report_factory;
use tool_my_external_backup_restore_courses\reportbuilder\local\systemreports\course_restoration_tasks;

require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot.'/blocks/my_external_backup_restore_courses/locallib.php');
require_once($CFG->dirroot.'/admin/tool/my_external_backup_restore_courses/restorecourseforuser_form.php');
$sytemcontext = context_system::instance();
require_login();
require_capability('tool/my_external_backup_restore_courses:restore_course_for_user', $sytemcontext);
$PAGE->set_context($sytemcontext);
$PAGE->set_url(new moodle_url('/admin/tool/my_external_backup_restore_courses/restorecourseforuser.php', []));
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('adminrestorecourseforuser', 'tool_my_external_backup_restore_courses'));
$PAGE->set_heading(get_string('adminrestorecourseforuser', 'tool_my_external_backup_restore_courses'));
$PAGE->requires->js(new moodle_url('/admin/tool/my_external_backup_restore_courses/module.js'));

$restorecourseforuserform = new block\my_external_backup_restore_courses\admin\restorecourseforuser_form();

if ($data = $restorecourseforuserform->get_data()) {
    $externalmoodles = block_my_external_backup_restore_courses_tools::get_external_moodles_url_token();
    // Check course exists and retrieve course name.
    $data->externalcoursename =
        block_my_external_backup_restore_courses_tools::external_backup_course_name(
            $data->externalmoodleurl,
            $data->externalcourseid);
    if (!empty($data->userid)) {
        $user = $DB->get_record('user', ['id' => $data->userid]);
        if (!$user) {
            throw new moodle_exception("user $data->userid does not exists");
        }
    }
    $data->status = block_my_external_backup_restore_courses_tools::STATUS_SCHEDULED;
    $data->timecreated = time();
    $data->restoredby = $USER->id;
    $DB->insert_record('block_external_backuprestore', $data);
    redirect(new moodle_url('/admin/tool/my_external_backup_restore_courses/restorecourseforuser.php'));
}
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('adminrestorecourseforuser',
    'tool_my_external_backup_restore_courses'));
$restorecourseforuserform->display();
$report = system_report_factory::create(course_restoration_tasks::class, context_system::instance());
if(!has_capability('moodle/site:config', $sytemcontext)) {
    $report->add_base_condition_simple('restoredby', $USER->id);
}
echo $report->output();
echo $OUTPUT->footer();
