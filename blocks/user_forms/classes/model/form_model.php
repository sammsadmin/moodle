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

global $CFG;

require_once($CFG->dirroot . '/lib/accesslib.php');

class form_model
{
    public int $form_id;
    /**
     * @var int
     */
    public int $user_id;
    /**
     * @var int
     */
    public int $current_submit_id = 0;
    /**
     * @var array
     */
    public array $errors = [];

    public $approved = false;

    public $db_form_fields;

    public $db_form_fields_values;

    public array $allowed_forms_for_user = [];

    public function __construct(int $form_id, $user_id)
    {
        $this->user_id = $user_id;

        $this->form_id = $form_id;

        //$this->set_approved();
    }

    /**
     * @return false|mixed
     * @throws dml_exception
     */
    public function get_form()
    {
        global $DB;

        $sql = "SELECT * from {block_user_forms} where id = :form_id";

        $params = array('form_id' => $this->form_id);

        return $DB->get_record_sql($sql, $params);
    }

    /**
     * @return array|void
     */
    public function get_form_fields()
    {
        global $DB;

        //get field ids

        $sql = "SELECT 
        fff.field_id AS field_id,
        ff.label_lang AS field_label,
        fft.type_name AS field_type FROM {block_user_form_fields} AS ff
        JOIN {block_user_forms_form_fields} AS fff ON fff.field_id = ff.id
        JOIN {block_user_form_field_types} AS fft ON ff.field_type_id = fft.id
        WHERE fff.form_id = :form_id 
        ORDER BY fff.id;";

        $params = array('form_id' => $this->form_id);

        try {
            return $DB->get_records_sql($sql, $params);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage() . $e->getTraceAsString();
        }
    }

    /**
     * @param $DB
     * @return mixed|void
     */
    public function get_field_ids($DB)
    {
        $sql = "SELECT 
        fff.field_id AS field_id
        FROM {block_user_forms_form_fields} AS fff
        WHERE fff.form_id = :form_id";

        $params = array('form_id' => $this->form_id);

        try {
            return $DB->get_records_sql($sql, $params);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage() . $e->getTraceAsString();
        }
    }

    /**
     * @return array|mixed|null
     * @throws dml_exception
     */
    public function get_build_form_fields()
    {
        global $DB;
        return $this->check_submit($DB) ? $this->get_submitted_data($DB) : $this->get_form_fields();
    }

    /**
     * @param $DB
     * @return false|mixed
     */
    public function check_submit($DB)
    {
        $sql = "SELECT *
            FROM {block_user_forms_submit}
            WHERE user_id = :user_id
            AND  form_id = :form_id";

        $params = array('user_id' => $this->user_id, 'form_id' => $this->form_id);

        $check_submit = $DB->get_record_sql($sql, $params);
        if ($check_submit) {
            return $check_submit;
        }
        return false;
    }


    /**
     * @param $DB
     * @return mixed|null
     */
    public function get_submitted_data($DB)
    {

        //TODO NB if not student application form the user should be able to submit form again.
        if ($this->form_id == 1 || $this->form_id == 2) {
            try {
                $check_submit = $this->check_submit($DB);
                if ($check_submit) {
                    $this->current_submit_id = $check_submit->id;

                    return $this->sql_form_submit_val($DB);
                }
            } catch (\Exception $e) {
                $this->errors = $e->getMessage() . $e->getTraceAsString();
            }

        } else if ($this->form_id == 3) {
            //$check_submit = $DB->get_records_sql($sql, $params);
            return null;
        }

        return null;
    }

    /**
     * @param $DB
     * @return mixed|void
     */
    public function sql_form_submit_val($DB)
    {

        $sql = "SELECT ff.id AS field_id, ff.label_lang AS field_label, ufsv.value AS field_value, fft.type_name AS field_type 
        FROM {block_user_form_fields} AS ff 
        LEFT JOIN {block_user_forms_sub_values} AS ufsv ON ff.id = ufsv.field_id 
        LEFT JOIN {block_user_forms_form_fields} AS fff ON fff.field_id = ufsv.field_id
        JOIN {block_user_form_field_types} AS fft ON fft.id = ff.field_type_id 
        AND ufsv.submit_id = :submit_id
        AND fff.form_id = :form_id
        ORDER BY fff.id;";

        $params = [
            'submit_id' => $this->current_submit_id,
            'form_id' => $this->form_id,
        ];

        try {
            return $DB->get_records_sql($sql, $params);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage() . $e->getTraceAsString();
        }
    }

    /**
     * @param $DB
     * @param $data
     * @return null
     * @throws dml_exception
     */
    public function update_create($DB, $data)
    {
        //in future use get_form_fields
        if ($this->get_submitted_data($DB) !== null) {
            //Not used at this point
            //return $this->update_submit($DB, $data);
            return null;
        }
        return $this->create_submit($DB, $data);
    }

