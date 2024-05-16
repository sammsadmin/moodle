<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_maintain_information
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_maintain_information\task;

defined('MOODLE_INTERNAL') || die();

class update_nationality extends \core\task\scheduled_task {
    public function get_name() {
        // Shown on admin screens
        return get_string('update_nationality', 'local_maintain_information'); //get the string from lang/en/ 
    }

    public function execute() {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/local/maintain_information/locallib.php');
        update_profile_field_menus('nationality'); //function to execute
    }

}