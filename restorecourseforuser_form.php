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
 * Folder plugin version information
 *
 * @package tool_my_external_backup_restore_courses
 * @copyright  2025 Université de Strasbourg  {@link http://unistra.fr}
 * @author Celine Perves <cperves@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block\my_external_backup_restore_courses\admin;
use backup;
use core\output\html_writer;
use MoodleQuickForm_checkbox;
use MoodleQuickForm_radio;
use MoodleQuickForm_textarea;

defined('MOODLE_INTERNAL') || die();
require_once("$CFG->libdir/formslib.php");

/**
 * Restore course for other user form
 */
class restorecourseforuser_form extends \moodleform {
    /**
     * form definition
     * @return void
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     */
    protected function definition() {
        global $CFG;
        require_once($CFG->dirroot.'/backup/util/includes/backup_includes.php');
        $mform = &$this->_form;
        $staticnoplfelement = html_writer::start_tag('div', ['class' => 'notice'])
            .get_string('noexternalmoodleconnected', 'block_my_external_backup_restore_courses')
            .html_writer::end_tag('div');

        $externalmoodles = \block_my_external_backup_restore_courses_tools::get_external_moodles_url_token();
        if ($externalmoodles && !empty($externalmoodles)) {
            // Choose external plateforms if more that one.
            if (count($externalmoodles) > 1) {
                $radioarray = [];
                foreach ($externalmoodles as $domain => $externalmoodle) {
                    $radioarray[] = $mform->createElement('radio', 'externalmoodleurl', '', $domain, $domain);
                }
                $mform->addGroup($radioarray, 'externalmoodlesarray',
                    get_string('externalmoodleurl', 'block_my_external_backup_restore_courses'),
                    [' '], false);
            } else {
                $mform->addElement('hidden', 'externalmoodleurl', array_keys($externalmoodles)[0]);
                $mform->setType('externalmoodleurl', PARAM_RAW);
                $mform->addElement('static', 'moodleurldesc',
                    get_string('externalmoodleurl', 'block_my_external_backup_restore_courses'),
                    array_keys($externalmoodles)[0]);
            }
            $mform->addElement('text', 'externalcourseid',
                get_string('externalcourseid', 'tool_my_external_backup_restore_courses'));
            $mform->setType('externalcourseid', PARAM_INT);
            $mform->addRule('externalcourseid', get_string('required'),
                'required', null, 'client');
            $mform->addElement('text', 'userid',
                get_string('userid', 'block_my_external_backup_restore_courses'));
            $mform->setType('userid', PARAM_INT);
            $mform->addElement('checkbox', 'internalcategory',
                get_string('keepcategory', 'block_my_external_backup_restore_courses'));
            $mform->addElement('checkbox', 'withuserdatas',
                get_string('withuserdatas', 'block_my_external_backup_restore_courses'));
            $enrolmentmodeoptions = [
                backup::ENROL_NEVER     => get_string('rootsettingenrolments_never', 'backup'),
                backup::ENROL_WITHUSERS => get_string('rootsettingenrolments_withusers', 'backup'),
                backup::ENROL_ALWAYS    => get_string('rootsettingenrolments_always', 'backup'),
            ];
            $mform->addElement('select', 'enrolmentmode',
                get_string('enrolmentmode', 'block_my_external_backup_restore_courses'), $enrolmentmodeoptions);
            $mform->setDefault('enrolmentmode', backup::ENROL_ALWAYS);
            $mform->addElement('submit', 'submit', get_string('planifyrestore',
                'block_my_external_backup_restore_courses'),
                ['onclick' => 'changeEnrolmentModeOptions(-1);']
            );
        } else {
            $mform->addElement('static', 'noexternalmoodles', $staticnoplfelement);

        }
    }
    public function reset(){
        $mform = &$this->_form;
        foreach ($mform->_elements as $element) {
            if ($element instanceof MoodleQuickForm_checkbox
                || $element instanceof MoodleQuickForm_radio
                || $element instanceof MoodleQuickForm_textarea
            ) {
                $element->reset();
            }
        }
    }
}
