<?php
// blocks/learning_log/classes/form/add_form.php

require_once("$CFG->libdir/formslib.php");

class block_learning_log_add_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('date_selector', 'event_date_int', get_string('event_date', 'block_learning_log'));

        $mform->addElement('text', 'description', get_string('description', 'block_learning_log'));
        $mform->setType('description', PARAM_TEXT);
        $mform->addRule('description', null, 'required', null, 'client');

        $mform->addElement('text', 'organisation', get_string('organisation', 'block_learning_log'));
        $mform->setType('organisation', PARAM_TEXT);
        $mform->addRule('organisation', null, 'required', null, 'client');

        $mform->addElement('text', 'duration', get_string('duration', 'block_learning_log'));
        $mform->setType('duration', PARAM_INT);
        $mform->addRule('duration', null, 'required', null, 'client');

        $mform->addElement('advcheckbox', 'is_verifiable', get_string('is_verifiable', 'block_learning_log'));

        $this->add_action_buttons(true, get_string('save', 'block_learning_log'));
    }
}
