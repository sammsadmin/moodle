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

defined('MOODLE_INTERNAL') || die();

$tasks = [
     [
          'classname' => '\local_maintain_information\task\update_nationality',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
     [
          'classname' => '\local_maintain_information\task\update_address_country',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
     [
          'classname' => '\local_maintain_information\task\update_address_province',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
     [
          'classname' => '\local_maintain_information\task\update_postal_country',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
     [
          'classname' => '\local_maintain_information\task\update_postal_province',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
     [
          'classname' => '\local_maintain_information\task\update_education_country',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
     [
          'classname' => '\local_maintain_information\task\update_education_province',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
     [
          'classname' => '\local_maintain_information\task\update_population_group',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
     [
          'classname' => '\local_maintain_information\task\update_employment_job_title',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
     [
          'classname' => '\local_maintain_information\task\update_employment_industry',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
     [
          'classname' => '\local_maintain_information\task\update_home_language',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
     [
          'classname' => '\local_maintain_information\task\update_gender',
          'blocking' => 0,
          'minute' => '*',
          'hour' => '1',
          'day' => '*',
          'dayofweek' => '*', 
          'month' => '*'
     ],
];