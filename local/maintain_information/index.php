<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_maintain_information
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('../../config.php');
require_once($CFG->libdir.'/gdlib.php');
require_once($CFG->dirroot.'/local/maintain_information/maintain_information_form.php');
require_once($CFG->dirroot.'/user/editlib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_once($CFG->dirroot.'/lib/filelib.php');
require_once($CFG->libdir.'/formslib.php');

require_login();

$userid = optional_param('id', $USER->id, PARAM_INT);    // User id.
$course = optional_param('course', SITEID, PARAM_INT);   // Course id (defaults to Site).
$returnto = optional_param('returnto', null, PARAM_ALPHA);  // Code determining where to return to after save.
$cancelemailchange = optional_param('cancelemailchange', 0, PARAM_INT);   // Course id (defaults to Site).

$context = context_user::instance($USER->id);

$PAGE->set_url('/local/maintain_information/index.php', array('course' => $course, 'id' => $userid));
$PAGE->set_title(get_string('title', 'local_maintain_information'));

$returnurl = new moodle_url('/my/');

$filemanageropts = array('subdirs' => 0, 'maxbytes' => '0', 'maxfiles' => 50, 'context' => $context);
$customdata = array('filemanageropts' => $filemanageropts);

// Create form.
$userform = new maintain_information_form(new moodle_url($PAGE->url, array('returnto' => $returnto)), $customdata);

$itemid = 0;

$disability_cert_fileid = $DB->get_record('user_info_field', ['shortname' => 'disability_cert']);

$draftitemid = file_get_submitted_draft_itemid('profile_field_disability_cert');

file_prepare_draft_area($draftitemid, $context->id, 'profilefield_file', 'files_'.$disability_cert_fileid->id, $itemid, $filemanageropts);

$disability_certificate = new stdClass();
$disability_certificate->profile_field_disability_cert = $draftitemid;

$userform->set_data($disability_certificate);

echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/maintain_information/css/style.css">';

// output header
echo $OUTPUT->header();

if($userform->is_cancelled()){
    redirect($returnurl);
}elseif($userform->is_submitted()){
    $userform->get_data();
    $data = $_POST;
    $user_updated = update_fields($data);
    file_save_draft_area_files($draftitemid, $context->id, 'profilefield_file', 'files_'.$disability_cert_fileid->id, $itemid, $filemanageropts);
    redirect($returnurl, get_string('changessaved'), null, \core\output\notification::NOTIFY_SUCCESS);
}else{
    // display form
    $userform->display();
}

// And proper footer.
echo $OUTPUT->footer();
