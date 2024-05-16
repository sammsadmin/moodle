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
if ($ADMIN->fulltree) {
    //--- settings ------------------------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('block_user_forms_settings', '', get_string('pluginname_desc', 'block_user_forms')));
    $settings->add(new admin_setting_configtext('block_user_forms/admin_emails', get_string('admin_emails', 'block_user_forms'), get_string('admin_emails_desc', 'block_user_forms'), '', PARAM_RAW));
    $settings->add(new admin_setting_configtext('block_user_forms/endpoint_url', get_string('endpoint_url', 'block_user_forms'), get_string('endpoint_url_desc', 'block_user_forms'), '', PARAM_RAW));
    $settings->add(new admin_setting_configtext('block_user_forms/may_exam_reg_endate', get_string('may_exam_reg_endate', 'block_user_forms'), get_string('may_exam_reg_endate_desc', 'block_user_forms'), '', PARAM_RAW));
    $settings->add(new admin_setting_configtext('block_user_forms/oct_exam_reg_endate', get_string('oct_exam_reg_endate', 'block_user_forms'), get_string('oct_exam_reg_endate_desc', 'block_user_forms'), '', PARAM_RAW));
}