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
class first_time_exam_registration_model
{

    public $tag;
    public $exam_period;

    public $closing_date;

    public $user_id;

    public array $errors;

    public $submission_date;

    public $may_end_date;

    public $november_end_date;

    public $year;

    public function __construct($submission_date = null)
    {
        //$current_date = new DateTime();
        //$may_end_date = new DateTime('May 31');
        //$november_end_date = new DateTime('November 30');

        $this->submission_date = new DateTime($submission_date) ?? new DateTime();
        $this->year = $this->submission_date->format("Y");

        $this->may_end_date = new DateTime($this->year . '-05-31');
        $this->november_end_date = new DateTime($this->year . '-11-30');

        $this->set_tag_to_available_period_and_closest();
    }

    public function check_if_user_has_registered_before()
    {
    }

    /*
     * SELECT c.id AS course_id, c.fullname AS course_name, c.shortname AS course_short_name
FROM mdl_course AS c
JOIN mdl_tag_instance AS ti ON ti.itemid = c.id
JOIN mdl_tag AS t ON t.id = ti.tagid
WHERE t.name = :tag
AND c.id IN (
    SELECT DISTINCT c.id
    FROM mdl_user u
    INNER JOIN mdl_user_enrolments ue ON u.id = ue.userid
    INNER JOIN mdl_enrol e ON ue.enrolid = e.id
    INNER JOIN mdl_course c ON e.courseid = c.id
    WHERE u.id = :user_id
);
     */
    /*public function get_first_time_reg_modules()
    {
        global $DB;

        $sql = "SELECT c.id AS course_id, c.fullname AS course_name, c.shortname AS course_short_name 
FROM mdl_course AS c
JOIN mdl_tag_instance AS ti ON ti.itemid = c.id
JOIN mdl_tag AS t ON t.id = ti.tagid
WHERE t.name = :tag";

        $params = array('tag' => $this->tag);

        $courses = $DB->get_records_sql($sql, $params);

        if ($courses) {
            return $courses;
        } else {
            // No courses found with the specified tag.
            return null;
        }
    }*/

    public function get_first_time_reg_modules_2()
    {
        global $DB;

        $sql = "SELECT *
FROM {block_user_forms_modules} 
WHERE is_active = :is_active";

        $params = array('is_active' => 1);

        $courses = $DB->get_records_sql($sql, $params);

        if ($courses) {
            return $courses;
        } else {
            // No courses found with the specified tag.
            return null;
        }
    }

    public function get_selected_modules($submit_id)
    {
        global $DB;

        $sql = "SELECT course_id FROM {block_user_form_exam_modules} WHERE submit_id = :submit_id";

        $params = array('submit_id' => $submit_id);

        $selected_modules = $DB->get_records_sql($sql, $params);

        if ($selected_modules) {
            $return_arr = [];
            foreach ($selected_modules as $item) {
                $return_arr[] = $item->course_id;
            }
            return $return_arr;
        } else {
            // No courses found with the specified tag.
            return [];
        }
    }

    public function insert_modules_for_submission($course_ids, $submit_id)
    {
        global $DB;
        $build_insert = [];

        foreach ($course_ids as $course_id) {
            $build_insert[] = [
                'course_id' => $course_id,
                'submit_id' => $submit_id,
            ];
        }

        try {
            $DB->insert_records('block_user_form_exam_modules', $build_insert);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage() . $e->getTraceAsString();
        }
    }

    public function set_tag_to_available_period_and_closest()
    {
        $current_date = new DateTime();
        //$may_end_date = new DateTime('May 31');
        $november_end_date = new DateTime('November 30');

        $current_year = date("Y");
        $next_year = $current_year + 1;

        if ($current_date > $november_end_date) {
            //Next year
            $this->tag = 'exam_period_june_' . $next_year;
            $this->exam_period = 'June ' . $next_year;
            $this->closing_date = '31 May ' . $next_year;
            return;
        }

        if ($this->submission_date > $this->may_end_date && $this->submission_date < $this->november_end_date) {
            $this->tag = 'exam_period_december_' . $this->year;
            $this->exam_period = 'December  ' . $this->year;
            $this->closing_date = '30 November ' . $this->year;
        } else {
            $this->tag = 'exam_period_june_' . $this->year;
            $this->exam_period = 'June ' . $this->year;
            $this->closing_date = '31 May ' . $this->year;
        }
    }
}