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
 * Plugin strings are defined here.
 *
 * @package     local_maintain_information
 * @category    string
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {

    //--- settings ------------------------------------------------------------------------------------------
    $settings = new admin_settingpage('local_maintain_information_settings', get_string('pluginname', 'local_maintain_information'));

    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configtext('local_maintain_information/endpoint_url', get_string( 'endpoint_url', 'local_maintain_information'), get_string('endpoint_url_desc', 'local_maintain_information'), '', PARAM_RAW));

}
