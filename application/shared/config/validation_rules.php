<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* account registration form
*/

$config['account_registration'] = array(

	array('FirstName', 'First name', 'trim|required|regex_match[/^[a-zA-Z ]+$/]',
		array(
	        'regex_match' => '%s has invalid characters. Letters and space only.'
	    )),
	array('LastName', 'Last name', 'trim|required|regex_match[/^[a-zA-Z. ]+$/]',
		array(
	        'regex_match' => '%s has invalid characters. Letters and space only.'
	    )),
	array('MiddleName', 'Middle name', 'trim|regex_match[/^[a-zA-Z ]+$/]',
		array(
	        'regex_match' => '%s has invalid characters. Letters and space only.'
	    )),
	array('EmailAddress', 'Email address', 'trim|required|valid_email|min_length[5]',//|is_unique[UserAccountInformation.EmailAddress]
	    array(
	        'is_unique' => 'This %s already exists.'
	    )),
	// array('Username', 'Username', 'trim|required|alpha_numeric|min_length[5]|is_unique[UserAccountInformation.Username]',
	// 	array(
	//         'is_unique' => 'This %s already exists.'
	//     )),
	// array('Password', 'Password', 'required|min_length[6]|max_length[16]'),
	// array('ConfirmPassword', 'Password confirmation', 'required|matches[Password]'),
	array('MunicipalityCityID', 'City or Municipality', 'trim|required|numeric'),
	array('BarangayID', 'Barangay', 'trim|required|numeric'),


);

$config['change_password'] = array(
	array('currentp', 'current password', 'required'),
	array('newp', 'new password', 'required|min_length[6]|max_length[16]'),
	array('confirmp', 'password confirmation', 'required|matches[newp]', array(
		'matches' => 'Password confirmation does not match.'
	)),
);

$config['account_approval'] = array(
	array('id', 'Account ID', 'trim|required|integer'),
	array('AccountTypeID', 'Account type', 'trim|required|integer'),
	array('AccountLevelID', 'Account level', 'trim|required|integer'),
);




$config['save_department'] = array(
	array('Code', 'Department code', 'trim|required'),
	array('Name', 'Department name', 'trim|required'),
);

$config['save_sub_department'] = array(
	array('Type', 'Sub department type', 'required'),
	array('Code', 'Sub department code', 'trim|required'),
	array('Name', 'Sub department name', 'trim|required'),
);

$config['save_department_location'] = array(
	array('Status', 'Department scope location status', 'trim|required'),
	array('DepartmentID', 'Department ID', 'trim|required|integer'),
	array('LocationScope', 'Location Scope', 'trim|required'),
);

$config['save_department_officer'] = array(
	array('DepartmentID', 'Department ID', 'trim|required|integer'),
	array('DepartmentLocationID', 'Department Location ID', 'trim|required|integer'),
	array('SelectedAccountID', 'Officer ID', 'trim|required'),
);




$config['service_base_setup'] = array(
	array('ServiceType', 'Service type', 'required'),
	array('LocationScope', 'Service scope', 'required'),
	array('DepartmentScope', 'Scope department', 'required'),
	array('Code', 'Service code', 'required'),
	array('Name', 'Service name', 'trim|required')
);

$config['service_fields_setup'] = array(
	array('Type', 'type', 'required'),
	array('Label', 'label', 'trim|required')
);

$config['save_service_organization'] = array(
	array('MenuName', 'Menu name', 'trim|required'),
	array('Keyword', 'Keyword', 'trim|required'),
	array('Category', 'Category', 'trim|required'),
);




$config['save_document'] = array(
	array('Type', 'Document type', 'required'),
	array('Name', 'Document name', 'trim|required'),
	array('DepartmentID', 'Department', 'required'),
	array('Validity', 'Validity', 'required'),
);

$config['save_document_extra_field'] = array(
	array('FieldType', 'Type', 'required'),
	array('FieldKey', 'Template keyword', 'trim|strtoupper|alpha|required'),
	array('FieldLabel', 'label', 'trim|required'),
);




$config['save_zone'] = array(
	array('Name', 'Location name', 'trim|required')
);

// zone public office setup
$config['save_office_setup'] = array(
	array('Name', 'Office name', 'trim|required'),
	array('Domain', 'Domain name', 'trim|alpha_numeric'),
	array('Email', 'Email', 'trim|valid_email')
);

$config['save_project'] = array(
	array('LocationScopeID', 'Location Scope', 'trim|required'),
	array('Name', 'Project name', 'trim|required'),
	array('Description', 'Project description', 'trim')
);

$config['save_project_category_item'] = array(
	array('Name', 'Project name', 'trim|required'),
	array('Description', 'Project description', 'trim'),
	array('Quantity', 'Quantity', 'trim|required|numeric'),
	array('Allocation', 'Allocation', 'trim|required|numeric'),
);