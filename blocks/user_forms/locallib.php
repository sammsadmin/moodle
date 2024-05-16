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

defined('MOODLE_INTERNAL') || die();

function application_account_email_student($link_to_application, $user_id){

    global $USER;

    $fromUser = new stdClass();
    $fromUser->email = 'noreply@cgisa.gov.za';
    $fromUser->firstname = 'CGISA';
    $fromUser->lastname = 'Chartered Governance Institute of Southern Africa';
    $fromUser->maildisplay = true;
    $fromUser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $fromUser->id = '-99';
    $fromUser->firstnamephonetic = '';
    $fromUser->lastnamephonetic = '';
    $fromUser->middlename = '';
    $fromUser->alternatename = '';

    //Student
    $toUserStudent = new stdClass();
    $toUserStudent->email = $USER->email;
    $toUserStudent->firstname = $USER->firstname;
    $toUserStudent->lastname = $USER->lastname;
    $toUserStudent->maildisplay = true;
    $toUserStudent->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $toUserStudent->id = '-99';
    $toUserStudent->firstnamephonetic = '';
    $toUserStudent->lastnamephonetic = '';
    $toUserStudent->middlename = '';
    $toUserStudent->alternatename = '';

    $subject = get_string('application_for_student_email_subject_student', 'block_user_forms');

    $aStudent = new stdClass();
    $aStudent->firstname = $USER->firstname;
    $aStudent->lastname = $USER->lastname;
    $aStudent->link = html_writer::link($link_to_application, $link_to_application);
    $aStudent->user_id = $user_id;

    $messageTextStudent = get_string('application_for_student_email_text_student', 'block_user_forms', $aStudent);

    $messageHtmlStudent = get_string('application_for_student_email_html_student', 'block_user_forms', $aStudent);

    $completeFilePath = '';
    $nameOfFile = '';

    email_to_user($toUserStudent, $fromUser, $subject, $messageTextStudent, $messageHtmlStudent, $completeFilePath, $nameOfFile, true);
}

function first_time_registration_email_student($link_to_application, $user_id){
    global $USER;

    $fromUser = new stdClass();
    $fromUser->email = 'noreply@cgisa.gov.za';
    $fromUser->firstname = 'CGISA';
    $fromUser->lastname = 'Chartered Governance Institute of Southern Africa';
    $fromUser->maildisplay = true;
    $fromUser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $fromUser->id = '-99';
    $fromUser->firstnamephonetic = '';
    $fromUser->lastnamephonetic = '';
    $fromUser->middlename = '';
    $fromUser->alternatename = '';

    //Student
    $toUserStudent = new stdClass();
    $toUserStudent->email = $USER->email;
    $toUserStudent->firstname = $USER->firstname;
    $toUserStudent->lastname = $USER->lastname;
    $toUserStudent->maildisplay = true;
    $toUserStudent->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $toUserStudent->id = '-99';
    $toUserStudent->firstnamephonetic = '';
    $toUserStudent->lastnamephonetic = '';
    $toUserStudent->middlename = '';
    $toUserStudent->alternatename = '';

    $subject = get_string('first_time_exam_reg_email_subject_student', 'block_user_forms');

    $aStudent = new stdClass();
    $aStudent->firstname = $USER->firstname;
    $aStudent->lastname = $USER->lastname;
    $aStudent->link = html_writer::link($link_to_application, $link_to_application);
    $aStudent->user_id = $user_id;

    $messageTextStudent = get_string('first_time_exam_reg_email_text_student', 'block_user_forms', $aStudent);

    $messageHtmlStudent = get_string('first_time_exam_reg_email_html_student', 'block_user_forms', $aStudent);

    $completeFilePath = '';
    $nameOfFile = '';

    email_to_user($toUserStudent, $fromUser, $subject, $messageTextStudent, $messageHtmlStudent, $completeFilePath, $nameOfFile, true);
}

