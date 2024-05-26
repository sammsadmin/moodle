<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Listens for Instant Payment Notification from PayFast
 *
 * This script waits for Payment notification from PayFast,
 * then double checks that data by sending it back to PayFast.
 * If PayFast verifies this then it sets up the enrolment for that
 * user.
 *
 * @package    enrol_payfast
 * Copyright (c) 2008 PayFast (Pty) Ltd
 * You (being anyone who is not PayFast (Pty) Ltd) may download and use this plugin / code in your own website in conjunction with a registered and active PayFast account. If your PayFast account is terminated for any reason, you may not use this plugin / code or part thereof.
 * Except as expressly indicated in this licence, you may not use, copy, modify or distribute this plugin / code or part thereof in any way.
 */

// Disable moodle specific debug messages and any errors in output,
// comment out when debugging or better look into error log!
//define('NO_DEBUG_DISPLAY', true);

require("../../config.php");
require_once("lib.php");
require_once($CFG->libdir.'/enrollib.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir.'/pdflib.php');
require_once( "payfast_common.inc" );

// PayFast does not like when we return error messages here,
// the custom handler just logs exceptions and stops.
set_exception_handler('enrol_payfast_itn_exception_handler');

/// Keep out casual intruders
if ( empty( $_POST ) or !empty( $_GET ) )
{
    print_error("Sorry, you can not use the script that way.");
}
$tld = 'co.za';
$plugin = enrol_get_plugin('payfast');
define( 'PF_DEBUG', $plugin->get_config( 'payfast_debug' ) );

$pfError = false;
$pfErrMsg = '';
$pfDone = false;
$pfData = array();
$pfParamString = '';

pflog( 'PayFast ITN call received' );
$data = new stdClass();

foreach ( $_POST as $key => $value)
{
    $data->$key = $value;
}

$custom = explode( '-', $data->m_payment_id );
$data->userid           = (int)$custom[0];
$data->courseid         = (int)$custom[1];
$data->instanceid       = (int)$custom[2];
$data->payment_currency = 'ZAR';
$data->timeupdated      = time();

/// get the user and course records

if (! $user = $DB->get_record( "user", array( "id" => $data->userid ) ) )
{
    $pfError = true;
    $pfErrMsg .= "Not a valid user id \n";
}

if (! $course = $DB->get_record( "course", array( "id" => $data->courseid ) ) )
{
    $pfError = true;
    $pfErrMsg .= "Not a valid course id \n";
}

if (! $context = context_course::instance( $course->id, IGNORE_MISSING ) )
{
    $pfError = true;
    $pfErrMsg .= "Not a valid context id \n";
}

if (! $plugin_instance = $DB->get_record( "enrol", array( "id" => $data->instanceid, "status"=>0 ) ) )
{
    $pfError = true;
    $pfErrMsg .= "Not a valid instance id \n";
}


//// Notify PayFast that information has been received
if( !$pfError && !$pfDone )
{
    header( 'HTTP/1.0 200 OK' );
    flush();
}

//// Get data sent by PayFast
if( !$pfError && !$pfDone )
{
    pflog( 'Get posted data' );

    // Posted variables from ITN
    $pfData = pfGetData();
    $pfData['item_name'] = html_entity_decode( $pfData['item_name'] );
    $pfData['item_description'] = html_entity_decode( $pfData['item_description'] );
    pflog( 'PayFast Data: '. print_r( $pfData, true ) );

    if( $pfData === false )
    {
        $pfError = true;
        $pfErrMsg = PF_ERR_BAD_ACCESS;
    }
}

//// Verify security signature
if( !$pfError && !$pfDone )
{
    pflog( 'Verify security signature' );
    $passphrase = $plugin->get_config( 'merchant_passphrase' );
    $pfPassphrase = ( $plugin->get_config( 'payfast_mode' ) == 'test' && 
    ( empty( $plugin->get_config( 'merchant_id' ) ) || empty( $plugin->get_config( 'merchant_key' ) ) ) ) ? 'payfast' : ( !empty( $passphrase ) ? $passphrase : null );
    // If signature different, log for debugging
    if( !pfValidSignature( $pfData, $pfParamString, $pfPassphrase ) )
    {
        $pfError = true;
        $pfErrMsg = PF_ERR_INVALID_SIGNATURE;
    }
}

//// Verify source IP (If not in debug mode)
if( !$pfError && !$pfDone && !PF_DEBUG )
{
    pflog( 'Verify source IP' );

    if( !pfValidIP( $_SERVER['REMOTE_ADDR'] ) )
    {
        $pfError = true;
        $pfErrMsg = PF_ERR_BAD_SOURCE_IP;
    }
}


//// Verify data received
if( !$pfError )
{
    pflog( 'Verify data received' );

    $pfHost = ( $plugin->get_config( 'payfast_mode' ) == 'live' ? 'www' : 'sandbox'  ) . '.payfast.' . $tld;
    $pfValid = pfValidData( $pfHost, $pfParamString );

    if( !$pfValid )
    {
        $pfError = true;
        $pfErrMsg = PF_ERR_BAD_ACCESS;
    }
}

//// Check data against internal order
if( !$pfError && !$pfDone )
{
    pflog( 'Check data against internal order' );

    if ( (float) $plugin_instance->cost <= 0 ) {
        $cost = (float) $plugin->get_config('cost');
    } else {
        $cost = (float) $plugin_instance->cost;
    }
    $cost = format_float( $cost, 2, false );

    if ( (float) $plugin_instance->customint1 <= 0 ) {
        $customint1 = (float) $plugin->get_config('customint1');
    } else {
        $customint1 = (float) $plugin_instance->customint1;
    }
    $customint1 = format_float( $customint1, 2, false );

    if ( (float) $plugin_instance->customint2 <= 0 ) {
        $customint2 = (float) $plugin->get_config('customint2');
    } else {
        $customint2 = (float) $plugin_instance->customint2;
    }
    $customint2 = format_float( $customint2, 2, false );

    // Check order amount
    $is_student_or_member = false;
    foreach (get_user_roles(context_system::instance(), $user->id) as $role) {
        if ($role->name == 'Member' || $role->shortname == 'member') {
            // deduct 15% VAT for foreigner (country == ZA && country not empty)
            if ($user->country && $user->country !== 'ZA') {
                $vat_percentage = 0;
                $vat_amount = 0;
                $amount = format_float(round(($customint2 / 115) * 100, 2), 2);
            }
            else {
                $vat_percentage = 15;
                $vat_amount = format_float( ($customint2 / 115) * 15, 2, false );
                $amount = format_float( $customint2, 2, false );
            }

            $is_student_or_member = true;
            break;
        }
        elseif ($role->name == 'Student' || $role->shortname == 'student') {
            // deduct 15% VAT for foreigner (country == ZA && country not empty)
            if ($user->country && $user->country !== 'ZA') {
                $vat_percentage = 0;
                $vat_amount = 0;
                $amount = format_float(round(($customint1 / 115) * 100, 2), 2, false);
            }
            else {
                $vat_percentage = 15;
                $vat_amount = format_float( ($customint1 / 115) * 15, 2, false );
                $amount = format_float( $customint1, 2, false );
            }

            $is_student_or_member = true;
            break;
        }
    }

    if (! $is_student_or_member) {
        $amount = $cost;

        // deduct 15% VAT for foreigner (country == ZA && country not empty)
        if ($user->country && $user->country !== 'ZA') {
            $vat_percentage = 0;
            $vat_amount = 0;
            $amount = format_float(round(($cost / 115) * 100, 2), 2, false);
        }
        else {
            $vat_percentage = 15;
            $vat_amount = format_float( ($cost / 115) * 15, 2, false );
            $amount = format_float( $cost, 2, false );
        }
    }

    if ( !pfAmountsEqual( $pfData['amount_gross'], $amount ) ) {
        $pfError = true;
        $pfErrMsg = PF_ERR_AMOUNT_MISMATCH;
    }
}

if( !$pfError && !$pfDone )
{
    if ( $existing = $DB->get_record( "enrol_payfast", array( "pf_payment_id" => $data->pf_payment_id ) ) )
    {   // Make sure this transaction doesn't exist already
        $pfErrMsg .= "Transaction $data->pf_payment_id is being repeated! \n" ;
        $pfError = true;
    }
    if ( $data->payment_currency != $plugin_instance->currency )
    {
        $pfErrMsg .= "Currency does not match course settings, received: " . $data->mc_currency . "\n";
        $pfError = true;
    }

    if ( !$user = $DB->get_record( 'user', array( 'id' => $data->userid ) ) )
    {   // Check that user exists
        $pfErrMsg .= "User $data->userid doesn't exist \n";
        $pfError = true;
    }

    if ( !$course = $DB->get_record( 'course', array( 'id'=> $data->courseid ) ) )
    { // Check that course exists
        $pfErrMsg .= "Course $data->courseid doesn't exist \n";
        $pfError = true;
    }
}

//// Check status and update order
if( !$pfError && !$pfDone )
{
    pflog( 'Check status and update order' );

    $transaction_id = $pfData['pf_payment_id'];

    switch( $pfData['payment_status'] )
    {
        case 'COMPLETE':
            pflog( '- Complete' );

            $coursecontext = context_course::instance($course->id, IGNORE_MISSING);

            if ($plugin_instance->enrolperiod) {
                $timestart = time();
                $timeend   = $timestart + $plugin_instance->enrolperiod;
            } else {
                $timestart = 0;
                $timeend   = 0;
            }

            // Enrol user
            $plugin->enrol_user($plugin_instance, $user->id, $plugin_instance->roleid, $timestart, $timeend);

            // Pass $view=true to filter hidden caps if the user cannot see them
            if ($users = get_users_by_capability($context, 'moodle/course:update', 'u.*', 'u.id ASC',
                '', '', '', '', false, true)) {
                $users = sort_by_roleassignment_authority($users, $context);
                $teacher = array_shift($users);
            } else {
                $teacher = false;
            }

            $mailstudents = $plugin->get_config('mailstudents');
            $mailteachers = $plugin->get_config('mailteachers');
            $mailadmins   = $plugin->get_config('mailadmins');
            $mailfinance  = $plugin->get_config('mailfinance');
            $shortname = format_string($course->shortname, true, array('context' => $context));

            $invoice = enrol_payfast_itn_send_invoice($course, $user, $amount, $vat_amount, $vat_percentage);



            if (!empty($mailstudents)) {
                $a = new stdClass();
                $a->coursename = format_string($course->fullname, true, array('context' => $coursecontext));
                $a->profileurl = "$CFG->wwwroot/user/view.php?id=$user->id";
                $a->webinarlink = $plugin_instance->customtext1;

                $eventdata = new \core\message\message();
                $eventdata->modulename        = 'moodle';
                $eventdata->component         = 'enrol_payfast';
                $eventdata->name              = 'payfast_enrolment';
                $eventdata->userfrom          = empty($teacher) ? get_admin() : $teacher;
                $eventdata->userto            = $user;
                $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
                $eventdata->fullmessage       = get_string('welcometocoursetext', 'enrol_payfast', $a);
                $eventdata->fullmessageformat = FORMAT_PLAIN;
                $eventdata->fullmessagehtml   = '';
                $eventdata->smallmessage      = '';

                $eventdata->attachment        = $invoice;
                $eventdata->attachname        = $invoice->get_filename();

                message_send($eventdata);

            }

            if (!empty($mailteachers) && !empty($teacher)) {
                $a->course = format_string($course->fullname, true, array('context' => $coursecontext));
                $a->user = fullname($user);

                $eventdata = new \core\message\message();
                $eventdata->modulename        = 'moodle';
                $eventdata->component         = 'enrol_payfast';
                $eventdata->name              = 'payfast_enrolment';
                $eventdata->userfrom          = $user;
                $eventdata->userto            = $teacher;
                $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
                $eventdata->fullmessage       = get_string('enrolmentnewuser', 'enrol', $a);
                $eventdata->fullmessageformat = FORMAT_PLAIN;
                $eventdata->fullmessagehtml   = '';
                $eventdata->smallmessage      = '';
                message_send($eventdata);
            }

            if ( !empty( $mailadmins ) )
            {
                $a->course = format_string($course->fullname, true, array('context' => $coursecontext));
                $a->user = fullname($user);
                $admins = get_admins();
                foreach ($admins as $admin) {
                    $eventdata = new \core\message\message();
                    $eventdata->modulename        = 'moodle';
                    $eventdata->component         = 'enrol_payfast';
                    $eventdata->name              = 'payfast_enrolment';
                    $eventdata->userfrom          = $user;
                    $eventdata->userto            = $admin;
                    $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
                    $eventdata->fullmessage       = get_string('enrolmentnewuser', 'enrol', $a);
                    $eventdata->fullmessageformat = FORMAT_PLAIN;
                    $eventdata->fullmessagehtml   = '';
                    $eventdata->smallmessage      = '';
                    message_send($eventdata);
                }
            }

            if ( !empty( $mailfinance ) )
            {
                $syscontext = context_system::instance();
                $finance_users = $DB->get_records_sql("SELECT u.* FROM {user} u, {role_assignments} ra, {role} r, {context} c WHERE ra.userid = u.id AND r.id = ra.roleid AND c.id = ra.contextid AND u.deleted = ? AND u.suspended = ? AND c.id = ? AND r.shortname = ?", array(0, 0, $syscontext->id, "Finance"));

                foreach ($finance_users as $finance_user) {
                    $eventdata = new \core\message\message();
                    $eventdata->modulename        = 'moodle';
                    $eventdata->component         = 'enrol_payfast';
                    $eventdata->name              = 'payfast_enrolment';
                    $eventdata->userfrom          = $user;
                    $eventdata->userto            = $finance_user;
                    $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
                    $eventdata->fullmessage       = get_string('enrolmentnewuser', 'enrol', $a);
                    $eventdata->fullmessageformat = FORMAT_PLAIN;
                    $eventdata->fullmessagehtml   = '';
                    $eventdata->smallmessage      = '';
                    message_send($eventdata);
                }
            }

            $DB->insert_record("enrol_payfast", $data );

            break;

        case 'FAILED':
            pflog( '- Failed' );

            break;

        case 'PENDING':
            pflog( '- Pending' );

            $eventdata = new \core\message\message();
            $eventdata->modulename        = 'moodle';
            $eventdata->component         = 'enrol_payfast';
            $eventdata->name              = 'payfast_enrolment';
            $eventdata->userfrom          = get_admin();
            $eventdata->userto            = $user;
            $eventdata->subject           = "Moodle: PayFast payment";
            $eventdata->fullmessage       = "Your PayFast payment is pending.";
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml   = '';
            $eventdata->smallmessage      = '';
            message_send($eventdata);

            message_payfast_error_to_admin("Payment pending", $data );

            break;

        default:
            // If unknown status, do nothing (safest course of action)
            break;
    }

}
else
{
    $DB->insert_record( "enrol_payfast", $data, false);
    message_payfast_error_to_admin( "Received an invalid payment notification!! (Fake payment?)\n" . $pfErrMsg, $data);
    die( 'ERROR encountered, view the logs to debug.' );
}

exit;


//--- HELPER FUNCTIONS --------------------------------------------------------------------------------------


function message_payfast_error_to_admin($subject, $data) {
    echo $subject;
    $admin = get_admin();
    $site = get_site();

    $message = "$site->fullname:  Transaction failed.\n\n$subject\n\n";

    foreach ($data as $key => $value) {
        $message .= "$key => $value\n";
    }

    $eventdata = new \core\message\message();
    $eventdata->modulename        = 'moodle';
    $eventdata->component         = 'enrol_payfast';
    $eventdata->name              = 'payfast_enrolment';
    $eventdata->userfrom          = $admin;
    $eventdata->userto            = $admin;
    $eventdata->subject           = "PAYFAST ERROR: ".$subject;
    $eventdata->fullmessage       = $message;
    $eventdata->fullmessageformat = FORMAT_PLAIN;
    $eventdata->fullmessagehtml   = '';
    $eventdata->smallmessage      = '';
    pflog( 'Error To Admin: ' . print_r( $eventdata, true ) );
    message_send($eventdata);

}

/**
 * Silent exception handler.
 *
 * @param Exception $ex
 * @return void - does not return. Terminates execution!
 */
function enrol_payfast_itn_exception_handler($ex) {
    $info = get_exception_info($ex);

    $logerrmsg = "enrol_payfast ITN exception handler: ".$info->message;
    $logerrmsg .= ' Debug: '.$info->debuginfo."\n".format_backtrace($info->backtrace, true);

    error_log($logerrmsg);

    exit(0);
}

/**
 * @throws stored_file_creation_exception
 * @throws file_exception
 */
function enrol_payfast_itn_send_invoice($course, $user, $amount, $vat_amount, $vat_percentage): stored_file
{
    global $CFG;
    global $plugin_instance;

    $invoice_no = $user->id . '-'  . $course->id . '-' . $plugin_instance->id;
    $today = date('d F Y');

    $addressee = $user->firstname . ' ' . $user->lastname . ' (<a href="mailto:' . $user->email . '">' . $user->email . '</a>)<br>';
    if ($user->department) {
        $addressee .= $user->department . '<br>';
    }
    if ($user->institution) {
        $addressee .= $user->institution . '<br>';
    }
    if ($user->address) {
        $addressee .= $user->address . '<br>';
    }
    if ($user->city) {
        $addressee .= $user->city . '<br>';
    }
    if ($user->country) {
        $countries = get_string_manager()->get_list_of_countries();
        $addressee .= $countries[$user->country] . '<br>';
    }
    if (isset($user->profile['VAT']) && $user->profile['VAT']) {
        $addressee .= 'VAT no: ' . $user->profile['VAT'] . '<br>';
    }

    $pdf = new pdf();

    $pdf->SetMargins(0, 0, 0, true);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);

    $pdf->AddPage();
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(0);

    $pdf->Image('https://chartgov-public-files.s3.af-south-1.amazonaws.com/My+project-2.jpg', 0, 0, 210, 38);

    $style = '<style>html, body, h1, h2, h3, h4, h5, div, p { font-family: sans-serif; font-weight: normal; }</style>';

    $html = $style . '<div style="font-size: 13px;">Tax Invoice No:</div>';
    $pdf->writeHTMLCell(190, 5, 9, 49, $html);

    $html = $style . '<div style="font-size: 13px;">' . $invoice_no . '</div>';
    $pdf->writeHTMLCell(190, 5, 9, 54, $html);

    $html = $style . '<div style="font-size: 13px;">' . $today . '</div>';
    $pdf->writeHTMLCell(190, 5, 9, 59, $html);

    $html = $style . '<div style="font-size: 8.5px;">Riviera Office Park (Block C) 6-10 Riviera Road Killarney 2193</div>';
    $pdf->writeHTMLCell(90, 4, 9, 72, $html);

    $html = $style . '<table style="font-size: 8.5px; padding: 2px 6px; border: 1px solid #dddddd;">
        <tr><td colspan="2" style="background-color: #ededed;">Bank Details</td></tr>
        <tr><td colspan="2" style="border: 1px solid #dddddd;">Direct Deposit and EFT is not available at this time<br></td></tr>
        <tr><td style="border: 1px solid #dddddd;">Your Unique Reference Number</td><td style="border: 1px solid #dddddd;">' . $invoice_no . '</td></tr>
    </table>';
    $pdf->writeHTMLCell(100, 4, 101, 71, $html);

    $html = $style . '<div style="font-size: 8.5px;">PO Box 3146 Houghton 2041<br>Tel No. 0115514000<br>Reg No. 1972/000007/08<br>VAT no: 4860117938<br><a href="mailto:debtors@chartgov.co.za">debtors@chartgov.co.za</a></div>';
    $pdf->writeHTMLCell(90, 4, 9, 80, $html);

    $html = $style . '<div style="padding-left: 4px;">
        <table style="padding: 2px 6px; border: 1px solid #dddddd;">
            <tr style="font-size: 10px;">
                <td style="width: 17%; text-align: center; border: 1px solid #dddddd;">Date</td>
                <td style="width: 70%; text-align: center; border: 1px solid #dddddd;">Description</td>
                <td style="width: 13%; text-align: center; border: 1px solid #dddddd;">Amount</td>
            </tr>
            <tr style="font-size: 8.5px;">
                <td style="text-align: center; border: 1px solid #dddddd;"><br><br>' . $today . '<br><br><br><br><br><br><br><br><br><br><br></td>
                <td style="text-align: left; border: 1px solid #dddddd;"><br><br>' . $course->fullname . '</td>
                <td style="text-align: right; border: 1px solid #dddddd;"><br><br>R ' . format_float($amount - $vat_amount, 2) . '</td>
            </tr>
            <tr style="font-size: 8.5px;">
                <td colspan="2" style="text-align: right; border: 1px solid #dddddd;">' . $vat_percentage . '% VAT</td>
                <td style="text-align: right; border: 1px solid #dddddd;">R ' . $vat_amount . '</td>
            </tr>
            <tr style="font-size: 8.5px;">
                <td colspan="2" style="text-align: right; border: 1px solid #dddddd;">TOTAL</td>
                <td style="background-color: #dedede; font-weight: bold; text-align: right; border: 1px solid #dddddd;">R ' . format_float($amount, 2) . '</td>
            </tr>
        </table>
    ';
    $pdf->writeHTMLCell(190, 40, 9, 150, $html);

    $html = $style . '<div style="font-size: 10px;"><span style="font-weight: bold;">TO:</span><br>' . $addressee . '</div>';
    $pdf->writeHTMLCell(90, 4, 9, 106, $html);

    $content = $pdf->Output('', 'S');

    $file = new stdClass;

    $usercontext = context_user::instance($user->id);

    $file->contextid = $usercontext->id;
    $file->component = 'user';
    $file->filearea  = 'private';
    $file->itemid    = 0;
    $file->filepath  = '/invoices/';
    $file->filename  = 'invoice_' . $course->id . '_'. $user->id . '_' . date('ymdHi') . '.pdf';

    return get_file_storage()->create_file_from_string($file, $content);
}
