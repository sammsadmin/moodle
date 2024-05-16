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

namespace block_user_forms\task;
defined('MOODLE_INTERNAL') || die();

class update_available_modules extends \core\task\scheduled_task
{
    public function get_name()
    {
        
        // Shown on admin screens
        return get_string('update_available_modules', 'block_user_forms'); //get the string from lang/en/
    }

    public function execute()
    {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/blocks/user_forms/locallib.php');
        update_DB_modules(); //function to execute
    }
}