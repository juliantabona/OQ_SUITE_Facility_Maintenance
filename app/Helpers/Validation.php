<?php

/*  Validation for when creating a company.This could
 *  be the users company, client, or contractor.
 */
function oq_company_create_v_rules()
{
    return $rules = [
        //  General Rules
        'new_company_name' => 'required',
        'new_company_phone_ext' => 'max:3',
        'new_company_phone_num' => 'max:13',

        // Rules for Images/Files
        'new_company_logo' => 'mimes:jpeg,jpg,png,gif|max:2000',  // max 2000Kb/2Mb
        'new_company_quote' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000Kb/2Mb
    ];
}

function oq_company_create_v_msgs()
{
    return $messages = [
        //  General rule messages
        'new_company_name.required' => 'Enter company name',
        'new_company_name.max' => 'Company name cannot be more than 255 characters',
        'new_company_name.min' => 'Company name must be atleast 3 characters',
        'new_company_email.unique' => 'This company email is already being used',
        'new_company_phone_ext.max' => 'Company phone number extension cannot be more than 3 characters',
        'new_company_phone_num.max' => 'Company phone number cannot be more than 13 characters',

        //  Rules messages for Images/Files
        'new_company_logo.mimes' => 'Company logo must be an image format e.g) jpeg,jpg,png,gif',
        'new_company_logo.max' => 'Company logo should not be more than 2MB in size',
      ];
}
