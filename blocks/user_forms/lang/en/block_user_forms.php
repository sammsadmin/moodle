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

$string['pluginname'] = 'User forms';
//New student registration
//Fields, NB: used in db so it should not be changed
$string['student_registration_title'] = 'Student Registration';
$string['student_registration_heading'] = 'Congratulations for deciding to become a student of the Institute.';
$string['upload_cert_id'] = 'Please upload a certified copy of your ID or passport:';
$string['upload_cert_highest_schooling'] = 'Please upload a certified copy of your highest schooling qualification (e.g. Grade 9, 10, 11, 12):';
$string['upload_cert_highest_education'] = 'Please upload a certified copy of your highest tertiary education qualification (e.g. diploma or degree):';
$string['upload_cert_marriage'] = 'Please upload a certified copy of your marriage certificate, if applicable:';
$string['investigated_charged_dishonesty'] = 'Have you ever been investigated and/or charged and/or convicted of any offence resulting from dishonesty, corruption, fraud, theft, perjury, misrepresentation, and/or embezzlement?';
$string['estate_sequestrated'] = 'Has your estate been provisionally or finally sequestrated in any jurisdiction?';
$string['party_arrangement_compromise_creditors'] = 'Have you at any time been a party to a scheme of arrangement or made any other form of compromise with your creditors?';
$string['guilty_disciplinary_proceedings'] = 'Have you ever been found guilty in disciplinary proceedings, by an employer or professional body, due to dishonest activities?';
$string['barred_professional'] = 'Have you ever previously or currently been barred from entry into any other professional body?';
$string['civil_judgements'] = 'Have you, at any time, had civil judgements either against you and/or involving you, including as a third party?';
$string['litigation_professional_capacity'] = 'Are you currently the subject of pending litigation and/or investigations in your professional capacity and /or conduct on the grounds of corruption, fraud, theft, embezzlement, perjury, and/ or misrepresentation, including those where you are a third party?';
$string['allegations_professional_capacity'] = 'Have you been in the past or are you currently the subject of allegations in your professional capacity which may reasonably affect the integrity of the professional standards required of a Company Secretary, Governance Professional or Governance Practitioner, which allegations may include deceit, dishonesty, misconduct and/or deception?';
$string['office_trust_grounds_misconduct'] = 'Have you ever been removed from an office of trust, on the grounds of misconduct?';
$string['yes_institute_supporting_doc'] = 'If you have answered yes to any of the above, please provide the Institute with supporting documentation for further processing.';
$string['certify_answers_correct'] = 'I certify that my answers given to the above questions are true and correct.';
$string['read_student_handbook'] = 'I have read the Student Handbook';
$string['read_student_policies'] = 'I have read the Student Policies Manual';
$string['agree_terms_cgisa'] = 'I agree to the terms of the CGISA Disclaimer';
$string['pluginname_desc'] = 'User Forms';

//First-Time Exam Registration
$string['first_time_exam_registration_title'] = 'First-Time Exam Registration';
$string['first_time_exam_registration_top_heading'] = 'Exam Registration: ';
$string['first_time_exam_registration_heading'] = 'Please mark the course modules to be written in the check boxes below.
Once you have paid the required fees, the CGISA will process your registration and notify you by email of the results.
Note that you will not be able access past papers or other materials if your account is not paid in full, and you will not receive your results.';
$string['registration_period_exam_session_open'] = 'The registration period for the {$a->exam_period} exam session is now open';
$string['the_registration_period_for_the'] = 'The registration period for the';
$string['exam_session_is_now_open'] = 'exam session is now open';
$string['closing_date_registration'] = 'The closing date for registration is ';
$string['please_mark_course_modules_checkboxes_below'] = 'Please mark the course modules to be written in the check boxes below.';
$string['once_paid_required_fees_process_heading_text'] = 'Once you have paid the required fees, the CGISA will process your registration and notify you by email of the results.';
$string['note_will_not_able_access_papers_not_paid_full_heading_text'] = 'Note that you will not be able access past papers or other materials if your account is not paid in full, and you will not receive your results.';
//Heading End

//Fields, NB: used in db so it should not be changed
$string['form2_upload_cert_id'] = 'Please upload a certified copy of your ID or passport:';
$string['form2_upload_cert_highest_qualification'] = 'Please upload a certified copy of your highest qualification:';
$string['select_lk_tuition_provider'] = 'Tuition Provider:';
$string['select_lk_venue'] = 'Venue:';

