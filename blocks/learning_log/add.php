<?php
// blocks/learning_log/add.php

require_once('../../config.php');
require_once('classes/forms/add_form.php');

$context = context_system::instance();
require_login();

$PAGE->set_url('/blocks/learning_log/add.php');
$PAGE->set_context($context);
$PAGE->set_title(get_string('addrecord', 'block_learning_log'));

$mform = new block_learning_log_add_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/my'));
} else if ($data = $mform->get_data()) {
    $record = new stdClass();
    $record->userid = $USER->id;
    $record->description = $data->description;
    $record->organisation = $data->organisation;
    $record->duration = $data->duration;
    $record->event_date = date('Y-m-d H:i:s', $data->event_date_int);
    $record->is_verifiable = $data->is_verifiable ? 1 : 0;

    $DB->insert_record('learning_log', $record);
    redirect(new moodle_url('/my'));
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