function application_account_email($link_to_application, $user_id, $to_email)
{
    $adminuser = get_admin();

    $fromUser = new stdClass();
    $fromUser->email = 'noreply@cgisa.gov.za';
    $fromUser->firstname = 'CGISA';
    $fromUser->lastname = 'Chartered Governance Institute of Southern Africa';
    $fromUser->maildisplay = true;
    $fromUser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $fromUser->id = '-99';
    $fromUser->firstnamephonetic = '';
    $fromUser->lastnamephonetic = '';
    $fromUser->middlename = '';
    $fromUser->alternatename = '';

    $toUser = new stdClass();
    $toUser->email = $to_email;
    $toUser->firstname = $adminuser->firstname;
    $toUser->lastname = $adminuser->lastname;
    $toUser->maildisplay = true;
    $toUser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $toUser->id = '-99';
    $toUser->firstnamephonetic = '';
    $toUser->lastnamephonetic = '';
    $toUser->middlename = '';
    $toUser->alternatename = '';

    $subject = get_string('application_for_student_email_subject', 'block_user_forms');

    $a = new stdClass();
    $a->firstname = $adminuser->firstname;
    $a->lastname = $adminuser->lastname;
    $a->link = html_writer::link($link_to_application, $link_to_application);
    $a->user_id = $user_id;

    $messageText = get_string('application_for_student_email_text', 'block_user_forms', $a);

    $messageHtml = get_string('application_for_student_email_html', 'block_user_forms', $a);

    $completeFilePath = '';
    $nameOfFile = '';

    email_to_user($toUser, $fromUser, $subject, $messageText, $messageHtml, $completeFilePath, $nameOfFile, true);
}

function first_time_registration_email($link_to_submission, $user_id, $to_email)
{
    $adminuser = get_admin();

    $fromUser = new stdClass();
    $fromUser->email = 'noreply@cgisa.gov.za';
    $fromUser->firstname = 'CGISA';
    $fromUser->lastname = 'Chartered Governance Institute of Southern Africa';
    $fromUser->maildisplay = true;
    $fromUser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $fromUser->id = '-99';
    $fromUser->firstnamephonetic = '';
    $fromUser->lastnamephonetic = '';
    $fromUser->middlename = '';
    $fromUser->alternatename = '';

    $toUser = new stdClass();
    $toUser->email = $to_email;
    $toUser->firstname = $adminuser->firstname;
    $toUser->lastname = $adminuser->lastname;
    $toUser->maildisplay = true;
    $toUser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $toUser->id = '-99';
    $toUser->firstnamephonetic = '';
    $toUser->lastnamephonetic = '';
    $toUser->middlename = '';
    $toUser->alternatename = '';

    $subject = get_string('first_time_exam_reg_email_subject', 'block_user_forms');

    $a = new stdClass();
    $a->firstname = $adminuser->firstname;
    $a->lastname = $adminuser->lastname;
    $a->link = html_writer::link($link_to_submission, $link_to_submission);
    $a->user_id = $user_id;

    $messageText = get_string('first_time_exam_reg_email_text', 'block_user_forms', $a);

    $messageHtml = get_string('first_time_exam_reg_email_html', 'block_user_forms', $a);

    $completeFilePath = '';
    $nameOfFile = '';

    email_to_user($toUser, $fromUser, $subject, $messageText, $messageHtml, $completeFilePath, $nameOfFile, true);
}

function update_providers_list()
{
    $blockconfig = get_config('block_user_forms');
    $endpoint_url = $blockconfig->endpoint_url;
    //'http://samms.archton.io:8080/providers
    $currenttime = date("Y-m-d H:i:s");
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint_url . '/providers',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'accept: */*'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $providers = json_decode($response);
    foreach ($providers as $provider) {
        global $DB;
        $provider_exists = $DB->get_record('block_user_forms_tuition', ['name' => $provider->name]);
        if ($provider_exists) {
            $provider_exists->is_active = $provider->isActive;
            $provider_exists->date_modified = $currenttime;
            $DB->update_record('block_user_forms_tuition', $provider_exists);
        } else {
            $new_provider = new stdClass();
            $new_provider->name = $provider->name;
            $new_provider->is_active = $provider->isActive;
            $new_provider->date_modified = $currenttime;
            $new_provider->date_created = $currenttime;
            $DB->insert_record('block_user_forms_tuition', $new_provider);
        }
    }
    return $providers;
}

