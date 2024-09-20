<?php
// blocks/learning_log/classes/form/edit_form.php

require_once("$CFG->libdir/formslib.php");
require_once('classes/forms/add_form.php');

class block_learning_log_edit_form extends block_learning_log_add_form {
    public function definition() {
        parent::definition();

        $mform = $this->_form;
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
    }
}