    /**
     * @param $DB
     * @param $data
     * @return true|void
     */
    private function update_submit($DB, $data)
    {
        $sql = "UPDATE {block_user_forms_sub_values}
        SET value = :value 
        WHERE submit_id = :submit_id
        AND field_id = :field_id";

        $params = array(
            'value' => $data->file_name_custom,
            'field_id' => 1,
            'submit_id' => $this->current_submit_id
        );

        $success = $DB->execute($sql, $params);

        if ($success) {
            return true;
        } else {
            $this->errors[] = $DB->getErrorMsg();
        }
    }

    /**
     * @param $DB
     * @param $data
     * @return void
     * @throws dml_exception
     */
    private function create_submit($DB, $data)
    {
        //create submit
        $this->insert_new_submit($DB);
        $this->insert_form_submit_values($DB, $data);
    }

    /**
     * @param $DB
     * @return void
     */
    private function insert_new_submit($DB)
    {
        $sql = "INSERT INTO {block_user_forms_submit}
        (user_id, form_id)
        VALUES
        (:user_id, :form_id)";

        $obj = (object)[];
        $obj->user_id = $this->user_id;
        $obj->form_id = $this->form_id;

        $get_insert_id = $DB->insert_record('block_user_forms_submit', $obj);

        if ($get_insert_id > 0) {
            $this->current_submit_id = $get_insert_id;
        } else {
            $this->errors[] = $DB->getErrorMsg();
        }
    }

    /**
     * @param $DB
     * @param $data
     * @return void
     * @throws dml_exception
     */
    private function insert_form_submit_values($DB, $data)
    {
        $build_insert = [];

        foreach ($data as $key => $submit_value) {
            $field = $this->get_field($key);
            if (isset($field)) {
                if ($field['field_type'] == 'FILE') {
                    //$submit_value = uniqid($submit_value) . 'form_id:' . $this->form_id . 'field_id:' . $field['field_id'] . 'submit_id:' . $this->current_submit_id;
                    $submit_value = $field['field_value'];
                }
                $build_insert[] = [
                    'submit_id' => $this->current_submit_id,
                    'field_id' => $field['field_id'],
                    'value' => $submit_value
                ];
            }
        }

        try {
            $DB->insert_records('block_user_forms_sub_values', $build_insert);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage() . $e->getTraceAsString();
        }
    }

    /**
     * @param $identifier
     * @return array|null
     * @throws dml_exception
     */
    public function get_field($identifier)
    {
        if (!isset($this->db_form_fields)) {
            $this->db_form_fields = $this->get_form_fields();
        }
        foreach ($this->db_form_fields as $key => $db_form_field) {
            if ($db_form_field->field_label == $identifier) {
                return [
                    'field_id' => $db_form_field->field_id,
                    'field_type' => $db_form_field->field_type,
                    'field_value' => $this->db_form_fields[$key]->field_value ?? null
                ];
            }
        }

        return null;
    }

    /**
     * @param $identifier
     * @return null
     * @throws dml_exception
     */
    public function get_field_value_by_identifier($identifier)
    {
        global $DB;
        if (!isset($this->db_form_fields_values)) {
            $this->db_form_fields_values = $this->get_submitted_data($DB);
        }
        if (isset($this->db_form_fields_values) && count($this->db_form_fields_values) > 0) {
            foreach ($this->db_form_fields_values as $db_form_field) {
                if ($db_form_field->field_label == $identifier) {
                    return $db_form_field->field_value;
                }
            }
        }

        return null;
    }

    /**
     * @param $identifier
     * @return null
     * @throws dml_exception
     */
    public function get_field_id_by_identifier($identifier)
    {
        global $DB;
        if (!isset($this->db_form_fields_values)) {
            $this->db_form_fields_values = $this->get_submitted_data($DB);
        }
        if (isset($this->db_form_fields_values) && count($this->db_form_fields_values) > 0) {
            foreach ($this->db_form_fields_values as $db_form_field) {
                if ($db_form_field->field_label == $identifier) {
                    return $db_form_field->field_id;
                }
            }
        }

        return null;
    }

    /**
     * @param $field_type
     * @return array|null
     * @throws dml_exception
     */
    public function get_type_submitted($field_type)
    {
        global $DB;
        $fields_by_type = [];

        if (!isset($this->db_form_fields_values)) {
            $this->db_form_fields_values = $this->get_submitted_data($DB);
        }

        if (isset($this->db_form_fields_values) && count($this->db_form_fields_values) > 0) {
            foreach ($this->db_form_fields_values as $db_form_field) {
                if ($db_form_field->field_type == $field_type) {
                    $fields_by_type[] = [
                        'field_id' => $db_form_field->field_id,
                        'field_label' => $db_form_field->field_label,
                        'field_value' => $db_form_field->field_value,
                    ];
                }
            }
        }

        if (count($fields_by_type) > 0) {
            return $fields_by_type;
        }

        return null;
    }

