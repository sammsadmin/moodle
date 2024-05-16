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
class lookup_table
{

    public function first_time_exam_registration()
    {
        global $DB;

        $providers = $DB->get_records('block_user_forms_tuition', array(), 'name ASC');
        $venues = $DB->get_records('block_user_forms_venues', array(), 'name ASC');
        $sessions = $this->get_db_sessions();

        return [
            'tuition_providers' => $this->make_options($providers),
            'venues' => $this->make_options($venues),
            'sessions' => $this->make_options_sessions($sessions),
        ];
    }

    public function get_db_sessions(){
        global $DB;
        $year = date('Y');

        $sessions = $DB->get_records('block_user_forms_sessions', ['year' => $year, 'is_active' => 1]);
        if(!$sessions){
            $sessions = $DB->get_records('block_user_forms_sessions', ['year' => $year + 1, 'is_active' => 1]);
            if(count($sessions) == 1){
                $sessions = $DB->get_records('block_user_forms_sessions', ['year' => $year + 1]);
            } 
        }

        if(count($sessions) == 1){
            $sessions = $DB->get_records('block_user_forms_sessions', ['year' => $year]);
        }

        return $sessions;
    }

    public function make_options($data_id_name): array
    {
        $id_name_array = [];

        foreach ($data_id_name as $id_name) {
            $id_name_array[$id_name->id] = $id_name->name;
        }

        return $id_name_array;
    }

    public function make_options_sessions($data_id_name): array
    {
        $id_name_array = [];
        $count = 1;
        foreach ($data_id_name as $id_name) {
            if($id_name->is_active == 0){
                $id_name_array[$count . '-' . $id_name->year . '-disabled'] = $id_name->month;
                ++$count;
                continue;
            }
            $id_name_array[$count . '-' . $id_name->year] = $id_name->month;
            ++$count;
        }

        return $id_name_array;
    }
}