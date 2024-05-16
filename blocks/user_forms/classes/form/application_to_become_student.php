<?php
//     Copyright (C) <2019>  <TTRO>
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, either version 3 of the License, or
//     (at your option) any later version.
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <https://www.gnu.org/licenses/>.
/**
 *
 * @package    User forms block
 * @copyright  TTRO <developer@ttro.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once("$CFG->libdir/formslib.php");
require_once('../classes/model/form_model.php');

class application_to_become_student extends moodleform
{
    public $model;

    public $user_id;

    public function __construct($model, $user_id)
    {
        $this->user_id = $user_id;
        $this->model = $model;

        parent::__construct();
    }

    public function definition()
    {
        global $DB;

        $build_field_data = $this->model->get_build_form_fields($DB);
        $mform = $this->_form;
        //$filemanageropts = $this->_customdata['filemanageropts'];
        $mform->addElement('hidden', 'user_id', $this->user_id);
        $mform->setType('user_id', PARAM_INT);
        $mform->addElement('html', '<div class="custom-html">' . get_string('student_registration_title',
                'block_user_forms') . '</div><br>' . get_string('student_registration_heading',
                'block_user_forms'));

        foreach ($build_field_data as $fd) {
            switch ($fd->field_type) {
                case 'CHECKBOX':
                    $mform->addElement('advcheckbox', $fd->field_label, get_string($fd->field_label, 'block_user_forms'), null);
                    if (isset($fd->field_value)) {
                        $mform->setDefault($fd->field_label, $fd->field_value);
                    }
                    $mform->addRule($fd->field_label, get_string('required'), 'required', null, 'client');
                    break;
                case 'FILE':
                    $filemanageroptions = array();
                    $filemanageroptions['accepted_types'] = array('.pdf');
                    $filemanageroptions['maxbytes'] = 0;
                    $filemanageroptions['maxfiles'] = 1;
                    $filemanageroptions['subdirs'] = 0;

                    $mform->addElement('filemanager', $fd->field_label, get_string($fd->field_label, 'block_user_forms'), null, $filemanageroptions);
                    $mform->addRule($fd->field_label, get_string('required'), 'required', null, 'client');
                    if ($fd->field_id == 4) {
                        $mform->addElement('html', '<div class="custom-html">' . get_string('declaration',
                                'block_user_forms') . '</div><br>' . get_string('declaration_applicant_acknowledge_conditions',
                                'block_user_forms'));
                    }

                    break;

                case 'RADIOBOX':
                    $mform->addElement('html', '<div class="yes-no-div">');
                    $radioarray = array();
                    $radioarray[] = $mform->createElement('radio', $fd->field_label, '', get_string('yes'), 1);
                    $radioarray[] = $mform->createElement('radio', $fd->field_label, '', get_string('no'), 0);
                    $mform->addGroup($radioarray, $fd->field_label, get_string($fd->field_label, 'block_user_forms'), array(' ', ' '), false);
                    $mform->addElement('html', '</div>');

                    if (isset($fd->field_value)) {
                        $mform->setDefault($fd->field_label, $fd->field_value);
                    }

                    if ($fd->field_id == 13) {
                        $mform->addElement('html', '<div class="custom-html"><strong>' . get_string('answered_any_above_doc_provide',
                                'block_user_forms') . '</strong></div><br>');
                    }

                    break;
                case 'TEXT':
                    break;
                case 'OPTION':
                    //Lookup?
                    break;
                default:
                    //Throw error
            }
        }
        if (!$this->model->check_submit($DB)) {
            $mform->addElement('submit', 'submitbutton', 'Submit');
        } else {
            /*if (is_siteadmin()) {
                if (!$this->model->approved) {
                    $mform->addElement('submit', 'submitbutton', 'Approve');
                } else {
                    $mform->addElement('html', '<div class="block-user-forms-approved"><strong>Approved</strong>
</div>');
                }
            }*/
        }
    }

    public function validation($data, $files)
    {
        $fields = $this->model->get_type_db_form('CHECKBOX');
        $errors = [];
        // Perform additional form validation here if needed.
        foreach ($fields as $field) {
            if (empty($data[$field['field_label']])) {
                $errors[$field['field_label']] = get_string('checkboxrequired', 'block_user_forms');
            }
        }

        return $errors;
    }

}