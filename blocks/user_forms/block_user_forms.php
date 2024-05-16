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

class block_user_forms extends block_base
{

    /**
     * Initializes class member variables.
     */
    public function init()
    {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_user_forms');
    }

    function has_config()
    {
        return true;
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content()
    {
        global $USER;
        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        } else {
            require_once('classes/model/form_model.php');

            $form_model_check_student = (new form_model(2, $USER->id))->forms_order_access();
            // Define an array of options for the dropdown.
            $new_student_file_url = new moodle_url('/blocks/user_forms/view_forms/application_to_become_student.php?uid=' . $USER->id);

            $first_time_exam_registration = new moodle_url('/blocks/user_forms/view_forms/first_time_exam_registration.php?uid=' . $USER->id);
            //$see_summitted = new moodle_url('/blocks/user_forms/view_forms/first_time_exam_registration.php');

            $form_links = '<a href="' . $new_student_file_url . '">New Student Registration</a><br>';

            if ($form_model_check_student) {
                $form_links .= '<a href="' . $first_time_exam_registration . '">First Time Exam Registration</a>';
            }

            $text = $form_links;
            $this->content->text = $text;
        }

        return $this->content;
    }
}