function update_venues_list()
{
    $blockconfig = get_config('block_user_forms');
    $endpoint_url = $blockconfig->endpoint_url;
    //'http://samms.archton.io:8080/examVenues'
    $currenttime = date("Y-m-d H:i:s");
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint_url . '/examVenues',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'accept: */*'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $venues = json_decode($response);
    foreach ($venues as $venue) {
        global $DB;
        $venue_exists = $DB->get_record('block_user_forms_venues', ['name' => $venue->name]);
        if ($venue_exists) {
            $venue_exists->is_active = $venue->isActive;
            $venue_exists->date_modified = $currenttime;
            $DB->update_record('block_user_forms_venues', $venue_exists);
        } else {
            $new_venue = new stdClass();
            $new_venue->name = $venue->name;
            $new_venue->is_active = $venue->isActive;
            $new_venue->date_modified = $currenttime;
            $new_venue->date_created = $currenttime;
            $DB->insert_record('block_user_forms_venues', $new_venue);
        }
    }
    return $venues;
}

function getDataFromSammsApi(string $route)
{
    $blockconfig = get_config('block_user_forms');
    $endpoint_url = $blockconfig->endpoint_url;
    //'http://samms.archton.io:8080/examSessions'
    $currenttime = date("Y-m-d H:i:s");
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint_url . '/' . $route,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'accept: */*'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);
}

function getExamSessions(){
    $sessions = getDataFromSammsApi('examSessions');
    //$year = date("Y"); 
    //foreach($sessions as $session){
    //}
    print_r('Sessions: ------------------------------------------');
    echo '<pre>';
    print_r($sessions);
    echo '</pre>';
}

function getModules(){
    return getDataFromSammsApi('modules');
    print_r('Sessions: ------------------------------------------');
    echo '<pre>';
    print_r($modules);
    echo '</pre>';
    //$modules_arr = [];
    //foreach($modules as $module){
    //    if(isset($module->isActive) && $module->isActive == 1)){
    //        $modules_arr[] = $module;
    //    }
    //}
    //return $modules_arr;
}

function update_DB_modules(){
    $modules = getDataFromSammsApi('modules');
    foreach ($modules as $module) {
        global $DB;
        $module_exists = $DB->get_record('block_user_forms_modules', ['identifier' => $module->id]);
        $currenttime = date("Y-m-d H:i:s");
        if ($module_exists) {
            $module_exists->is_active = $module->isActive;
            $module_exists->description = $module->description;
            $module_exists->code = $module->code;
            $module_exists->programme = $module->programme->description;
            $module_exists->date_modified = $currenttime;
            $DB->update_record('block_user_forms_modules', $module_exists);
        } else {
            $new_module = new stdClass();
            $new_module->code = $module->code;
            $new_module->description = $module->description;
            $new_module->identifier = $module->id;
            $new_module->programme = $module->programme->description;
            $new_module->is_active = $module->isActive;
            $new_module->date_modified = $currenttime;
            $new_module->date_created = $currenttime;
            $DB->insert_record('block_user_forms_modules', $new_module);
        }
    }
}

function update_DB_sessions(){
    $examSessions = getDataFromSammsApi('examSessions');
    foreach ($examSessions as $examSession) {
        global $DB;
        $examSession_exists = $DB->get_record('block_user_forms_sessions', ['month' => $examSession->description, 'year' => $examSession->year]);
        $currenttime = date("Y-m-d H:i:s");
        if ($examSession_exists) {
            $examSession_exists->is_active = $examSession->isActive;
            $examSession_exists->month = $examSession->description;
            $examSession_exists->year = $examSession->year;
            $examSession_exists->date_modified = $currenttime;
            $DB->update_record('block_user_forms_sessions', $examSession_exists);
        } else {
            $new_module = new stdClass();
            $new_module->month = $examSession->description;
            $new_module->description = $examSession->description;
            $new_module->year = $examSession->year;
            $new_module->is_active = $examSession->isActive;
            $new_module->date_modified = $currenttime;
            $new_module->date_created = $currenttime;
            $DB->insert_record('block_user_forms_sessions', $new_module);
        }
    }
}
