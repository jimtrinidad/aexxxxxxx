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
	3 => 'Image file',
	4 => 'Selection',
	5 => 'Checkbox'
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


$config['department_function_type'] = array(
	1 => 'Head of Department',
	3 => 'Treasurer',
	4 => 'QuickServe Observer',
	5 => 'mGov System Integrator'
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
	18 => 'Accounting Expenses',
	19 => 'License',
	20 => 'Permit',
	21 => 'Validation',
	22 => 'Business Accreditation'
);

//array of service type id
$config['report_service_type'] = array(11, 13);

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

$config['service_categories'] = array(
	1 => 'Peace & Order',
	2 => 'Environment',
	3 => 'Health',
	4 => 'Education',
	5 => 'Social Services',
	6 => 'Shelter',
	7 => 'Livelihood & Employment',
	8 => 'Infrastructure & Utilities',
	9 => 'Agriculture & Fishery',
	10 => 'Investment & Tourism',
	11 => 'Ease of Doing Business',
	12 => 'Emergency Response'
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


$config['document_template_keywords'] = array(
	array(
		'name'	=> 'Citizen Data',
		'keys'	=> array(
			'MABUHAYID'			=> 'Mabuhay ID',
			'FIRSTNAME' 		=> 'Firstname',
			'MIDDLENAME'		=> 'Middlename',
			'MI'				=> 'Middle initial',
			'LASTNAME'			=> 'Lastname',
			'EMAIL'				=> 'Email address',
			'CONTACTNO'			=> 'Contact number',
			'BIRTHDAY'			=> 'Birthday',
			'AGE'				=> 'Age',
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
			),
			array(
				'name'	=> 'QR',
				'url'	=> 'i/profile/qr'
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
		'name'	=> 'Dates',
		'keys'	=> array(
			'CURDATE'	=> 'Current date (YYYY-MM-DD)',
			'CURDAY'	=> 'Current day (DD)',
			'CURMONTH'	=> 'Current month (January - December)',
			'CURNMONTH'	=> 'Current month (MM)',
			'CURYEAR'	=> 'Current year (YYYY)',
			'DOCXDATE' 	=> 'Doc expiration date (YYYY-MM-DD)',
			'DOCXDAY' 	=> 'Doc expiration day (DD)',
			'DOCXMONTH'	=> 'Doc expiration month (MM)',
			'DOCXYEAR'	=> 'Doc expiration year (YYYY)'
		)
	),
);



$config['countries'] = json_decode('{"AF":"Afghanistan","AX":"Aland Islands","AL":"Albania","DZ":"Algeria","AS":"American Samoa","AD":"Andorra","AO":"Angola","AI":"Anguilla","AQ":"Antarctica","AG":"Antigua and Barbuda","AR":"Argentina","AM":"Armenia","AW":"Aruba","AU":"Australia","AT":"Austria","AZ":"Azerbaijan","BS":"Bahamas","BH":"Bahrain","BD":"Bangladesh","BB":"Barbados","BY":"Belarus","BE":"Belgium","BZ":"Belize","BJ":"Benin","BM":"Bermuda","BT":"Bhutan","BO":"Bolivia","BQ":"Bonaire, Saint Eustatius and Saba ","BA":"Bosnia and Herzegovina","BW":"Botswana","BV":"Bouvet Island","BR":"Brazil","IO":"British Indian Ocean Territory","VG":"British Virgin Islands","BN":"Brunei","BG":"Bulgaria","BF":"Burkina Faso","BI":"Burundi","KH":"Cambodia","CM":"Cameroon","CA":"Canada","CV":"Cape Verde","KY":"Cayman Islands","CF":"Central African Republic","TD":"Chad","CL":"Chile","CN":"China","CX":"Christmas Island","CC":"Cocos Islands","CO":"Colombia","KM":"Comoros","CK":"Cook Islands","CR":"Costa Rica","HR":"Croatia","CU":"Cuba","CW":"Curacao","CY":"Cyprus","CZ":"Czech Republic","CD":"Democratic Republic of the Congo","DK":"Denmark","DJ":"Djibouti","DM":"Dominica","DO":"Dominican Republic","TL":"East Timor","EC":"Ecuador","EG":"Egypt","SV":"El Salvador","GQ":"Equatorial Guinea","ER":"Eritrea","EE":"Estonia","ET":"Ethiopia","FK":"Falkland Islands","FO":"Faroe Islands","FJ":"Fiji","FI":"Finland","FR":"France","GF":"French Guiana","PF":"French Polynesia","TF":"French Southern Territories","GA":"Gabon","GM":"Gambia","GE":"Georgia","DE":"Germany","GH":"Ghana","GI":"Gibraltar","GR":"Greece","GL":"Greenland","GD":"Grenada","GP":"Guadeloupe","GU":"Guam","GT":"Guatemala","GG":"Guernsey","GN":"Guinea","GW":"Guinea-Bissau","GY":"Guyana","HT":"Haiti","HM":"Heard Island and McDonald Islands","HN":"Honduras","HK":"Hong Kong","HU":"Hungary","IS":"Iceland","IN":"India","ID":"Indonesia","IR":"Iran","IQ":"Iraq","IE":"Ireland","IM":"Isle of Man","IL":"Israel","IT":"Italy","CI":"Ivory Coast","JM":"Jamaica","JP":"Japan","JE":"Jersey","JO":"Jordan","KZ":"Kazakhstan","KE":"Kenya","KI":"Kiribati","XK":"Kosovo","KW":"Kuwait","KG":"Kyrgyzstan","LA":"Laos","LV":"Latvia","LB":"Lebanon","LS":"Lesotho","LR":"Liberia","LY":"Libya","LI":"Liechtenstein","LT":"Lithuania","LU":"Luxembourg","MO":"Macao","MK":"Macedonia","MG":"Madagascar","MW":"Malawi","MY":"Malaysia","MV":"Maldives","ML":"Mali","MT":"Malta","MH":"Marshall Islands","MQ":"Martinique","MR":"Mauritania","MU":"Mauritius","YT":"Mayotte","MX":"Mexico","FM":"Micronesia","MD":"Moldova","MC":"Monaco","MN":"Mongolia","ME":"Montenegro","MS":"Montserrat","MA":"Morocco","MZ":"Mozambique","MM":"Myanmar","NA":"Namibia","NR":"Nauru","NP":"Nepal","NL":"Netherlands","NC":"New Caledonia","NZ":"New Zealand","NI":"Nicaragua","NE":"Niger","NG":"Nigeria","NU":"Niue","NF":"Norfolk Island","KP":"North Korea","MP":"Northern Mariana Islands","NO":"Norway","OM":"Oman","PK":"Pakistan","PW":"Palau","PS":"Palestinian Territory","PA":"Panama","PG":"Papua New Guinea","PY":"Paraguay","PE":"Peru","PH":"Philippines","PN":"Pitcairn","PL":"Poland","PT":"Portugal","PR":"Puerto Rico","QA":"Qatar","CG":"Republic of the Congo","RE":"Reunion","RO":"Romania","RU":"Russia","RW":"Rwanda","BL":"Saint Barthelemy","SH":"Saint Helena","KN":"Saint Kitts and Nevis","LC":"Saint Lucia","MF":"Saint Martin","PM":"Saint Pierre and Miquelon","VC":"Saint Vincent and the Grenadines","WS":"Samoa","SM":"San Marino","ST":"Sao Tome and Principe","SA":"Saudi Arabia","SN":"Senegal","RS":"Serbia","SC":"Seychelles","SL":"Sierra Leone","SG":"Singapore","SX":"Sint Maarten","SK":"Slovakia","SI":"Slovenia","SB":"Solomon Islands","SO":"Somalia","ZA":"South Africa","GS":"South Georgia and the South Sandwich Islands","KR":"South Korea","SS":"South Sudan","ES":"Spain","LK":"Sri Lanka","SD":"Sudan","SR":"Suriname","SJ":"Svalbard and Jan Mayen","SZ":"Swaziland","SE":"Sweden","CH":"Switzerland","SY":"Syria","TW":"Taiwan","TJ":"Tajikistan","TZ":"Tanzania","TH":"Thailand","TG":"Togo","TK":"Tokelau","TO":"Tonga","TT":"Trinidad and Tobago","TN":"Tunisia","TR":"Turkey","TM":"Turkmenistan","TC":"Turks and Caicos Islands","TV":"Tuvalu","VI":"U.S. Virgin Islands","UG":"Uganda","UA":"Ukraine","AE":"United Arab Emirates","GB":"United Kingdom","US":"United States","UM":"United States Minor Outlying Islands","UY":"Uruguay","UZ":"Uzbekistan","VU":"Vanuatu","VA":"Vatican","VE":"Venezuela","VN":"Vietnam","WF":"Wallis and Futuna","EH":"Western Sahara","YE":"Yemen","ZM":"Zambia","ZW":"Zimbabwe"}', true);

$config['currencies'] = array(
	'MYR'	=> 'Malaysian Ringgit',
	'SGD'	=> 'Singapore Dollar',
	'PHP'	=> 'Philippine Peso',
	'USD'	=> 'US Dollar',
	'INR'	=> 'Indian Rupee',
	'AUD'	=> 'Australian Dollar',
	'IDR'	=> 'Indonesian Rupiah',
	'THB'	=> 'Thai Baht',
	'HKD'	=> 'Hong Kong Dollar',
	'EUR'	=> 'Euro',
	'CNY'	=> 'Chinese Yuan',
	'JPY'	=> 'Japanese Yen',
	'GBP'	=> 'British Pound',
	'VND'	=> 'Vietnamese đồng',
	'BDT'	=> 'Bangladeshi Taka',
	'NZD'	=> 'New Zealand Dollar'
);


$config['job_specializations'] = array(
	1 => 'Actuarial Science/Statistics',
	2 => 'Advertising/Media Planning',
	3 => 'Agriculture/Forestry/Fisheries',
	4 => 'Architecture/Interior Design',
	5 => 'Arts/Creative/Graphics Design',
	6 => 'Aviation/Aircraft Maintenance',
	7 => 'Banking/Financial Services',
	8 => 'Biomedical',
	9 => 'Biotechnology',
	10 => 'Chemistry',
	11 => 'Clerical/Administrative Support',
	12 => 'Corporate Strategy/Top Management',
	13 => 'Customer Service',
	14 => 'Digital Marketing',
	15 => 'E-commerce',
	16 => 'Education',
	17 => 'Engineering - Chemical',
	18 => 'Engineering - Civil/Construction/Structural',
	19 => 'Engineering - Electrical',
	20 => 'Engineering - Electronics/Communication',
	21 => 'Engineering - Environmental/Health/Safety',
	22 => 'Engineering - Industrial',
	23 => 'Engineering - Mechanical/Automotive',
	24 => 'Engineering - Oil/Gas',
	25 => 'Engineering - Others',
	26 => 'Entertainment/Performing Arts',
	27 => 'Finance - Audit/Taxation',
	28 => 'Finance - Corporate Finance/Investment/Merchant Banking',
	29 => 'Finance - General/Cost Accounting',
	30 => 'Food Technology/Nutritionist',
	31 => 'Food/Beverage/Restaurant Service',
	32 => 'General Work (Housekeeper, Driver, Dispatch, Messenger, etc)',
	33 => 'Geology/Geophysics',
	34 => 'Healthcare - Doctor/Diagnosis',
	35 => 'Healthcare - Nurse/Medical Support &amp; Assistant',
	36 => 'Healthcare - Pharmacy',
	37 => 'Hotel Management/Tourism Services',
	38 => 'Human Resources',
	39 => 'IT/Computer - Hardware',
	40 => 'IT/Computer - Network/System/Database Admin',
	41 => 'IT/Computer - Software',
	42 => 'Journalist/Editor',
	43 => 'Law/Legal Services',
	44 => 'Logistics/Supply Chain',
	45 => 'Maintenance/Repair (Facilities &amp; Machinery)',
	46 => 'Manufacturing/Production Operations',
	47 => 'Marketing/Business Development',
	48 => 'Merchandising',
	49 => 'Personal Care/Beauty/Fitness Service',
	50 => 'Process Design &amp; Control/Instrumentation',
	51 => 'Property/Real Estate',
	52 => 'Public Relations/Communications',
	53 => 'Publishing/Printing',
	54 => 'Purchasing/Inventory/Material &amp; Warehouse Management',
	55 => 'Quality Control/Assurance',
	56 => 'Quantity Surveying',
	57 => 'Sales - Corporate',
	58 => 'Sales - Engineering/Technical/IT',
	59 => 'Sales - Financial Services (Insurance, Unit Trust, etc)',
	60 => 'Sales - Retail/General',
	61 => 'Sales - Telesales/Telemarketing',
	62 => 'Science &amp; Technology/Laboratory',
	63 => 'Secretarial/Executive &amp; Personal Assistant',
	64 => 'Security/Armed Forces/Protective Services',
	65 => 'Social &amp; Counselling Service',
	66 => 'Technical &amp; Helpdesk Support',
	67 => 'Training &amp; Development'
);


$config['job_industries'] = array(
	1 => 'Accounting / Audit / Tax Services',
	2 => 'Advertising / Marketing / Promotion / PR',
	3 => 'Aerospace / Aviation / Airline',
	4 => 'Agricultural / Plantation / Poultry / Fisheries',
	5 => 'Apparel',
	6 => 'Architectural Services / Interior Designing',
	7 => 'Arts / Design / Fashion',
	8 => 'Automobile / Automotive Ancillary / Vehicle',
	9 => 'Banking / Financial Services',
	10 => 'BioTechnology / Pharmaceutical / Clinical research',
	11 => 'Call Center / IT-Enabled Services / BPO',
	12 => 'Chemical / Fertilizers / Pesticides',
	13 => 'Computer / Information Technology (Hardware)',
	14 => 'Computer / Information Technology (Software)',
	15 => 'Construction / Building / Engineering',
	16 => 'Consulting (Business &amp; Management)',
	17 => 'Consulting (IT, Science, Engineering &amp; Technical)',
	18 => 'Consumer Products / FMCG',
	19 => 'Education',
	20 => 'Electrical &amp; Electronics',
	21 => 'Entertainment / Media',
	22 => 'Environment / Health / Safety',
	23 => 'Exhibitions / Event management / MICE',
	24 => 'Food &amp; Beverage / Catering / Restaurant',
	25 => 'Gems / Jewellery',
	26 => 'General &amp; Wholesale Trading',
	27 => 'Government / Defence',
	28 => 'Grooming / Beauty / Fitness',
	29 => 'Healthcare / Medical',
	30 => 'Heavy Industrial / Machinery / Equipment',
	31 => 'Hotel / Hospitality',
	32 => 'Human Resources Management / Consulting',
	33 => 'Insurance',
	34 => 'Journalism',
	35 => 'Law / Legal',
	36 => 'Library / Museum',
	37 => 'Manufacturing / Production',
	38 => 'Marine / Aquaculture',
	39 => 'Mining',
	40 => 'Non-Profit Organisation / Social Services / NGO',
	41 => 'Oil / Gas / Petroleum',
	42 => 'Polymer / Plastic / Rubber / Tyres',
	43 => 'Printing / Publishing',
	44 => 'Property / Real Estate',
	45 => 'R&amp;D',
	46 => 'Repair &amp; Maintenance Services',
	47 => 'Retail / Merchandise',
	48 => 'Science &amp; Technology',
	49 => 'Security / Law Enforcement',
	50 => 'Semiconductor/Wafer Fabrication',
	51 => 'Sports',
	52 => 'Stockbroking / Securities',
	53 => 'Telecommunication',
	54 => 'Textiles / Garment',
	55 => 'Tobacco',
	56 => 'Transportation / Logistics',
	57 => 'Travel / Tourism',
	58 => 'Utilities / Power',
	59 => 'Wood / Fibre / Paper'
);

$config['job_levels'] = array(
	1 => 'CEO / SVP / AVP / VP / Director',
	2 => 'Assistant Manager / Manager',
	3 => 'Supervisor / 5 Years &amp; Up Experienced Employee',
	4 => '1-4 Years Experienced Employee',
	5 => 'Fresh Grad / &lt; 1 Year Experienced Employee',
	6 => 'Non-Executive'
);


$config['education_fields'] = array(
	1 => 'Advertising/Media',
	2 => 'Agriculture/Aquaculture/Forestry',
	3 => 'Airline Operation/Airport Management',
	4 => 'Architecture',
	5 => 'Art/Design/Creative Multimedia',
	6 => 'Biology',
	7 => 'BioTechnology',
	8 => 'Business Studies/Administration/Management',
	9 => 'Chemistry',
	10 => 'Commerce',
	11 => 'Computer Science/Information Technology',
	12 => 'Dentistry',
	13 => 'Economics',
	14 => 'Journalism',
	15 => 'Education/Teaching/Training',
	16 => 'Engineering (Aviation/Aeronautics/Astronautics)',
	17 => 'Engineering (Bioengineering/Biomedical)',
	18 => 'Engineering (Chemical)',
	19 => 'Engineering (Civil)',
	20 => 'Engineering (Computer/Telecommunication)',
	21 => 'Engineering (Electrical/Electronic)',
	22 => 'Engineering (Environmental/Health/Safety)',
	23 => 'Engineering (Industrial)',
	24 => 'Engineering (Marine)',
	25 => 'Engineering (Material Science)',
	26 => 'Engineering (Mechanical)',
	27 => 'Engineering (Mechatronic/Electromechanical)',
	28 => 'Engineering (Metal Fabrication/Tool &amp; Die/Welding)',
	29 => 'Engineering (Mining/Mineral)',
	30 => 'Engineering (Others)',
	31 => 'Engineering (Petroleum/Oil/Gas)',
	32 => 'Finance/Accountancy/Banking',
	33 => 'Food &amp; Beverage Services Management',
	34 => 'Food Technology/Nutrition/Dietetics',
	35 => 'Geographical Science',
	36 => 'Geology/Geophysics',
	37 => 'History',
	38 => 'Hospitality/Tourism/Hotel Management',
	39 => 'Human Resource Management',
	40 => 'Humanities/Liberal Arts',
	41 => 'Logistic/Transportation',
	42 => 'Law',
	43 => 'Library Management',
	44 => 'Linguistics/Languages',
	45 => 'Mass Communications',
	46 => 'Mathematics',
	47 => 'Medical Science',
	48 => 'Medicine',
	49 => 'Maritime Studies',
	50 => 'Marketing',
	51 => 'Music/Performing Arts Studies',
	52 => 'Nursing',
	53 => 'Optometry',
	54 => 'Personal Services',
	55 => 'Pharmacy/Pharmacology',
	56 => 'Philosophy',
	57 => 'Physical Therapy/Physiotherapy',
	58 => 'Physics',
	59 => 'Political Science',
	60 => 'Property Development/Real Estate Management',
	61 => 'Protective Services &amp; Management',
	62 => 'Psychology',
	63 => 'Quantity Survey',
	64 => 'Science &amp; Technology',
	65 => 'Secretarial',
	66 => 'Social Science/Sociology',
	67 => 'Sports Science &amp; Management',
	68 => 'Textile/Fashion Design',
	69 => 'Urban Studies/Town Planning',
	70 => 'Veterinary'
);

$config['education_levels'] = array(
	1 => 'High School Diploma',
	2 => 'Vocational Diploma / Short Course Certificate',
	3 => 'Bachelor\'s/College Degree',
	4 => 'Post Graduate Diploma / Master\'s Degree',
	5 => 'Professional License (Passed Board/Bar/Professional License Exam)',
	6 => 'Doctorate Degree'
);

$config['skill_levels'] = array(
	1 => 'Advanced',
	2 => 'Intermediate',
	3 => 'Beginner'
);


$config['months'] = array(
	1 => 'January', 
	2 => 'February', 
	3 => 'March', 
	4 => 'April', 
	5 => 'May', 
	6 => 'June.', 
	7 => 'July.', 
	8 => 'August.', 
	9 => 'September.', 
	10 => 'October', 
	11 => 'November', 
	12 => 'December');



$config['mobile_service_provider'] = array(
	1 => 'Globe',
	2 => 'Smart',
	3 => 'Touch Mobile',
	4 => 'Sun Cellular',
	5 => 'ABS-CBN Mobile'
);



/* DEFAULTS */

/**
* organization id the will use the ctto report
*/
$config['cttmo_organizations'] = array(
	246
);

/**
* organization id the will use coa format
*/
$config['coa_organizations'] = array(
	// 234, // bantay korapsyon
	297, // Barangay League of Accountants
	300 // Barangay Procurement League
);


// BUSINESS APPLICATION REQUEST SERVICE TYPE
define('BUSINESS_REQUEST_TYPE', 22);