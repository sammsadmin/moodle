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
require_once('../classes/model/first_time_exam_registration_model.php');
require_once('../classes/external/lookup_table.php');

class first_time_exam_registration extends moodleform
{

    public $model;
    public $first_time_registration_model;

    public $lookups;

    public $user_id;

    public function __construct($model, $user_id)
    {
        global $DB;
        $this->user_id = $user_id;
        $this->model = $model;
        $submission = $this->model->check_submit($DB);
        if ($submission) {
            $this->first_time_registration_model = new first_time_exam_registration_model($submission->date_created);
        } else {
            $this->first_time_registration_model = new first_time_exam_registration_model();
        }

        $this->first_time_registration_model = new first_time_exam_registration_model();
        $this->lookups = (new lookup_table())->first_time_exam_registration();

        parent::__construct();
    }

    public function definition()
    {
        global $DB;

        $mform = $this->_form;

        $year = date('Y');
        $month = 'May';

        $build_field_data = $this->model->get_build_form_fields($DB);

        $sessions = (new lookup_table())->get_db_sessions();
        $sessions_arr = [];
        foreach ($sessions as $session) {
            //var_dump($session);
            $sessions_arr[] = $session->month . ' ' . $session->year;
            $year = $session->year;
        }
        
        function compareDateStrings($a, $b) {
            $dateA = date_create_from_format('F Y', $a);
            $dateB = date_create_from_format('F Y', $b);
            
            if (!$dateA || !$dateB) {
                // Handle invalid date formats
                return 0;
            }
            
            return $dateA <=> $dateB;
        }

        // Sort the array using the custom comparison function
        usort($sessions_arr, 'compareDateStrings');

        $select_val_increment = 1;
        $pre_select_index = 0;

        foreach ($sessions_arr as $session) {
            foreach($build_field_data as $fd){
                if(isset($fd->field_value) && $fd->field_type == 'SELECT' && $fd->field_id == 22){
                    $pos = strpos($fd->field_value, '-');

                    if ($pos !== false) {
                        $year = substr($fd->field_value, $pos + 1);
                        $month_num = explode('-', $fd->field_value)[0];

                        if($month_num == 2){
                            $pre_select_index = $select_val_increment - 1;
                        }
                    }
                    continue;
                }
            }
            ++$select_val_increment; 
        }
        
        $blockconfig = get_config('block_user_forms');
        
        if($pre_select_index == 1){
            $init_endate = $blockconfig->oct_exam_reg_endate . ' ' . $year;
        }else{
            $init_endate = $blockconfig->may_exam_reg_endate . ' ' . $year;
        }

        $first_exam_reg_endate = $blockconfig->may_exam_reg_endate . ' ' . $year;
        $second_exam_reg_endate = $blockconfig->oct_exam_reg_endate . ' ' . $year;
        
        $mform->addElement('hidden', 'user_id', $this->user_id);
        $mform->setType('user_id', PARAM_INT);
        $mform->addElement('html', '<div class="custom-html"><strong>');

        foreach($build_field_data as $fd){
            if($fd->field_type == 'SELECT' && $fd->field_id == 22){
                
                $sessions = $this->lookups['sessions'];
                $select = $mform->addElement('select', $fd->field_label, get_string('first_time_exam_registration_top_heading', 'block_user_forms'), $sessions);

                if (isset($fd->field_value)) {
                    $select->setSelected($fd->field_value);
                }
                $count = 0;
                foreach ($sessions as $key => $value) {
                    
                    if (strpos($key, '-disabled') !== false) {

                        $javascript = <<<EOD
                        <script type="text/javascript">
                            document.addEventListener('DOMContentLoaded', function() {
                                var selectElement = document.getElementById("id_{$fd->field_label}");
                
                            if (selectElement) {
                                var optionToDisable = selectElement.querySelector('option[value="{$key}"]');
                
                                if (optionToDisable) {
                                    optionToDisable.disabled = true;
                                }
                            }
                            });
                        </script>
                        EOD;
                
                    $mform->addElement('static', 'javascript', '', $javascript);
                    if($count == 0){
                        next($sessions);
                        $nextKey = key($sessions);
                        $select->setSelected($nextKey);
                        $init_endate = $blockconfig->oct_exam_reg_endate . ' ' . $year;
                        $pre_select_index = 1;
                    }
                    
                    }
                    ++$count;
                }
                break;
            }
        }

        $mform->addElement('html', '</strong>');

        $mform->addElement('html', '<br>' . get_string('the_registration_period_for_the',
                'block_user_forms') . ' ' . ' <span id="registration_period">' . $sessions_arr[$pre_select_index] . '</span> ' . get_string('exam_session_is_now_open',
                'block_user_forms') . '<br>' . get_string('closing_date_registration','block_user_forms') . '<span id="registration_period_close">' . $init_endate . '</span>' . '<br>' . get_string('please_mark_course_modules_checkboxes_below',
                'block_user_forms') . '<br>' . get_string('once_paid_required_fees_process_heading_text',
                'block_user_forms') . '<br>' . get_string('note_will_not_able_access_papers_not_paid_full_heading_text',
                'block_user_forms') . '</div>');

        $javascript = <<<EOD
                <script type="text/javascript">
                    document.getElementById('id_the_registration_period_for_the').addEventListener('change', function() {
                        var selectedValue = this.value;
                        
                        var updateRegistrationPeriod = document.getElementById('registration_period');
                        var updateRegistrationPeriodClose = document.getElementById('registration_period_close');
                        if (selectedValue[0] == '1') {
                            updateRegistrationPeriod.innerHTML  = "{$sessions_arr[0]}";
                            updateRegistrationPeriodClose.innerHTML = "{$first_exam_reg_endate}";
                        } else if (selectedValue[0] == '2') {
                            updateRegistrationPeriodClose.innerHTML = "{$second_exam_reg_endate}";
                            updateRegistrationPeriod.innerHTML  = "{$sessions_arr[1]}";
                            
                        }
                    });
                </script>
                EOD;
                
        $mform->addElement('static', 'javascript', '', $javascript);

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
                case 'SELECT':

                    if ($fd->field_id == 18) {
                        $tuition_providers = $this->lookups['tuition_providers'];

                        $select = $mform->addElement('select', $fd->field_label, get_string($fd->field_label, 'block_user_forms'), $tuition_providers);

                        if (isset($fd->field_value)) {
                            $select->setSelected($fd->field_value);
                        }

                        $courses = $this->first_time_registration_model->get_first_time_reg_modules_2();
                        $mform->addElement('html', '<table class="table"><tr><td>Module</td><td colspan="2">Description</td><td></td></tr>');
                        $selected_mods = [];
                        if ($this->model->current_submit_id != 0) {
                            $selected_mods = $this->first_time_registration_model->get_selected_modules($this->model->current_submit_id);
                        }
                        
                        if(isset($courses) && count($courses) > 0) {
                            foreach ($courses as $course) {
                                $mform->addElement('html', '<tr>');
    
                                $mform->addElement('html', '<td>' . $course->code . '</td>');
                                $mform->addElement('html', '<td>' . $course->description . '(' . $course->programme . ')</td>');
    
                                $mform->addElement('html', '<td>');
                                $mform->addElement('advcheckbox', 'first_exam_|_reg_input_identifier_course_id_' . $course->id, '', null);
                                //print_r(in_array($course->course_id, $selected_mods));
    
                                if (count($selected_mods) > 0 && in_array($course->id, $selected_mods)) {
    
                                    $mform->setDefault('first_exam_|_reg_input_identifier_course_id_' . $course->id, 1);
                                }
    
                                $mform->addElement('html', '</td>');
    
                                $mform->addElement('html', '</tr>');
                            }
                        }
                        
                        $mform->addElement('html', '</table>');
                        // Add the table to the form.
                    } else {
                        if($fd->field_id == 22){
                            break;
                        }
                        $venues = $this->lookups['venues'];
                        $select = $mform->addElement('select', $fd->field_label, get_string($fd->field_label, 'block_user_forms'), $venues);

                        if (isset($fd->field_value)) {
                            $select->setSelected($fd->field_value);
                        }
                    }

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