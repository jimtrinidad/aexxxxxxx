<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
* User details
*/
$config['gender'] = array(
    1 => 'Male',
    2 => 'Female',
);

$config['marital_status'] = array(
    1 => 'Single',
    2 => 'Married',
    3 => 'Widowed',
    4 => 'Divorced',
);

$config['livelihood'] = array(
	1 => 'Regular Employee',
	2 => 'Employee with Part Time Job',
	3 => 'Employee with Part Time Business',
	4 => 'Business Owner',
	5 => 'Unemployed with Livelihood Income',
	6 => 'Unemployed without Livelihood Income'
);

$config['education'] = array(
	1 => 'Not Applicable',
	2 => 'Vocational',
	3 => 'Bachelor\'s Degree',
	4 => 'Master\'s Degree',
	5 => 'Doctorate Degree'
);


$config['govt_ids'] = array(
	1 => 'Voters ID',
	2 => 'BIR',
	3 => 'SSS',
	4 => 'DRIVERS'
);


$config['cycle_interval'] = array(
	1 	=> '1 day',
	7 	=> '1 week',
	14 	=> '2 weeks',
	21 	=> '3 weeks',
	30 	=> '1 month',
	91 	=> '3 months',
	182	=> '6 months',
	274	=> '9 months',
	365 	=> '1 year',
	730 	=> '2 years',
	1095 	=> '3 years',
);


/**
* document builder
*/
$config['field_class'] = array(
	1 => 'Citizen Information',
	// 2 => 'Document Requirement'
);

$config['field_type'] = array(
	1 => 'Textbox',
	2 => 'Textarea',
	3 => 'Image file'
);

$config['document_type'] = array(
	1 => 'Accreditation',
	2 => 'Acknowledgement',
	3 => 'Appointment',
	4 => 'Assessment',
	5 => 'Certificate',
	6 => 'Clearance',
	7 => 'Eligibility',
	8 => 'Employment',
	9 => 'Endorsement',
	10 => 'Grant',
	11 => 'Grant Financial',
	12 => 'ID',
	13 => 'License',
	14 => 'Permit',
	15 => 'Receipt',
	16 => 'Registration',
	17 => 'Report',
	18 => 'Request',
	19 => 'Statement',
	20 => 'Verification',
	21 => 'Inquiry Response'
);


$config['document_validity'] = array(
	1 => '1 month upon generation',
	2 => '6 months upon generation',
	3 => '1 year upon generation',
	4 => 'lifetime',
	5 => 'end of the current month',
	6 => 'end of the current year',
);


$config['location_scope'] = array(
	1 => 'National',
	2 => 'Regional',
	3 => 'Provincial',
	4 => 'City',
	5 => 'Municipality',
	6 => 'Barangay'
);



$config['service_type'] = array(
	1 => 'Appointment',
	2 => 'Clearance',
	3 => 'Counselling',
	4 => 'Donation',
	5 => 'Employment',
	6 => 'Certificate',
	7 => 'Grant',
	8 => 'Payment Process',
	9 => 'Public Service',
	10 => 'Registration',
	11 => 'Report',
	12 => 'Request',
	13 => 'Traffic Violations',
	14 => 'Seminar / Training',
	15 => 'Service Implementation',
	16 => 'Endorsement Financial Assistant',
	17 => 'Security Support',
);

//array of service type id
$config['no_waiting_service_type'] = array(11, 13);

$config['service_tags'] = array(
	1 => 'frequently',
	2 => 'featured',
	3 => 'new',
	4 => 'barangay',
	5 => 'municipal',
	6 => 'city',
	7 => 'province',
	8 => 'region',
	9 => 'national'
);

$config['service_status'] = array(
	0 => 'Pending',
	1 => 'Active',
	2 => 'Inactive',
);

$config['service_application_status'] = array(
	0 => 'New',
	1 => 'Processing',
	2 => 'Approved',
	3 => 'Denied',
	4 => 'Canceled',
);

