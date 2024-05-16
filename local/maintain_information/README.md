# Maintain information #

TODO Describe the plugin shortly here.
The plugin creates a custom page for the user to manage their profile / personal information on Moodle

TODO Provide more detailed description here.
To display fields on the custom page you would need to add custom user profile fields to the platform and use the shortnames below with their type
title - Drop-down menu
date_of_birth - Date/Time
idtype - Drop-down menu
nationality - Drop-down menu
population_group - Drop-down menu
home_language - Drop-down menu
gender - Drop-down menu
disability - Drop-down menu
disability_cert - File upload (additional plugin from Moodle's plugin library to upload file in profile)
fax_number - Text input
alternate_email_address - Text input
work_phone_number - Text input
alternate_phone_number - Text input
address_first_line - Text input
address_second_line - Text input
address_suburb - Text input
address_city_town - Text input
address_postal_code - Text input
address_province - Drop-down menu
address_country - Drop-down menu
postal_first_line - Text input
postal_second_line - Text input
postal_suburb - Text input
postal_city_town - Text input
postal_postal_code - Text input
postal_province - Drop-down menu
postal_country - Drop-down menu
education_description - Text input
education_nqf_level - Text input
education_year_completed - Text input
education_city_town - Text input
education_postal_code - Text input
education_province - Drop-down menu
education_country - Drop-down menu
employment_employer - Text input
employment_vat_no - Text input
employment_position_held - Text input
employment_job_title - Drop-down menu
employment_industry - Drop-down menu
employment_start_date - Date/Time
hear_about_us_mailer - Checkbox
hear_about_us_conference - Checkbox
hear_about_us_seminars - Checkbox
hear_about_us_publications - Checkbox
hear_about_us_boardroom - Checkbox
hear_about_us_business_day - Checkbox
hear_about_us_without_prejudice - Checkbox
hear_about_us_website - Checkbox
hear_about_us_social_media - Checkbox
hear_about_us_facebook - Checkbox
hear_about_us_linkedin - Checkbox
hear_about_us_posters - Checkbox
hear_about_us_student - Checkbox
hear_about_us_career_guidance - Checkbox
hear_about_us_other - Checkbox
hear_about_us_other_desc - Text input
consent_form - Checkbox

If any of the above does not show on the maintain personal information page then verify the shortname or if you want to remove a field from the maintain personal information page then either remove the custom profile field or change the shortname

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/maintain_information

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2023 Mohamed Shariff <mohamed@ttro.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
