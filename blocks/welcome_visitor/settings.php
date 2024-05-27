<?php
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configcheckbox('block_welcome_visitor/showcourses', 
    get_string('showcourses','block_welcome_visitor'), 
    get_string('showcoursesprompt','block_welcome_visitor'), 
    0));
}
