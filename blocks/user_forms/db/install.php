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

require_once('seeds/first_time_exam_registration.php');
function xmldb_block_user_forms_install()
{
    global $DB;

    seed_block_user_forms($DB);
    seed_block_user_forms_field_types($DB);

    //Student Registration
    seed_block_user_forms_fields_student_registration($DB);
    seed_block_user_forms_form_fields_student_registration($DB);

    //first time registration
    seed_block_user_forms_fields_first_time_registration($DB);
    seed_block_user_forms_form_fields_first_time_registration($DB);

    run_upgrade_functions();

}

function seed_block_user_forms_form_fields_student_registration($DB)
{
    $data = [];

    //Student registration:
    $data[] = [
        'form_id' => 1,
        'field_id' => 1,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 2,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 3,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 4,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 5,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 6,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 7,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 8,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 9,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 10,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 11,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 12,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 13,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 14,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 15,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 16,
    ];
    $data[] = [
        'form_id' => 1,
        'field_id' => 17,
    ];

    $table = 'block_user_forms_form_fields';
    foreach ($data as $record) {
        $DB->insert_record($table, (object)$record);
    }
}

function seed_block_user_forms($DB)
{
    $data = [];

    $data[] = [
        'title_lang' => 'student_registration_title',
        'heading_lang' => 'student_registration_heading',
    ];
    $data[] = [
        'title_lang' => 'first_time_exam_registration_title',
        'heading_lang' => 'first_time_exam_registration_heading',
    ];
    $data[] = [
        'title_lang' => 'application_for_exemption_title',
        'heading_lang' => 'application_exemption_registration_heading'
    ];

    $table = 'block_user_forms';

    foreach ($data as $record) {
        $DB->insert_record($table, (object)$record);
    }
}

function seed_block_user_forms_field_types($DB)
{
    $data = [];

    $data[] = [
        'type_name' => 'CHECKBOX',
        'data_type' => 'TINYINT',
    ];

    $data[] = [
        'type_name' => 'FILE',
        'data_type' => 'INT',
    ];

    $data[] = [
        'type_name' => 'RADIOBOX',
        'data_type' => 'TINYINT',
    ];

    $data[] = [
        'type_name' => 'TEXT',
        'data_type' => 'VARCHAR',
    ];

    $data[] = [
        'type_name' => 'SELECT',
        'data_type' => 'INT',
    ];

    $table = 'block_user_form_field_types';
    foreach ($data as $record) {
        $DB->insert_record($table, (object)$record);
    }
}

function seed_block_user_forms_fields_student_registration($DB)
{
    $data = [];

    $data[] = [
        'label_lang' => 'upload_cert_id',
        'field_type_id' => 2, //FILE
    ];

    $data[] = [
        'label_lang' => 'upload_cert_highest_schooling',
        'field_type_id' => 2, //FILE
    ];

    $data[] = [
        'label_lang' => 'upload_cert_highest_education',
        'field_type_id' => 2, //FILE
    ];

    $data[] = [
        'label_lang' => 'upload_cert_marriage',
        'field_type_id' => 2, //FILE
    ];

    $data[] = [
        'field_type_id' => 3, //RADIOBOX
        'label_lang' => 'investigated_charged_dishonesty',
    ];

    $data[] = [
        'field_type_id' => 3, //RADIOBOX
        'label_lang' => 'estate_sequestrated',
    ];

    $data[] = [
        'field_type_id' => 3, //RADIOBOX
        'label_lang' => 'party_arrangement_compromise_creditors',
    ];

    $data[] = [
        'field_type_id' => 3, //RADIOBOX
        'label_lang' => 'guilty_disciplinary_proceedings',
    ];

    $data[] = [
        'field_type_id' => 3, //RADIOBOX
        'label_lang' => 'barred_professional',
    ];

    $data[] = [
        'field_type_id' => 3, //RADIOBOX
        'label_lang' => 'civil_judgements',
    ];

    $data[] = [
        'field_type_id' => 3, //RADIOBOX
        'label_lang' => 'litigation_professional_capacity',
    ];

    $data[] = [
        'field_type_id' => 3, //RADIOBOX
        'label_lang' => 'allegations_professional_capacity',
    ];

    $data[] = [
        'field_type_id' => 3, //RADIOBOX
        'label_lang' => 'office_trust_grounds_misconduct',
    ];

    /*$data[] = [
        'field_type_id' => 3, //RADIOBOX
        'label_lang' => 'yes_institute_supporting_doc',
    ];*/

    $data[] = [
        'field_type_id' => 1, //CHECKBOX
        'label_lang' => 'certify_answers_correct',
    ];

    $data[] = [
        'field_type_id' => 1, //CHECKBOX
        'label_lang' => 'read_student_handbook',
    ];

    $data[] = [
        'field_type_id' => 1, //CHECKBOX
        'label_lang' => 'read_student_policies',
    ];

    $data[] = [
        'field_type_id' => 1, //CHECKBOX
        'label_lang' => 'agree_terms_cgisa',
    ];

    $table = 'block_user_form_fields';

    foreach ($data as $record) {
        $DB->insert_record($table, (object)$record);
    }

}

