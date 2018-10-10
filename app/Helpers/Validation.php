<?php

/**************************************************************
***************************************************************
***************************************************************
    COMPANY RELATED VALIDATION
***************************************************************
***************************************************************
**************************************************************/

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
        oq_getRules(oq_document_create_v_rules()),
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

        //  Rule messages for Images/Files
        oq_getRules(oq_document_create_v_msgs()),
      ];
}

/**************************************************************
***************************************************************
***************************************************************
    DOCUMENT RELATED VALIDATION [Images/Files]
***************************************************************
***************************************************************
**************************************************************/

function oq_document_create_v_rules()
{
    return $rules = [
        // Rules for Images/Files
        'new_company_logo' => 'mimes:jpeg,jpg,png,gif|max:2000',  // max 2000Kb/2Mb
        'new_company_quote' => 'mimes:jpeg,jpg,png,pdf|max:2000', // max 2000Kb/2Mb
        'new_jobcard_image' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000Kb/2Mb
    ];
}

function oq_document_create_v_msgs()
{
    return $rules = [
        // Rules for Images/Files
        'new_company_logo.mimes' => 'Company logo must be a valid image format e.g) jpeg,jpg,png,gif',
        'new_company_logo.max' => 'Company logo should not be more than 2MB in size',
        'new_company_quote.mimes' => 'Company quotation must be a valid file format e.g) jpeg,jpg,png,pdf',
        'new_company_quote.max' => 'Company quotation should not be more than 2MB in size',
        'new_jobcard_image.mimes' => 'Jobcard image must be a valid image format e.g) jpeg,jpg,png,gif',
        'new_jobcard_image.max' => 'Jobcard image should not be more than 2MB in size',
    ];
}

function oq_getRules($rules)
{
    //  Will remove the square brackets of the array [key => value, key2 => value2]
    //  So that we are only left with the strings key=>value, key2=>value2
    return join(',', $rules);
}

/**************************************************************
***************************************************************
***************************************************************
    JOBCARD RELATED VALIDATION
***************************************************************
***************************************************************
**************************************************************/

/*  Validation for when creating a company.This could
 *  be the users company, client, or contractor.
 */
function oq_jobcard_create_v_rules($user)
{
    return $rules = [
        //  General Rules
        'title' => 'required|max:255|min:3',
        'description' => 'required|max:255|min:3',
        'start_date' => 'date_format:"Y-m-d"|required',
        'end_date' => 'date_format:"Y-m-d"|required|after:today',

        /*  Advanced Validation
        *
        *  Note that when creating new priorities, cost centers, categories and branches,
        *  we want to make sure we do not make any repeated values since they will confuse
        *  users e.g) having priority Low, Medium, Medium, High. The "medium" values is duplicated
        *  and undesirable outcome. To prevent this we must first check and confirm that for the
        *  current company the user does not already have records using the same names.
        */

        //  The priority (name & company_id) must be unique per row
        'priority' => 'required|unique:priorities,name,null,who_created_id,priority_id,'.$user->companyBranch->company->id.',priority_type,company',
        //  The cost center (name & company_id) must be unique per row
        'cost_center' => 'required|unique:costcenters,name,null,who_created_id,costcenter_id,'.$user->companyBranch->company->id.',costcenter_type,company',
        //  The category (name & company_id) must be unique per row
        'category' => 'required|unique:categories,name,null,who_created_id,category_id,'.$user->companyBranch->company->id.',category_type,company',
        //  The branch (name & company_id) must be unique per row
        'branch' => 'required|unique:company_branches,destination,null,who_created_id,company_id,'.$user->companyBranch->company->id,

        // Rules for Images/Files
        oq_getRules(oq_document_create_v_rules()),
    ];
}

function oq_jobcard_create_v_msgs($request)
{
    return $messages = [
        //  General rule messages
        'title.required' => 'Enter your title',
        'title.max' => 'Title name cannot be more than 255 characters',
        'title.min' => 'Title name must be atleast 3 characters',
        'description.required' => 'Enter your description',
        'description.max' => 'Description cannot be more than 255 characters',
        'description.min' => 'Description must be atleast 3 characters',
        'start_date.required' => 'Enter job start date',
        'start_date.date' => 'Enter a valid start date',
        'end_date.required' => 'Enter job end date',
        'end_date.date' => 'Enter a valid end date',
        'end_date.after' => 'End date must be a future date',
        'priority.required' => 'Select or create a new priority level',
        'priority.unique' => 'The new priority ('.$request->input('priority').') you tried to create already exists',
        'cost_center.required' => 'Select or create a new cost center',
        'cost_center.unique' => 'The new cost center ('.$request->input('cost_center').') you tried to create already exists',
        'category.required' => 'Select or create a new category',
        'category.unique' => 'The new category ('.$request->input('category').') you tried to create already exists',
        'branch.required' => 'Select or create a new company branch',
        'branch.unique' => 'The new company branch ('.$request->input('branch').') you tried to create already exists',

        //  Rule messages for Images/Files
        oq_getRules(oq_document_create_v_msgs()),
      ];
}
