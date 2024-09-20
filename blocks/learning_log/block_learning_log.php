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
 * Block todo is defined here.
 *
 * @package    block_learning_log
 * @author     2024 Gerald O'Sullivan <gerald@archton.io>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_learning_log extends block_base
{
    public function init()
    {
        $this->title = get_string('blocktitle', 'block_learning_log');
    }

    public function get_content()
    {
        global $DB, $USER, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        // Fetch learning log records for the current user
        $records = $DB->get_records('learning_log', ['userid' => $USER->id]);
        foreach ($records as &$record) {
            $record->event_date_formatted = date('Y-m-d',strtotime($record->event_date));
            $record->editurl = new \moodle_url('/blocks/learning_log/edit.php', ['id' => $record->id]);
            $record->deleteurl = new \moodle_url('/blocks/learning_log/delete.php', ['id' => $record->id]);
        }

        $data['records'] = array_values($records);
        $data['addurl'] = new moodle_url('/blocks/learning_log/add.php');
        $this->content->text = $OUTPUT->render_from_template('block_learning_log/learning_log', $data);

        return $this->content;
    }

    public function applicable_formats() {
        return ['all' => true];
    }
}
