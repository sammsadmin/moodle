<?php
/**
 * Copyright (c) 2008 PayFast (Pty) Ltd
 * You (being anyone who is not PayFast (Pty) Ltd) may download and use this plugin / code in your own website in conjunction with a registered and active PayFast account. If your PayFast account is terminated for any reason, you may not use this plugin / code or part thereof.
 * Except as expressly indicated in this licence, you may not use, copy, modify or distribute this plugin / code or part thereof in any way.
 */

require('../../config.php');
require_once('edit_form.php');

$courseid   = required_param('courseid', PARAM_INT);
$instanceid = optional_param('id', 0, PARAM_INT); // instanceid

$course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

require_login($course);
require_capability('enrol/payfast:config', $context);

$PAGE->set_url('/enrol/payfast/edit.php', array('courseid'=>$course->id, 'id'=>$instanceid));
$PAGE->set_pagelayout('admin');

$return = new moodle_url('/enrol/instances.php', array('id'=>$course->id));
if (!enrol_is_enabled('payfast')) {
    redirect($return);
}

$plugin = enrol_get_plugin('payfast');

if ($instanceid) {
    $instance = $DB->get_record('enrol', array('courseid'=>$course->id, 'enrol'=>'payfast', 'id'=>$instanceid), '*', MUST_EXIST);
    $instance->cost = format_float($instance->cost, 2, true);
    $instance->customint1 = format_float($instance->customint1, 2, true);
    $instance->customint2 = format_float($instance->customint2, 2, true);
} else {
    require_capability('moodle/course:enrolconfig', $context);
    // no instance yet, we have to add new instance
    navigation_node::override_active_url(new moodle_url('/enrol/instances.php', array('id'=>$course->id)));
    $instance = new stdClass();
    $instance->id       = null;
    $instance->courseid = $course->id;
}

$mform = new enrol_payfast_edit_form(NULL, array($instance, $plugin, $context));

if ($mform->is_cancelled()) {
    redirect($return);

} else if ($data = $mform->get_data()) {
    if ($instance->id) {
        $reset = ($instance->status != $data->status);

        $instance->status         = $data->status;
        $instance->name           = $data->name;
        $instance->cost           = unformat_float($data->cost);
        $instance->customint1     = unformat_float($data->customint1);
        $instance->customint2     = unformat_float($data->customint2);
        $instance->currency       = $data->currency;
        $instance->roleid         = $data->roleid;
        $instance->enrolperiod    = $data->enrolperiod;
        $instance->enrolstartdate = $data->enrolstartdate;
        $instance->enrolenddate   = $data->enrolenddate;
        $instance->customtext1    = $data->customtext1;
        $instance->timemodified   = time();
        $DB->update_record('enrol', $instance);

        if ($reset) {
            $context->mark_dirty();
        }

    } else {
        $fields = array('status'=>$data->status, 'name'=>$data->name, 'currency'=>$data->currency, 'roleid'=>$data->roleid,
                        'cost'=>unformat_float($data->cost), 'customint1'=>unformat_float($data->customint1), 'customint2'=>unformat_float($data->customint2),
                        'enrolperiod'=>$data->enrolperiod, 'enrolstartdate'=>$data->enrolstartdate, 'enrolenddate'=>$data->enrolenddate,
                        'customtext1'=>$data->customtext1);
        $plugin->add_instance($course, $fields);
    }

    redirect($return);
}

$PAGE->set_heading($course->fullname);
$PAGE->set_title(get_string('pluginname', 'enrol_payfast'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'enrol_payfast'));
$mform->display();
echo $OUTPUT->footer();