function run_upgrade_functions() {
    global $DB;
    $dbman = $DB->get_manager();

    $table = new xmldb_table('block_user_forms_modules');
            
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
            
    $table->add_field('identifier',XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL,null, null);
            
    $table->add_field('code', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
            
    $table->add_field('description', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
            
    $table->add_field('programme', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
            
    $table->add_field('is_active', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL,null, 0, null);

    $table->add_field('date_modified', XMLDB_TYPE_DATETIME, null, null, XMLDB_NOTNULL,null, 'NOW()', null);

    $table->add_field('date_created', XMLDB_TYPE_DATETIME, null, null, XMLDB_NOTNULL, null, 'NOW()', null);
    $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
    // Add fields and keys to the table.
    
    // Create the table.

    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    $table = new xmldb_table('block_user_forms_sessions');
            
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
            
    $table->add_field('month', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
            
    $table->add_field('year', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
            
    $table->add_field('is_active', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL,null, 0, null);

    $table->add_field('date_modified', XMLDB_TYPE_DATETIME, null, null, XMLDB_NOTNULL,null, 'NOW()', null);

    $table->add_field('date_created', XMLDB_TYPE_DATETIME, null, null, XMLDB_NOTNULL, null, 'NOW()', null);
    $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
    // Add fields and keys to the table.
    
    // Create the table.

    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    insert_new_form_field('the_registration_period_for_the', 2, 5/*select*/);

}

function insert_new_form_field(string $label_lang, int $form_id, int $field_type_id) {

    global $DB;
    $label_exists = $DB->get_record('block_user_form_fields', ['label_lang'=> $label_lang, 'field_type_id' => $field_type_id]);
    if($label_exists){
        $data_form_connect = [
            'form_id' => $form_id,
            'field_id' => $label_exists->id,
        ];
        $form_field_exists = $DB->record_exists('block_user_forms_form_fields', ['form_id'=> $form_id, 'field_id' => $label_exists->id]);
        if(!$form_field_exists){
            $DB->insert_record('block_user_forms_form_fields', (object)$data_form_connect);
        }
        
        return;
    }

    $data_label = [
        'label_lang' => $label_lang,
        'field_type_id' => $field_type_id, 
    ];

    $id = $DB->insert_record('block_user_form_fields', (object)$data_label);

    $data_form_connect = [
        'form_id' => $form_id,
        'field_id' => $id,
    ];

    $form_field_exists = $DB->record_exists('block_user_forms_form_fields', ['form_id'=> $form_id, 'field_id' => $id]);
    if(!$form_field_exists){
        $DB->insert_record('block_user_forms_form_fields', (object)$data_form_connect);
    }
}