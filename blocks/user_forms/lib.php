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

require_once('classes/model/form_model.php');
function block_user_forms_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array())
{
    global $DB;

    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }

    require_login();

    /*if ($filearea != 'attachment') {
        return false;
    }*/

    $itemid = (int)array_shift($args);

    if ($itemid != 0) {
        return false;
    }

    $fs = get_file_storage();

    $filename = array_pop($args);
    if (empty($args)) {
        $filepath = '/';
    } else {
        $filepath = '/' . implode('/', $args) . '/';
    }

    $form_model = new form_model(1);
    $fields = $form_model->get_all_labels();

    foreach ($fields as $field) {
        if ($field == $filearea) {
            $file = $fs->get_file($context->id, $field, $filearea, $itemid, $filepath, $filename);
            if (!$file) {
                return false;
            }

            // finally send the file
            send_stored_file($file, 0, 0, true, $options);
        }
    }
    // download MUST be forced - security!
}

