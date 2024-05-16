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

function seed_block_user_forms_form_fields_first_time_registration($DB)
{
    $data = [];

    //Student registration:
    $data[] = [
        'form_id' => 2,
        'field_id' => 18,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 19,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 20,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 21,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 22,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 5,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 6,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 7,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 8,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 9,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 10,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 11,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 12,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 13,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 14,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 15,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 16,
    ];
    $data[] = [
        'form_id' => 2,
        'field_id' => 17,
    ];

    $table = 'block_user_forms_form_fields';
    foreach ($data as $record) {
        $DB->insert_record($table, (object)$record);
    }
}

function seed_block_user_forms_fields_first_time_registration($DB)
{
    $data = [];

    //Tuition Provider (select)
    $data[] = [
        'label_lang' => 'select_lk_tuition_provider',
        'field_type_id' => 5, //OPTION
    ];

    //Venue (select)
    $data[] = [
        'label_lang' => 'select_lk_venue',
        'field_type_id' => 5, //OPTION
    ];

    $data[] = [
        'label_lang' => 'form2_upload_cert_id',
        'field_type_id' => 2, //FILE
    ];

    $data[] = [
        'label_lang' => 'form2_upload_cert_highest_qualification',
        'field_type_id' => 2, //FILE
    ];

    $table = 'block_user_form_fields';

    foreach ($data as $record) {
        $DB->insert_record($table, (object)$record);
    }

}