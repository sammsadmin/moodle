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

/**
 * 'form_id' => 2 First time exam registration
 */
function xmldb_block_user_forms_upgrade($oldversion): bool {
    global $CFG, $DB;
    
    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    if ($oldversion < 2023092920) {
        // Define the fields
        if (!$dbman->table_exists('block_user_forms_modules')) {
            // Define the table structure using the format you provided.
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
        }
    }

    if ($oldversion < 2023092920) {
        insert_new_form_field('the_registration_period_for_the', 2, 5/*select*/);
    }

    if ($oldversion < 2023092942) {
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
    }

    // Everything has succeeded to here. Return true.
    return true;
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