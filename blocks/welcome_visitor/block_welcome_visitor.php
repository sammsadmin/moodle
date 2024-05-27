<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Block definition class for the block_welcome_visitor plugin.
 *
 * @package   block_welcome_visitor
 * @copyright 2024, dmsadmin@chartgov.co.za
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_welcome_visitor extends block_base {

    /**
     * Initialises the block.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_welcome_visitor');
    }

    /**
     * Tells Moodle that there are configuration settings.
     */
    function has_config(){
        return true;
    }

    /**
     * Gets the block contents.
     *
     * @return string The block HTML.
     */
    function get_content() {
        global $DB;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $content = '';

        $showcourses = get_config('block_welcome_visitor', 'showcourses');

        if ($showcourses) {
            $courses = $DB->get_records('course');
            foreach ($courses as $course) {
                $content .= $course->fullname . '(' . $course->shortname . ')' . '<br/>';
            }    
        } else {
            $users = $DB->get_records('user');
            foreach ($users as $user) {
                $content .= $user->firstname . ' ' . $user->lastname . '<br/>';
            }
        }

        $this->content = new stdClass;
        $this->content->text = $content;
        $this->content->footer = 'This is the Footer';

        return $this->content;
    }

    /**
     * Defines in which pages this block can be added.
     *
     * @return array of the pages where the block can be added.
     */
    public function applicable_formats() {
        return [
            'admin' => false,
            'site-index' => true,
            'course-view' => true,
            'mod' => false,
            'my' => true,
        ];
    }
}