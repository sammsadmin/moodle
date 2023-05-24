<?php
/**
 * Copyright (c) 2008 PayFast (Pty) Ltd
 * You (being anyone who is not PayFast (Pty) Ltd) may download and use this plugin / code in your own website in conjunction with a registered and active PayFast account. If your PayFast account is terminated for any reason, you may not use this plugin / code or part thereof.
 * Except as expressly indicated in this licence, you may not use, copy, modify or distribute this plugin / code or part thereof in any way.
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class enrol_payfast_edit_form extends moodleform {

    function definition() {
        $mform = $this->_form;

        list($instance, $plugin, $context) = $this->_customdata;

        $mform->addElement('header', 'header', get_string('pluginname', 'enrol_payfast'));

        $mform->addElement('text', 'name', get_string('custominstancename', 'enrol'));
        $mform->setType('name', PARAM_TEXT);

        $options = array(ENROL_INSTANCE_ENABLED  => get_string('yes'),
                         ENROL_INSTANCE_DISABLED => get_string('no'));
        $mform->addElement('select', 'status', get_string('status', 'enrol_payfast'), $options);
        $mform->setDefault('status', $plugin->get_config('status'));

        $mform->addElement('text', 'cost', get_string('cost', 'enrol_payfast'), array('size'=>4));
        $mform->setType('cost', PARAM_RAW); // Use unformat_float to get real value.
        $mform->setDefault('cost', format_float($plugin->get_config('cost'), 2, true));

        $mform->addElement('text', 'customint1', get_string('customint1', 'enrol_payfast'), array('size'=>4));
        $mform->setType('customint1', PARAM_RAW); // Use unformat_float to get real value.
        $mform->setDefault('customint1', format_float($plugin->get_config('customint1'), 2, true));

        $mform->addElement('text', 'customint2', get_string('customint2', 'enrol_payfast'), array('size'=>4));
        $mform->setType('customint2', PARAM_RAW); // Use unformat_float to get real value.
        $mform->setDefault('customint2', format_float($plugin->get_config('customint2'), 2, true));

        $payfastcurrencies = $plugin->get_currencies();
        $mform->addElement('select', 'currency', get_string('currency', 'enrol_payfast'), $payfastcurrencies);
        $mform->setDefault('currency', $plugin->get_config('currency'));

        if ($instance->id) {
            $roles = get_default_enrol_roles($context, $instance->roleid);
        } else {
            $roles = get_default_enrol_roles($context, $plugin->get_config('roleid'));
        }
        $mform->addElement('select', 'roleid', get_string('assignrole', 'enrol_payfast'), $roles);
        $mform->setDefault('roleid', $plugin->get_config('roleid'));

        $mform->addElement('duration', 'enrolperiod', get_string('enrolperiod', 'enrol_payfast'), array('optional' => true, 'defaultunit' => 86400));
        $mform->setDefault('enrolperiod', $plugin->get_config('enrolperiod'));
        $mform->addHelpButton('enrolperiod', 'enrolperiod', 'enrol_payfast');

        $mform->addElement('date_time_selector', 'enrolstartdate', get_string('enrolstartdate', 'enrol_payfast'), array('optional' => true));
        $mform->setDefault('enrolstartdate', 0);
        $mform->addHelpButton('enrolstartdate', 'enrolstartdate', 'enrol_payfast');

        $mform->addElement('date_time_selector', 'enrolenddate', get_string('enrolenddate', 'enrol_payfast'), array('optional' => true));
        $mform->setDefault('enrolenddate', 0);
        $mform->addHelpButton('enrolenddate', 'enrolenddate', 'enrol_payfast');

        $mform->addElement('text', 'customtext1', get_string('customtext1', 'enrol_payfast'));
        $mform->setType('customtext1', PARAM_RAW);
        $mform->setDefault('customtext1', $plugin->get_config('customtext1'));

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        if (enrol_accessing_via_instance($instance)) {
            $mform->addElement('static', 'selfwarn', get_string('instanceeditselfwarning', 'core_enrol'), get_string('instanceeditselfwarningtext', 'core_enrol'));
        }

        $this->add_action_buttons(true, ($instance->id ? null : get_string('addinstance', 'enrol')));

        $this->set_data($instance);
    }

    function validation($data, $files) {
        global $DB, $CFG;
        $errors = parent::validation($data, $files);

        list($instance, $plugin, $context) = $this->_customdata;

        if (!empty($data['enrolenddate']) and $data['enrolenddate'] < $data['enrolstartdate']) {
            $errors['enrolenddate'] = get_string('enrolenddaterror', 'enrol_payfast');
        }

        $cost = str_replace(get_string('decsep', 'langconfig'), '.', $data['cost']);
        if (!is_numeric($cost)) {
            $errors['cost'] = get_string('costerror', 'enrol_payfast');
        }

        $customint1 = str_replace(get_string('decsep', 'langconfig'), '.', $data['customint1']);
        if (!is_numeric($customint1)) {
            $errors['customint1'] = get_string('costerror', 'enrol_payfast');
        }

        $customint2 = str_replace(get_string('decsep', 'langconfig'), '.', $data['customint2']);
        if (!is_numeric($customint2)) {
            $errors['customint2'] = get_string('costerror', 'enrol_payfast');
        }

        return $errors;
    }
}
