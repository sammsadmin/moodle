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
 * Plugin strings are defined here.
 *
 * @package     local_maintain_information
 * @category    string
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Maintain information';
$string['title'] = 'Maintain personal information';
$string['welcome'] = 'Welcome {$a->fullname}';
$string['region'] = 'region';
$string['personal_info'] = 'Personal Information';
$string['profiletitle'] = 'Title';
$string['dateofbirth'] = 'Date of birth';
$string['southafricanid'] = 'South African ID';
$string['passport'] = 'Passport';
$string['idtype'] = 'ID Type';
$string['nationality'] = 'Nationality';
$string['population_group'] = 'Population group';
$string['home_language'] = 'Home language';
$string['gender'] = 'Gender';
$string['disability'] = 'Disability';
$string['disability_certificate'] = 'If you registered a disability, please upload proof (e.g., medical certificate, psychologist’s report, etc.)';
$string['contact_details'] = 'Contact details';
$string['cellphone_number'] = 'Cell phone number';
$string['phone_number'] = 'Home phone number';
$string['fax_number'] = 'Fax number';
$string['alternate_email_address'] = 'Alternative email address';
$string['work_phone_number'] = 'Work phone number';
$string['alternate_phone_number'] = 'Alternative phone number';
$string['address_details'] = 'Address details';
$string['physical_address_details'] = 'Physical address';
$string['address_first_line'] = 'First line';
$string['address_second_line'] = 'Second line';
$string['address_suburb'] = 'Suburb';
$string['address_city_town'] = 'City / Town';
$string['address_postal_code'] = 'Postal code';
$string['address_province'] = 'Province';
$string['address_country'] = 'Country';
$string['postal_address_details'] = 'Postal address';
$string['postal_first_line'] = 'First line';
$string['postal_second_line'] = 'Second line';
$string['postal_suburb'] = 'Suburb';
$string['postal_city_town'] = 'City / Town';
$string['postal_postal_code'] = 'Postal code';
$string['postal_province'] = 'Province';
$string['postal_country'] = 'Country';
$string['highest_education_qualification'] = 'Highest education qualification';
$string['education_description'] = 'Description';
$string['education_nqf_level'] = 'NQF Level';
$string['education_year_completed'] = 'Year completed';
$string['education_city_town'] = 'City / Town';
$string['education_postal_code'] = 'Postal code';
$string['education_province'] = 'Province';
$string['education_country'] = 'Country';
$string['current_employment_info'] = 'Current employment information';
$string['employment_employer'] = 'Employer';
$string['employment_vat_no'] = 'VAT number';
$string['employment_position_held'] = 'Position held';
$string['employment_job_title'] = 'Job title';
$string['employment_industry'] = 'Industry';
$string['employment_start_date'] = 'Start date';
$string['hear_about_us'] = 'Where did you hear about us?';
$string['hear_about_us_mailer'] = 'Mailers';
$string['hear_about_us_conference'] = 'Conference';
$string['hear_about_us_seminars'] = 'Seminars and/or webinars';
$string['hear_about_us_publications'] = 'Publications';
$string['hear_about_us_boardroom'] = 'Boardroom';
$string['hear_about_us_business_day'] = 'Business day';
$string['hear_about_us_without_prejudice'] = 'Without prejudice';
$string['hear_about_us_website'] = 'Website';
$string['hear_about_us_social_media'] = 'Social media';
$string['hear_about_us_facebook'] = 'Facebook';
$string['hear_about_us_linkedin'] = 'LinkedIn';
$string['hear_about_us_posters'] = 'Posters';
$string['hear_about_us_student'] = 'Fellow student / member';
$string['hear_about_us_other'] = 'Other (please elaborate)';
$string['hear_about_us_other_desc'] = 'Other description';
$string['hear_about_us_career_guidance'] = 'Career guidance';
$string['consent_form_header'] = 'Permission to Process Personal Information';
$string['consent_form'] = '<p>By ticking the box below,</p>
<ul>
<li>I acknowledge that completion and submission of this form gives rise to a contract between me and CGISA in terms of which CGISA must fulfil the obligations requested by me in this form, and I must render counter performance as provided for in this form.</li>
<li>I consent to CGISA processing my personal information (including the information provided by me to CGISA in this form), in order for CGISA to fulfil its obligations to me pursuant to this form and agree that CGISA may send relevant communications to me for any purposes referred to in this document and/or in connection with CGISA’s activities.</li>
<li>I acknowledge that processing my personal information is in my legitimate interests and is necessary in order for CGISA to carry out its functions as requested by me in terms of this form.</li>
<li>I agree to the terms of CGISA’s privacy policy (available for download here) which sets out, inter alia, further information as to the personal information which CGISA processes, the purpose for such processing and my rights as a data subject.</li>
<li>By agreeing to the terms of this consent form, I expressly consent to the processing of my information for marketing purposes and know and understand that by agreeing to same that I may receive marketing materials in the form of SMS’s, emails, and the like from CGISA.</li>
</ul>';

$string['endpoint_url'] = 'Endpoint URL';
$string['endpoint_url_desc'] = 'Enter the endpoint url for the API calls "exclude trailing slash" (eg. https://api.chartgov.co.za)';

// schedule task stings
$string['update_address_country'] = 'Update address countries';
$string['update_address_province'] = 'Update address provinces';
$string['update_education_country'] = 'Update education countries';
$string['update_education_province'] = 'Update education provinces';
$string['update_postal_country'] = 'Update postal countries';
$string['update_postal_province'] = 'Update postal provinces';
$string['update_employment_industry'] = 'Update employment industries';
$string['update_employment_job_title'] = 'Update employment job titles';
$string['update_population_group'] = 'Update population group';
$string['update_nationality'] = 'Update nationality list';
$string['update_home_language'] = 'Update home language list';
$string['update_gender'] = 'Update gender list';
