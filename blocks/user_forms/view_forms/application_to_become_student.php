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

require_once('../../../config.php');
require_once('../locallib.php');

global $DB, $CFG, $PAGE, $OUTPUT, $USER;

require_login();

$returnto = optional_param('returnto', null, PARAM_ALPHA);
$user_id_param = optional_param('uid', null, PARAM_INT);

$user_id = $USER->id;

if (isset($user_id_param)) {
    $user_id = intval($user_id_param);
    if (intval($USER->id) !== $user_id && !is_siteadmin()) {
        $unauth_link = new moodle_url('/blocks/user_forms/unauthorized.php');
        redirect($unauth_link);
    }
} /*elseif (isset($_POST['user_id'])) {
    //handle submission
    $user_id = intval($_POST['user_id']);
}*/


require_once('../classes/form/application_to_become_student.php');
require_once('../classes/model/form_model.php');

$form_model = new form_model(1, $user_id);

if (!$form_model->forms_order_access()) {
    $unauth_link = new moodle_url('/blocks/user_forms/unauthorized.php');
    redirect($unauth_link);
}

$context = context_user::instance($user_id);

$PAGE->set_context($context);

//check if user has paid their fees in full

$PAGE->set_url('/blocks/user_forms/view_forms/application_to_become_student.php');
$PAGE->set_title('Application to be a student');
$PAGE->set_heading('Application to be a student');
$PAGE->set_pagelayout('standard');

$returnurl = new moodle_url('/my/');


echo '<link rel="stylesheet" type="text/css" href="' . $CFG->wwwroot . '/blocks/user_forms/css/style.css">';

$data = new stdClass();
$filemanageropts = array('subdirs' => 0, 'maxbytes' => '0', 'maxfiles' => 50, 'context' => $context);
$customdata = array('filemanageropts' => $filemanageropts);

$mform = new application_to_become_student($form_model, $user_id, array('returnto' => $returnto), $customdata);

$attachments = $form_model->build_file_uploads();

$entry = new stdClass();

$fileid = 0;

if (isset($attachments)) {
    foreach ($attachments as $attachment) {
        $field_label = $attachment['field_label'];
        if (isset($attachment['field_value'])) {
            $identifier = $attachment['field_value'];
        } else {
            $identifier = $form_model->generate_identifier_file($attachment['field_id']);
        }
        $draftitemid = file_get_submitted_draft_itemid($field_label);
        $fileid = 0;
        file_prepare_draft_area($draftitemid, $context->id, $field_label, $identifier, $fileid, $filemanageropts);
        $entry->$field_label = $draftitemid;
    }
}

$mform->set_data($entry);

if ($mform->is_submitted() && $mform->get_data()) {
    if ($form_model->check_submit($DB)) {
        //APPROVAL and site admin is editing
        //$form_model->approve_on_db();
        redirect($returnurl, get_string('changessaved'), null, \core\output\notification::NOTIFY_SUCCESS);
    } else {
        $attachments = $form_model->build_file_uploads();
        $data = $mform->get_data();

        if (isset($attachments)) {
            foreach ($attachments as $attachment) {
                $identifier = $attachment['field_value'];
                $draftitemid = file_get_submitted_draft_itemid($attachment['field_label']);
                $fileid = 0;
                file_save_draft_area_files($draftitemid, $context->id, $attachment['field_label'], $identifier, $fileid, $filemanageropts);
            }
        }

        try {
            $transaction = $DB->start_delegated_transaction();
            $form_model->update_create($DB, $data);
            if (count($form_model->errors) > 0) {
                $errors_string = implode(',', $form_model->errors);

                throw new Exception($errors_string);
            }
            $transaction->allow_commit();
        } catch (Exception $e) {
            $transaction->rollback($e);
            redirect($returnurl, get_string('error_submission', 'block_user_forms'), null, \core\output\notification::NOTIFY_ERROR);
            exit();
        }
        //Admin use link to see form
        $url_link = new moodle_url('/blocks/user_forms/view_forms/application_to_become_student.php?uid=' . $user_id);
        $blockconfig = get_config('block_user_forms');
        $arr_admin_emails = explode(';', $blockconfig->admin_emails);

        if (isset($arr_admin_emails) && is_array($arr_admin_emails) && count($arr_admin_emails) > 0) {
            foreach ($arr_admin_emails as $admin_email) {
                application_account_email($url_link, $user_id, $admin_email);
            }
        }
        //Email student
        application_account_email_student($url_link, $user_id);

        redirect($returnurl, get_string('changessaved'), null, \core\output\notification::NOTIFY_SUCCESS);
    }

}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();