$config['service_organization_category'] = array(
	1 => 'Traffic Violation',
	2 => 'Licensing Services',
	3 => 'Emergency Assistance',
	4 => 'Driving Violation',
	5 => 'Establishment Traffic Violation',
	6 => 'Pedestrian Traffic Violation'
);


$config['account_status'] = array(
	// 0 => 'Pending', //hidden option, default upon registration (waiting for approval status)
	1 => 'Active',
	2 => 'Inactive',
	3 => 'Blocked'
);

$config['account_type'] = array(
	1 => 'Citizen',
	2 => 'Public Servant',
	3 => 'mGov SysIntegrator'
	// 4 => 'ADMIN', //hidden type
);


$config['child_department_types'] = array(
	1 => 'Sub Department/Agency',
	2 => 'Office',
	3 => 'Organization'
);


// $config['document_list'] = array(
// 	1 => 'Cedula',
// 	2 => 'Mayor\'s Endorsement',
// 	3 => 'Barangay Clearance',
// 	4 => 'Birth certificate',
// 	5 => 'NBI Clearance',
// 	6 => 'Police Clearance'
// );


$config['document_template_keywords'] = array(
	array(
		'name'	=> 'Citizen Data',
		'keys'	=> array(
			'MABUHAYID'			=> 'Mabuhay ID',
			'FIRSTNAME' 		=> 'Firstname',
			'MIDDLENAME'		=> 'Middlename',
			'LASTNAME'			=> 'Lastname',
			'EMAIL'				=> 'Email address',
			'CONTACTNO'			=> 'Contact number',
			'BIRTHDAY'			=> 'Birthday',
			'GENDER'			=> 'Gender',
			'MARITALSTATUS'		=> 'Marital status',
			'EDUCATION'			=> 'Education',
			'LIVELIHOOD'		=> 'Livelihood',
			'CREGION'			=> 'Region name',
			'CPROVINCE'			=> 'Province name',
			'CMUNICITY'			=> 'City or Municipality',
			'CBRGY'				=> 'Barangay',
			'CSTREET'			=> 'Street address',
		),
		'images' => array(
			array(
				'name'	=> 'Profile photo',
				'url'	=> 'i/profile/dummy'
			)
		)
	),
	array(
		'name'	=> 'Department Data',
		'keys'	=> array(
			'DCODE'		=> 'Code',
			'DNAME'		=> 'Name',
			'DADDRESS'	=> 'Address'
		),
		'images' => array(
			array(
				'name'	=> 'Department logo',
				'url'	=> 'i/dept/logo'
			)
		)
	),
	array(
		'name'	=> 'Sub Department / Agency',
		'keys'	=> array(
			'SDCODE'	=> 'Code',
			'SDNAME'	=> 'Name',
			'SDADDRESS'	=> 'Address'
		),
		'images' => array(
			array(
				'name'	=> 'Sub Department logo',
				'url'	=> 'i/subdept/logo'
			)
		)
	),
	array(
		'name'	=> 'Issuer Location Data',
		'keys'	=> array(
			'LREGION'		=> 'Region name',
			'LPROVINCE'		=> 'Province name',
			'LMUNICITY'		=> 'City or Municipality',
			'LBRGY'			=> 'Barangay'
		),
		'images' => array(
			array(
				'name'	=> 'Regional logo',
				'url'	=> 'i/region/logo'
			),
			array(
				'name'	=> 'Provincial logo',
				'url'	=> 'i/prov/logo'
			),
			array(
				'name'	=> 'City or Municipal logo',
				'url'	=> 'i/city/logo'
			),
			array(
				'name'	=> 'Barangay logo',
				'url'	=> 'i/brgy/logo'
			),
		)
	),
	array(
		'name'	=> 'Other Data',
		'keys'	=> array(
			'CURDAY'	=> 'Current day',
			'CURMONTH'	=> 'Current month',
			'CURYEAR'	=> 'Current year'
		)
	),
);