    /**
     * @param $field_type
     * @return array|null
     * @throws dml_exception
     */
    public function get_type_db_form($field_type)
    {
        global $DB;
        $fields_by_type = [];

        if (!isset($this->db_form_fields)) {
            $this->db_form_fields = $this->get_form_fields();
        }

        foreach ($this->db_form_fields as $key => $db_form_field) {
            if ($db_form_field->field_type == $field_type) {
                $fields_by_type[] = [
                    'field_id' => $db_form_field->field_id,
                    'field_label' => $db_form_field->field_label,
                    'field_value' => $this->db_form_fields[$key]->field_value ?? null
                ];
            }
        }

        if (count($fields_by_type) > 0) {
            return $fields_by_type;
        }

        return null;
    }

    /**
     * @return array|null
     * @throws dml_exception
     */
    public function build_file_uploads()
    {
        $submitted = $this->get_type_submitted('FILE');
        if (isset($submitted)) {
            return $submitted;
        }
        return $this->get_type_db_form('FILE');
    }

    public function generate_identifier_file($field_id)
    {
        $identifier = 'files_' . $this->get_next_sub_id() . $this->form_id . $field_id . $this->user_id;
        //$identifier = 'file_' . $this->form_id . 'field_id=' . $field_id;
        foreach ($this->db_form_fields as $key => $field) {
            if ($field->field_id == $field_id) {
                $this->db_form_fields[$key]->field_value = $identifier;
            }
        }
        return $identifier;
    }

    public function get_next_sub_id()
    {
        global $DB;

        $sql = "SELECT MAX(id) AS latest_id FROM {block_user_forms_submit}";

        $latestIdRecord = $DB->get_record_sql($sql);

        if ($latestIdRecord) {
            // Access the latest ID from the result.
            return $latestIdRecord->latest_id + 1;
        } else {
            // No records found in the table.
            return 1;
        }
    }

    public function get_all_labels()
    {
        global $DB;
        $sql = "SELECT label_lang FROM {block_user_form_fields}";

        $records = $DB->get_records_sql($sql);

        if ($records) {
            // Extract the values of the specified column from the records.
            return array_column($records, 'label_lang');
        }
    }

    public function get_all_submissions_for_user()
    {
        global $DB;
        try {
            $sql = "SELECT *
            FROM {block_user_forms_submit}
            WHERE user_id = :user_id ";

            $params = array('user_id' => $this->user_id, 'approved' => 1);

            $records = $DB->get_records_sql($sql, $params);

            $records_arr = [];

            if ($records) {
                foreach ($records as $record) {
                    $records_arr[] = $record->form_id;
                }
                return $records_arr;
            }
        } catch (\Exception $e) {
            error_log($e->getMessage() . $e->getTraceAsString());
        }

        return false;
    }

    /**
     * @return bool
     * TODO need to look at this to make it better
     */
    public function forms_order_access()
    {
        global $DB, $USER;
        if (is_siteadmin()) {
            //admin has access to all forms
            return true;
        }
        //Form id 2 requires form id 1
        //$seq_forms_require = [1, 2];

        //2 requires 1

        $submissions = $this->get_all_submissions_for_user();

        if ($submissions) {
            if ($this->form_id == 2) {
                /*if ($this->check_submit($DB)) {
                    return false;
                }*/

                if (in_array(1, $submissions)) {
                    $context = context_system::instance();
                    $user_roles = get_user_roles($context, $this->user_id);

                    foreach ($user_roles as $role) {
                        if ($role->roleid == 5) {
                            //student
                            if (intval($USER->id) !== $this->user_id) {
                                return false;
                            }

                            return true;
                        }
                    }
                }
            }
        }

        if ($this->form_id == 2) {
            return false;
        }
        return true;
    }

    public function approve_on_db()
    {
        global $DB;

        try {
            $sub = $DB->get_record('block_user_forms_submit', array('user_id' => $this->user_id, 'form_id' => $this->form_id));
            $sub->approved = 1;
            $DB->update_record(
                'block_user_forms_submit',
                $sub,
            );
        } catch (\Exception $e) {
            error_log($e->getMessage() . $e->getTraceAsString());
        }


    }

    public function set_approved()
    {
        global $DB;
        $submission = $this->check_submit($DB);
        if ($submission) {
            $this->approved = $submission->approved == 1;
        }
    }

    public function get_form_ids()
    {

    }

    public function get_submitted_forms()
    {

    }
}