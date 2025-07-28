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
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('adminrestorecourseforuser',
    'tool_my_external_backup_restore_courses'));

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
    $DB->insert_record('block_external_backuprestore', $data);
    echo $OUTPUT->box_start('my_external_backup_restore_courses_restorecourseforuser_success');
    echo html_writer::tag('span', get_string('my_external_backup_restore_courses_restorecourseforuser_success',
        'block_my_external_backup_restore_courses'), ['class' => 'table-success']);
    echo $OUTPUT->box_end();
}
$restorecourseforuserform->display();

echo $OUTPUT->footer();
