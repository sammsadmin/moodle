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

function local_maintain_information_myprofile_navigation(core_user\output\myprofile\tree $tree, $user, $iscurrentuser, $course){
    echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/maintain_information/css/style.css">';
    $category = new core_user\output\myprofile\category('local_maintain_information', get_string('pluginname', 'local_maintain_information'), null);
    $url = new moodle_url('/local/maintain_information/');
    $string = get_string('title', 'local_maintain_information');
    $node = new core_user\output\myprofile\node('local_maintain_information', 'local_maintain_information', $string, null, $url);
    $category->add_node($node);
    $tree->add_category($category);
    return ($tree);
}

function has_config() {
    return true;
}