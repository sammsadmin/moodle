<?php

function create_select_array($i){
    foreach($i as $key => $value){
        $a[$value] = $value;
    }
    return $a;
}

function update_fields($data){
    global $DB, $USER, $CFG;
    $user_data = $DB->get_record('user', ['id' => $USER->id]);
    $user_data->idnumber        = $data["idnumber"];
    $user_data->firstname       = $data["firstname"];
    $user_data->lastname        = $data["lastname"];
    $user_data->email           = $data["email"];
    $user_data->phone1          = $data["phone1"];
    $user_data->phone2          = $data["phone2"];
    
    $DB->update_record('user', $user_data);

    $custom_fields = new stdClass();
    $custom_fields->id = $USER->id;
    foreach($data as $key => $value){
        if(substr($key, 0, 14) == 'profile_field_'){
            $field = str_replace('profile_field_', '', $key);
            $datatype = $DB->get_record('user_info_field', ['shortname' => $field]);
            if($datatype->datatype == 'datetime'){
                $date = strtotime($value['day'].'/'.$value['month'].'/'.$value['year']);
                $custom_fields->$key = $date;
            }elseif($datatype->datatype == 'file'){
            }else{
                $custom_fields->$key = $value;
            }
        }
    }
    profile_save_data($custom_fields);
    return $custom_fields;
}

function update_profile_field_menus($field_shortname){
    global $DB;
    $endpoint_url = get_config('local_maintain_information', 'endpoint_url');

    switch ($field_shortname) {
        case "nationality":
            $name = 'countries';
            $data_field = 'name';
            break;
        case "address_country":
            $name = 'countries';
            $data_field = 'name';
            break;
        case "address_province":
            $name = 'provinces';
            $data_field = 'name';
            break;
        case "postal_province":
            $name = 'provinces';
            $data_field = 'name';
            break;
        case "postal_country":
            $name = 'countries';
            $data_field = 'name';
            break;
        case "education_province":
            $name = 'provinces';
            $data_field = 'name';
            break;
        case "education_country":
            $name = 'countries';
            $data_field = 'name';
            break;
        case "population_group":
            $name = 'populationGroups';
            $data_field = 'description';
            break;
        case "employment_job_title":
            $name = 'jobTitles';
            $data_field = 'description';
            break;
        case "employment_industry":
            $name = 'industryTypes';
            $data_field = 'description';
            break;
        case "home_language":
            $name = 'languages';
            $data_field = 'description';
            break;
        case "gender":
            $name = 'genders';
            $data_field = 'description';
            break;
        default:
            echo null;
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $endpoint_url.'/'.$name,
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
    $menus = json_decode($response);

    if(!empty($menus)){
        $menu_name = [];

        foreach($menus as $m){
            $menu_name[] = $m->$data_field;
        }

        sort($menu_name);

        $param1 = 'Select one'."\n";

        foreach($menu_name as $name){
            $param1 .= $name."\n";
        }

        $profile_field = $DB->get_record('user_info_field', ['shortname' => $field_shortname, 'datatype' => 'menu']);
        if($profile_field){
            $profile_field->defaultdata = 'Select one';
            $profile_field->param1 = $param1;
            $DB->update_record('user_info_field', $profile_field);
        }
    }

    return $menus;
}