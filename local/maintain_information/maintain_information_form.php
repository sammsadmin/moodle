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


if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    //  It must be included from a Moodle page.
}

require_once($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot.'/lib/filelib.php');
require_once('locallib.php');

class maintain_information_form extends moodleform {

    /**
     * Define the form.
     */
    public function definition () {
        global $DB, $CFG, $COURSE, $USER;
        
        $mform = $this->_form;
        $filemanageropts = $this->_customdata['filemanageropts'];
        $usernotfullysetup = user_not_fully_set_up($USER);
        $user = $DB->get_record('user', ['id' => $USER->id]);
        $extra_fields = profile_user_record($USER->id);

        

        // get profile fields to check if they exist to create the field in the form
        $profile_fields = $DB->get_records('user_info_field');

        // personal information start here

        $a = new stdClass();
        $a->fullname = $user->firstname.' '.$user->lastname;
        
        $mform->addElement('html', '<div class="welcome_message">');
        $mform->addElement('html', get_string('welcome', 'local_maintain_information', $a));
        $mform->addElement('html', '</div>');

        $mform->addElement('html', '<div class="personal_information">');
        $mform->addElement('html', '<div class="header">');
        $mform->addElement('html', get_string('personal_info', 'local_maintain_information'));
        $mform->addElement('html', '</div>');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'title'){
                $title = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_title', get_string('profiletitle', 'local_maintain_information'), $title);
                foreach($title as $key => $value){
                    if($value == $extra_fields->title){
                        $mform->getElement('profile_field_title')->setSelected($key);
                    }
                }
                $mform->addRule('profile_field_title', get_string('missingtitle'), 'required', null, 'client');
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'date_of_birth'){
                $date_of_birth = $extra_fields->date_of_birth;
                $mform->addElement('date_selector', 'profile_field_date_of_birth', get_string('dateofbirth', 'local_maintain_information'));
                $mform->setDefault('profile_field_date_of_birth',  $date_of_birth);
            }
        }

        $mform->addElement('text', 'firstname', get_string('firstname'));
        $mform->setType('firstname', PARAM_TEXT);
        $mform->setDefault('firstname', $user->firstname);
        $mform->addRule('firstname', get_string('missingfirstname'), 'required', null, 'client');

        $mform->addElement('text', 'lastname', get_string('lastname'));
        $mform->setType('lastname', PARAM_TEXT);
        $mform->setDefault('lastname', $user->lastname);
        $mform->addRule('lastname', get_string('missinglastname'), 'required', null, 'client');

        $mform->addElement('text', 'idnumber', get_string('idnumber'));
        $mform->setType('idnumber', PARAM_TEXT);
        $mform->setDefault('idnumber', $user->idnumber);

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'idtype'){
                $idtype_options = explode(PHP_EOL, $profile_field->param1);
                foreach($idtype_options as $key => $value){
                    $idtype[$value] = $value;
                }
                $radioarray=array();
                foreach($idtype_options as $key => $value){
                    $radioarray[] = $mform->createElement('radio', 'profile_field_idtype', '', $value, $value);
                    if($value == $extra_fields->idtype){
                        $mform->setDefault('profile_field_idtype', $extra_fields->idtype);
                    }
                }
                $mform->addGroup($radioarray, 'radioar', get_string('idtype', 'local_maintain_information'), array(' '), false);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'nationality'){
                $nationality = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_nationality', get_string('nationality', 'local_maintain_information'), $nationality);
                foreach($nationality as $key => $value){
                    if($value == $extra_fields->nationality){
                        $mform->getElement('profile_field_nationality')->setSelected($key);
                    }
                }
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'population_group'){
                $population_group = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_population_group', get_string('population_group', 'local_maintain_information'), $population_group);
                foreach($population_group as $key => $value){
                    if($value == $extra_fields->population_group){
                        $mform->getElement('profile_field_population_group')->setSelected($key);
                    }
                }
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'home_language'){
                $home_language = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_home_language', get_string('home_language', 'local_maintain_information'), $home_language);
                foreach($home_language as $key => $value){
                    if($value == $extra_fields->home_language){
                        $mform->getElement('profile_field_home_language')->setSelected($key);
                    }
                }
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'gender'){
                $gender = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_gender', get_string('gender', 'local_maintain_information'), $gender);
                foreach($gender as $key => $value){
                    if($value == $extra_fields->gender){
                        $mform->getElement('profile_field_gender')->setSelected($key);
                    }
                }
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'disability'){
                $disability = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_disability', get_string('disability', 'local_maintain_information'), $disability);
                foreach($disability as $key => $value){
                    if($value == $extra_fields->disability){
                        $mform->getElement('profile_field_disability')->setSelected($key);
                    }
                }
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'disability_cert'){
                $mform->addElement('filemanager', 'profile_field_disability_cert', get_string('disability_certificate', 'local_maintain_information'), null, $filemanageropts);
            }
        }

        // personal information ends here

        // contact details start here

        $mform->addElement('html', '</div>');

        $mform->addElement('html', '<div class="contact_details">');
        $mform->addElement('html', '<div class="header">');
        $mform->addElement('html', get_string('contact_details', 'local_maintain_information'));
        $mform->addElement('html', '</div>');

        $mform->addElement('text', 'email', get_string('email'));
        $mform->setType('email', PARAM_TEXT);
        $mform->setDefault('email', $user->email);
        $mform->addRule('email', get_string('required'), 'required', null, 'client');
        
        $mform->addElement('text', 'phone2', get_string('cellphone_number', 'local_maintain_information'));
        $mform->setType('phone2', PARAM_TEXT);
        $mform->setDefault('phone2', $user->phone2);
        
        $mform->addElement('text', 'phone1', get_string('phone_number', 'local_maintain_information'));
        $mform->setType('phone1', PARAM_TEXT);
        $mform->setDefault('phone1', $user->phone2);

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'fax_number'){
                $mform->addElement('text', 'profile_field_fax_number', get_string('fax_number', 'local_maintain_information'));
                $mform->setType('profile_field_fax_number', PARAM_TEXT);
                $mform->setDefault('profile_field_fax_number', $extra_fields->fax_number);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'alternate_email_address'){
                $mform->addElement('text', 'profile_field_alternate_email_address', get_string('alternate_email_address', 'local_maintain_information'));
                $mform->setType('profile_field_alternate_email_address', PARAM_TEXT);
                $mform->setDefault('profile_field_alternate_email_address', $extra_fields->alternate_email_address);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'work_phone_number'){
                $mform->addElement('text', 'profile_field_work_phone_number', get_string('work_phone_number', 'local_maintain_information'));
                $mform->setType('profile_field_work_phone_number', PARAM_TEXT);
                $mform->setDefault('profile_field_work_phone_number', $extra_fields->work_phone_number);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'alternate_phone_number'){
                $mform->addElement('text', 'profile_field_alternate_phone_number', get_string('alternate_phone_number', 'local_maintain_information'));
                $mform->setType('profile_field_alternate_phone_number', PARAM_TEXT);
                $mform->setDefault('profile_field_alternate_phone_number', $extra_fields->alternate_phone_number);
            }
        }

        // contact details end here
        // address start here

        $mform->addElement('html', '</div>');

        $mform->addElement('html', '<div class="address_details">');
        $mform->addElement('html', '<div class="header">');
        $mform->addElement('html', get_string('address_details', 'local_maintain_information'));
        $mform->addElement('html', '</div>');

        $mform->addElement('html', '<div class="subheader address">');
        $mform->addElement('html', '<div class="address_header">'.get_string('physical_address_details', 'local_maintain_information').'</div>');
        
        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'address_first_line'){
                $mform->addElement('text', 'profile_field_address_first_line', get_string('address_first_line', 'local_maintain_information'));
                $mform->setType('profile_field_address_first_line', PARAM_TEXT);
                $mform->setDefault('profile_field_address_first_line', $extra_fields->address_first_line);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'address_second_line'){
                $mform->addElement('text', 'profile_field_address_second_line', get_string('address_second_line', 'local_maintain_information'));
                $mform->setType('profile_field_address_second_line', PARAM_TEXT);
                $mform->setDefault('profile_field_address_second_line', $extra_fields->address_second_line);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'address_suburb'){
                $mform->addElement('text', 'profile_field_address_suburb', get_string('address_suburb', 'local_maintain_information'));
                $mform->setType('profile_field_address_suburb', PARAM_TEXT);
                $mform->setDefault('profile_field_address_suburb', $extra_fields->address_suburb);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'address_city_town'){
                $mform->addElement('text', 'profile_field_address_city_town', get_string('address_city_town', 'local_maintain_information'));
                $mform->setType('profile_field_address_city_town', PARAM_TEXT);
                $mform->setDefault('profile_field_address_city_town', $extra_fields->address_city_town);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'address_postal_code'){
                $mform->addElement('text', 'profile_field_address_postal_code', get_string('address_postal_code', 'local_maintain_information'));
                $mform->setType('profile_field_address_postal_code', PARAM_TEXT);
                $mform->setDefault('profile_field_address_postal_code', $extra_fields->address_postal_code);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'address_province'){
                $address_province = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_address_province', get_string('address_province', 'local_maintain_information'), $address_province);
                foreach($address_province as $key => $value){
                    if($value == $extra_fields->address_province){
                        $mform->getElement('profile_field_address_province')->setSelected($key);
                    }
                }
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'address_country'){
                $address_country = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_address_country', get_string('address_country', 'local_maintain_information'), $address_country);
                foreach($address_country as $key => $value){
                    if($value == $extra_fields->address_country){
                        $mform->getElement('profile_field_address_country')->setSelected($key);
                    }
                }
            }
        }

        $mform->addElement('html', '</div>');

        // postal address starts here

        $mform->addElement('html', '<div class="subheader postal_address">');
        $mform->addElement('html', '<div class="address_header">'.get_string('postal_address_details', 'local_maintain_information').'</div>');
        
        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'postal_first_line'){
                $mform->addElement('text', 'profile_field_postal_first_line', get_string('postal_first_line', 'local_maintain_information'));
                $mform->setType('profile_field_postal_first_line', PARAM_TEXT);
                $mform->setDefault('profile_field_postal_first_line', $extra_fields->postal_first_line);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'postal_second_line'){
                $mform->addElement('text', 'profile_field_postal_second_line', get_string('postal_second_line', 'local_maintain_information'));
                $mform->setType('profile_field_postal_second_line', PARAM_TEXT);
                $mform->setDefault('profile_field_postal_second_line', $extra_fields->postal_second_line);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'postal_suburb'){
                $mform->addElement('text', 'profile_field_postal_suburb', get_string('postal_suburb', 'local_maintain_information'));
                $mform->setType('profile_field_postal_suburb', PARAM_TEXT);
                $mform->setDefault('profile_field_postal_suburb', $extra_fields->postal_suburb);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'postal_city_town'){
                $mform->addElement('text', 'profile_field_postal_city_town', get_string('postal_city_town', 'local_maintain_information'));
                $mform->setType('profile_field_postal_city_town', PARAM_TEXT);
                $mform->setDefault('profile_field_postal_city_town', $extra_fields->postal_city_town);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'postal_postal_code'){
                $mform->addElement('text', 'profile_field_postal_postal_code', get_string('postal_postal_code', 'local_maintain_information'));
                $mform->setType('profile_field_postal_postal_code', PARAM_TEXT);
                $mform->setDefault('profile_field_postal_postal_code', $extra_fields->postal_postal_code);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'postal_province'){
                $postal_province = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_postal_province', get_string('postal_province', 'local_maintain_information'), $postal_province);
                foreach($postal_province as $key => $value){
                    if($value == $extra_fields->postal_province){
                        $mform->getElement('profile_field_postal_province')->setSelected($key);
                    }
                }
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'postal_country'){
                $postal_country = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_postal_country', get_string('postal_country', 'local_maintain_information'), $postal_country);
                foreach($postal_country as $key => $value){
                    if($value == $extra_fields->postal_country){
                        $mform->getElement('profile_field_postal_country')->setSelected($key);
                    }
                }
            }
        }
        
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');

        // highest education qualification starts here
        $mform->addElement('html', '<div class="education">');
        $mform->addElement('html', '<div class="header">');
        $mform->addElement('html', get_string('highest_education_qualification', 'local_maintain_information'));
        $mform->addElement('html', '</div>');

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'education_description'){
                $mform->addElement('text', 'profile_field_education_description', get_string('education_description', 'local_maintain_information'));
                $mform->setType('profile_field_education_description', PARAM_TEXT);
                $mform->setDefault('profile_field_education_description', $extra_fields->education_description);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'education_nqf_level'){
                $mform->addElement('text', 'profile_field_education_nqf_level', get_string('education_nqf_level', 'local_maintain_information'));
                $mform->setType('profile_field_education_nqf_level', PARAM_TEXT);
                $mform->setDefault('profile_field_education_nqf_level', $extra_fields->education_nqf_level);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'education_year_completed'){
                $mform->addElement('text', 'profile_field_education_year_completed', get_string('education_year_completed', 'local_maintain_information'));
                $mform->setType('profile_field_education_year_completed', PARAM_TEXT);
                $mform->setDefault('profile_field_education_year_completed', $extra_fields->education_year_completed);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'education_city_town'){
                $mform->addElement('text', 'profile_field_education_city_town', get_string('education_city_town', 'local_maintain_information'));
                $mform->setType('profile_field_education_city_town', PARAM_TEXT);
                $mform->setDefault('profile_field_education_city_town', $extra_fields->education_city_town);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'education_postal_code'){
                $mform->addElement('text', 'profile_field_education_postal_code', get_string('education_postal_code', 'local_maintain_information'));
                $mform->setType('profile_field_education_postal_code', PARAM_TEXT);
                $mform->setDefault('profile_field_education_postal_code', $extra_fields->education_postal_code);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'education_province'){
                $education_province = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_education_province', get_string('education_province', 'local_maintain_information'), $education_province);
                foreach($education_province as $key => $value){
                    if($value == $extra_fields->education_province){
                        $mform->getElement('profile_field_education_province')->setSelected($key);
                    }
                }
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'education_country'){
                $education_country = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_education_country', get_string('education_country', 'local_maintain_information'), $education_country);
                foreach($education_country as $key => $value){
                    if($value == $extra_fields->education_country){
                        $mform->getElement('profile_field_education_country')->setSelected($key);
                    }
                }
            }
        }

        $mform->addElement('html', '</div>');

        // current employment
        $mform->addElement('html', '<div class="header">');
        $mform->addElement('html', get_string('current_employment_info', 'local_maintain_information'));
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div class="current_employment">');

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'employment_employer'){
                $mform->addElement('text', 'profile_field_employment_employer', get_string('employment_employer', 'local_maintain_information'));
                $mform->setType('profile_field_employment_employer', PARAM_TEXT);
                $mform->setDefault('profile_field_employment_employer', $extra_fields->employment_employer);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'employment_vat_no'){
                $mform->addElement('text', 'profile_field_employment_vat_no', get_string('employment_vat_no', 'local_maintain_information'));
                $mform->setType('profile_field_employment_vat_no', PARAM_TEXT);
                $mform->setDefault('profile_field_employment_vat_no', $extra_fields->employment_vat_no);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'employment_position_held'){
                $mform->addElement('text', 'profile_field_employment_position_held', get_string('employment_position_held', 'local_maintain_information'));
                $mform->setType('profile_field_employment_position_held', PARAM_TEXT);
                $mform->setDefault('profile_field_employment_position_held', $extra_fields->employment_position_held);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'employment_job_title'){
                $employment_job_title = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_employment_job_title', get_string('employment_job_title', 'local_maintain_information'), $employment_job_title);
                foreach($employment_job_title as $key => $value){
                    if($value == $extra_fields->employment_job_title){
                        $mform->getElement('profile_field_employment_job_title')->setSelected($key);
                    }
                }
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'employment_industry'){
                $employment_industry = create_select_array(explode(PHP_EOL, $profile_field->param1));
                $mform->addElement('select', 'profile_field_employment_industry', get_string('employment_industry', 'local_maintain_information'), $employment_industry);
                foreach($employment_industry as $key => $value){
                    if($value == $extra_fields->employment_industry){
                        $mform->getElement('profile_field_employment_industry')->setSelected($key);
                    }
                }
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'employment_start_date'){
                $employment_start_date = $extra_fields->employment_start_date;
                $mform->addElement('date_selector', 'profile_field_employment_start_date', get_string('employment_start_date', 'local_maintain_information'));
                $mform->setDefault('profile_field_employment_start_date',  $employment_start_date);
            }
        }

        $mform->addElement('html', '</div>');
        
        // hear about us
        $mform->addElement('html', '<div class="header">');
        $mform->addElement('html', get_string('hear_about_us', 'local_maintain_information'));
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div class="hear_about_us">');

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_mailer'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_mailer', get_string('hear_about_us_mailer', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_mailer', $extra_fields->hear_about_us_mailer);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_conference'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_conference', get_string('hear_about_us_conference', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_conference', $extra_fields->hear_about_us_conference);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_seminars'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_seminars', get_string('hear_about_us_seminars', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_seminars', $extra_fields->hear_about_us_seminars);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_publications'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_publications', get_string('hear_about_us_publications', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_publications', $extra_fields->hear_about_us_publications);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_boardroom'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_boardroom', get_string('hear_about_us_boardroom', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_boardroom', $extra_fields->hear_about_us_boardroom);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_business_day'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_business_day', get_string('hear_about_us_business_day', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_business_day', $extra_fields->hear_about_us_business_day);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_without_prejudice'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_without_prejudice', get_string('hear_about_us_without_prejudice', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_without_prejudice', $extra_fields->hear_about_us_without_prejudice);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_website'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_website', get_string('hear_about_us_website', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_website', $extra_fields->hear_about_us_website);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_social_media'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_social_media', get_string('hear_about_us_social_media', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_career_guidance', $extra_fields->hear_about_us_career_guidance);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_facebook'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_facebook', get_string('hear_about_us_facebook', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_facebook', $extra_fields->hear_about_us_facebook);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_linkedin'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_linkedin', get_string('hear_about_us_linkedin', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_linkedin', $extra_fields->hear_about_us_linkedin);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_posters'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_posters', get_string('hear_about_us_posters', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_posters', $extra_fields->hear_about_us_posters);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_student'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_student', get_string('hear_about_us_student', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_student', $extra_fields->hear_about_us_student);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_career_guidance'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_career_guidance', get_string('hear_about_us_career_guidance', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_career_guidance', $extra_fields->hear_about_us_career_guidance);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_other'){
                $mform->addElement('advcheckbox', 'profile_field_hear_about_us_other', get_string('hear_about_us_other', 'local_maintain_information'), '&nbsp;', array('group' => 1));
                $mform->setDefault('profile_field_hear_about_us_other', $extra_fields->hear_about_us_other);
            }
        }

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'hear_about_us_other_desc'){
                $mform->addElement('text', 'profile_field_hear_about_us_other_desc', get_string('hear_about_us_other_desc', 'local_maintain_information'));
                $mform->setType('profile_field_hear_about_us_other_desc', PARAM_TEXT);
                $mform->setDefault('profile_field_hear_about_us_other_desc', $extra_fields->hear_about_us_other_desc);
            }
        }

        $mform->addElement('html', '</div>');

        // consent form
        $mform->addElement('html', '<div class="header">');
        $mform->addElement('html', get_string('consent_form_header', 'local_maintain_information'));
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div class="consent_form">');
        $mform->addElement('html', get_string('consent_form', 'local_maintain_information'));
        $mform->addElement('html', '</div>');

        foreach($profile_fields as $profile_field){
            if($profile_field->shortname == 'consent_form'){
                $mform->addElement('advcheckbox', 'profile_field_consent_form', '&nbsp;', 'I consent to the above', array('group' => 1, 'required' => 'required'));
                $mform->setDefault('profile_field_consent_form', $extra_fields->consent_form);
                $mform->addRule('profile_field_consent_form', get_string('required'), 'required', null, 'client');

            }
        }
        
        $this->add_action_buttons();

    }
}

function local_maintain_information_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $DB, $USER;

    if ($context->contextlevel != CONTEXT_USER) {
        return false;
    }
    if (strpos($filearea, 'files_') !== 0) {
        return false;
    }

    require_login($course, false, $cm);

    $fieldid = substr($filearea, strlen('files_'));
    $field = $DB->get_record('user_info_field', array('id' => $fieldid));

    // If is allowed to see.
    if ($field->visible != PROFILE_VISIBLE_ALL) {
        if ($field->visible == PROFILE_VISIBLE_PRIVATE) {
            if ($context->instanceid != $USER->id) {
                if (!has_capability('moodle/user:viewalldetails', $context)) {
                    return false;
                }
            }
        } else if (!has_capability('moodle/user:viewalldetails', $context)) {
            return false;
        }
    }

    array_shift($args); // ignore revision - designed to prevent caching problems only

    $relativepath = implode('/', $args);
    $fullpath = "/{$context->id}/profilefield_file/$filearea/0/$relativepath";
    $fs = get_file_storage();
    if (!($file = $fs->get_file_by_hash(sha1($fullpath))) || $file->is_directory()) {
        return false;
    }

    // Force download
    send_stored_file($file, 0, 0, true);
}