//Application for Exemption
$string['application_for_exemption_title'] = 'Application for Exemption';
$string['application_exemption_registration_heading'] = 'Please mark the course modules for which you are seeking for exemption.
Once you have paid the required fees, the CGISA will process your application and notify you by email of the results.';

//Common
$string['declaration'] = 'Declaration';
$string['declaration_applicant_acknowledge_conditions'] = 'I, the applicant acknowledges that by submitting my application for processing I have (a) read, accepted, and held myself bound by the admissions policy and all other student policies which seek to uphold the core values of diligence, honesty, and integrity of the Institute; and (b) accepted that I and the Institute are bound by these policies in processing my application for registration and admission.';
$string['answered_any_above_doc_provide'] = 'If you have answered yes to any of the above, please send an email to the Institute and attach supporting documentation for further processing';
$string['checkboxrequired'] = 'Checkbox is required';

$string['error_submission'] = 'Error occurred while submitting';

//Emails
$string['application_for_student_email_text'] = 'Dear {$a->firstname} {$a->lastname}

User {$a->user_id} has submitted an application to become a student.

Link to application: {$a->link_to_application}

Regards
CGISA';
$string['application_for_student_email_text_student'] = 'Dear {$a->firstname} {$a->lastname}

You have submitted an application to become a student.

Link to application: {$a->link_to_application}

Regards
CGISA';
$string['application_for_student_email_html'] = 'Dear {$a->firstname} {$a->lastname}
<p>
User {$a->user_id} has submitted an application to become a student.
</p>
<p>
Link to application: {$a->link}
</p>
<p>
Regards<br />
CGISA
</p>';
$string['application_for_student_email_subject'] = 'Application for student submission';

$string['first_time_exam_reg_email_text'] = 'Dear {$a->firstname} {$a->lastname}

User {$a->user_id} has submitted an application to become a student.

Link to application: {$a->link_to_application}

Regards
CGISA';
$string['first_time_exam_reg_email_html'] = 'Dear {$a->firstname} {$a->lastname}
<p>
User {$a->user_id} has submitted a first time exam registration.
</p>
<p>
Link to first time registration: {$a->link}
</p>
<p>
Regards<br />
CGISA
</p>';
$string['first_time_exam_reg_email_subject'] = 'First Time Exam Registration';

$string['application_for_student_email_subject_student'] = 'Application to become a student';

$string['application_for_student_email_html_student'] = 'Dear {$a->firstname} {$a->lastname}
<p>
You have submitted an application to become a student.
</p>
<p>
Link to application to become a student: {$a->link}
</p>
<p>
Regards<br />
CGISA
</p>';
$string['first_time_exam_reg_email_subject_student'] = 'First Time Exam Registration';
$string['first_time_exam_reg_email_text_student'] = 'Dear {$a->firstname} {$a->lastname}

You have submitted a first time exam registration

Link to application: {$a->link_to_application}

Regards
CGISA';
$string['first_time_exam_reg_email_html_student'] = 'Dear {$a->firstname} {$a->lastname}
<p>
You have submitted a first time exam registration. 
</p>
<p>
Link to first time exam registration: {$a->link}
</p>
<p>
Regards<br />
CGISA
</p>';;

// settings strings
$string['admin_emails'] = 'Admin email addresses';
$string['admin_emails_desc'] = 'Specify the email addresses of the users who need to receive email notifications separated by semicolon';
$string['update_providers'] = 'Update user forms providers';
$string['update_venues'] = 'Update user forms venues';
$string['update_available_modules'] = 'Update available modules';
$string['update_sessions'] = 'Update year exam sessions';
$string['endpoint_url'] = 'Endpoint URL';
$string['endpoint_url_desc'] = 'Enter the endpoint url for the API calls "exclude trailing slash" (eg. https://api.chartgov.co.za)';
$string['may_exam_reg_endate'] = 'May Exam registration end date';
$string['may_exam_reg_endate_desc'] = "Enter the exam registration end date. Example:'31 March'";
$string['oct_exam_reg_endate'] = 'October Exam registration end date';
$string['oct_exam_reg_endate_desc'] = "Enter the exam registration end date. Example:'31 August'";
    


