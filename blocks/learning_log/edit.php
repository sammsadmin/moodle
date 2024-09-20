<?php
// blocks/learning_log/edit.php

require_once('../../config.php');
require_once('classes/forms/edit_form.php');

$id = required_param('id', PARAM_INT);
$context = context_system::instance();

require_login();

$record = $DB->get_record('learning_log', ['id' => $id, 'userid' => $USER->id], '*', MUST_EXIST);
$record->event_date_int = strtotime($record->event_date);
$PAGE->set_url('/blocks/learning_log/edit.php', ['id' => $id]);
$PAGE->set_context($context);
$PAGE->set_title(get_string('editrecord', 'block_learning_log'));

$mform = new block_learning_log_edit_form();
$mform->set_data($record);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/my'));
} else if ($data = $mform->get_data()) {
    $record->description = $data->description;
    $record->organisation = $data->organisation;
    $record->duration = $data->duration;
    $record->event_date = date('Y-m-d H:i:s', $data->event_date_int);
    $record->is_verifiable = $data->is_verifiable ? 1 : 0;

    $DB->update_record('learning_log', $record);
    redirect(new moodle_url('/my'));
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
