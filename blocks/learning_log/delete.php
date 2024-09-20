<?php
// blocks/learning_log/delete.php

require_once('../../config.php');

$id = required_param('id', PARAM_INT);
$context = context_system::instance();

require_login();

$record = $DB->get_record('learning_log', ['id' => $id, 'userid' => $USER->id], '*', MUST_EXIST);

$PAGE->set_url('/blocks/learning_log/delete.php', ['id' => $id]);
$PAGE->set_context($context);
$PAGE->set_title(get_string('deleterecord', 'block_learning_log'));

if (optional_param('confirm', false, PARAM_BOOL)) {
    $DB->delete_records('learning_log', ['id' => $id]);
    redirect(new moodle_url('/my'));
}

echo $OUTPUT->header();
echo $OUTPUT->confirm(
    get_string('confirmdelete', 'block_learning_log').' <strong>'.$record->description.'</strong>?',
    new moodle_url('/blocks/learning_log/delete.php', ['id' => $id, 'confirm' => 1]),
    new moodle_url('/my')
);
echo $OUTPUT->footer();
