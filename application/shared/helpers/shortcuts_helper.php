<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* render view
*/
function view($view, $data = array(), $template = null, $return = false)
{
	$ci =& get_instance();
	if ($template !== null) {
		$content = $ci->load->view($view, $data, true);
		$data['templateContent'] = $content;
		$rendered = $ci->load->view($template, $data, $return);
	} else {
		$rendered = $ci->load->view($view, $data, $return);
	}

	if ($return) {
		return $rendered;
	}
}

/**
* return request input
*/
function get_post($key)
{	
	$ci =& get_instance();
	return $ci->input->get_post($key);
}

/**
* is ajax request
*/
function is_ajax()
{
	$ci =& get_instance();
    return $ci->input->is_ajax_request();
}

/**
* public url for both front and admin
*/
function public_url($segment = '')
{
	$base_url = base_url();
	$base_url = rtrim(str_replace('/admin', '', $base_url), '/') . '/';
	if ($segment != '') {
		$base_url = $base_url . trim($segment, '/') . '/';
	}
	return $base_url;
}


/**
* run validation
* get rules from config
*/
function validate($rules, $data = null)
{	
	$ci =& get_instance();
		
	// if validation rules key is provided, get from validation config
	if (is_string($rules)) {
    	$rules = $ci->config->item($rules);
    }

    if ($data !== null) {
		// reset validation and set new data to validate
		$ci->form_validation->reset_validation();
		$ci->form_validation->set_data($data);
	}

    if (is_array($rules)) {
		foreach ($rules as $rule) {
			call_user_func_array(array($ci->form_validation, 'set_rules'), $rule);
		}
		return $ci->form_validation->run();
	}

	// invalid rules
	return false;
}

/**
* return validation error in array
*/
function validation_error_array()
{
	$ci =& get_instance();
	return $ci->form_validation->error_array();
}


/**
* create pagination links
*/
function paginate($config)
{	
	$ci =& get_instance();
	$ci->load->library('pagination');

	// required config input
	// base_url
	// total_rows
	// per_page

	$default = array(
		'num_links'		=> 3,
		'uri_segment' 	=> 3
	);

	$default['full_tag_open']   = '<ul class="pagination">';
	$default['full_tag_close']  = '</ul>';
	$default['first_link']      = '<< First';
	$default['last_link']       = 'Last >>';
	$default['first_tag_open']  = '<li>';
	$default['first_tag_close'] = '</li>';
	$default['prev_link']       = '< Previous';
	$default['prev_tag_open']   = '<li class="prev">';
	$default['prev_tag_close']  = '</li>';
	$default['next_link']       = 'Next >';
	$default['next_tag_open']   = '<li>';
	$default['next_tag_close']  = '</li>';
	$default['last_tag_open']   = '<li>';
	$default['last_tag_close']  = '</li>';
	$default['cur_tag_open']    = '<li class="active"><a href="#">';
	$default['cur_tag_close']   = '</a></li>';
	$default['num_tag_open']    = '<li>';
	$default['num_tag_close']   = '</li>';

	$config = array_merge($default, $config);

	$ci->pagination->initialize($config);

	return $ci->pagination->create_links();

}



/**
* @param email options
* @param send now or on background
* @param smtp config to use
*
*/
function send_email($emailParams, $background = true, $smtpConfig = 'info')
{
	$ci =& get_instance();

	$ci->load->config('email');
    $config = $ci->config->item('email');

    if (isset($config[$smtpConfig])) {

		$ci->load->library('email', $config[$smtpConfig]);	    
		$ci->email->set_newline("\r\n");

		foreach ($emailParams as $method => $params)
		{
			if (!is_array($params)) {
				$params = (array) $params;
			}
			call_user_func_array(array($ci->email, $method), $params);
		}

		// set config to use on background sending
		$ci->email->configKey = $smtpConfig;

		$ci->email->send(!$background);

		// var_dump($ci->email->print_debugger());
	}
}




/**
* is authenticated?
* if not
* redirect to loggin page
* if ajax return 
*/
function check_authentication()
{
	$ci =& get_instance();
	if (!$ci->authentication->is_loggedin()) {
        if (!$ci->input->is_ajax_request()) {
		   	redirect('account/signin');
		} else {
			header("HTTP/1.1 401 Unauthorized");
			header('Content-Type: application/json');
			echo json_encode(array(
				'status'	=> false,
				'message'	=> 'Unauthorized access.'
			));
			exit;
		}
    }
}

/**
* authtentication is_loggedin shortcut
*/
function isGuest()
{
	$ci =& get_instance();
	if (!$ci->authentication->is_loggedin()) {
		return true;
	} else {
		return false;
	}
}


/**
* prepare account data
* convert id to actual value
*/
function prepare_account_data($raw)
{
	$data = array(
		'id'			=> $raw->id,
		'reg_id'		=> $raw->RegistrationID,
		'mabuhay_id'	=> $raw->MabuhayID,
		'fullname'		=> user_full_name($raw, 3),
		'firstname'		=> $raw->FirstName,
		'middlename'	=> $raw->MiddleName,
		'lastname'		=> $raw->LastName,
		'email'			=> $raw->EmailAddress,
		'contact'		=> $raw->ContactNumber,
		'username'		=> $raw->Username,
		'birthdate'		=> ($raw->BirthDate != '0000-00-00' && $raw->BirthDate != '1970-01-01' && $raw->BirthDate != '1969-12-31' ? $raw->BirthDate : ''),
		'gender'		=> lookup('gender', $raw->GenderID),
		'marital'		=> lookup('marital_status', $raw->MaritalStatusID),
		'education'		=> lookup('education', $raw->EducationalAttainmentID),
		'livelihood'	=> lookup('livelihood', $raw->LivelihoodStatusID),
		'address'		=> lookup_address(((array)$raw)),
		'street'		=> $raw->StreetPhase,
		'photo'			=> photo_filename($raw->Photo),
		'gov_ids'		=> lookup('govt_ids', json_decode($raw->GovernmentID, true)),
		'account_type'	=> lookup('account_type', $raw->AccountTypeID),
		'account_level'	=> lookup_db('UserAccountLevel', 'LevelName', $raw->AccountLevelID),
		'account_status'=> $raw->StatusID ? lookup('account_status', $raw->StatusID) : 'Pending',
		'a_type_id'		=> $raw->AccountTypeID,
		'a_level_id'	=> $raw->AccountLevelID,
		'a_status_id'	=> $raw->StatusID,
		'reg_date'		=> $raw->RegistrationDate,
		'approver_id'	=> $raw->ApprovedBy,
		'approved_date'	=> $raw->ApprovedDate,
		'gender_id'		=> $raw->GenderID,
		'marital_id'	=> $raw->MaritalStatusID,
		'education_id'	=> $raw->EducationalAttainmentID,
		'livelihood_id'	=> $raw->LivelihoodStatusID,
		'city_id'		=> $raw->MunicipalityCityID,
		'barangay_id'	=> $raw->BarangayID,
		'organization'	=> $raw->OrganizationID

	);

	return $data;
}


/**
* prepare service data
* optional
* include requirements, functions and officers
*/
function prepare_service_data($raw, $getcomponents = false)
{

	$ci =& get_instance();

	$deparment  = lookup_row('Dept_Departments', $raw->DepartmentID);
	$subDept 	= lookup_row('Dept_ChildDepartment', $raw->SubDepartmentID);

	foreach (array('LastUpdate','deletedBy','deletedAt') as $field) {
		unset($raw->$field);
		unset($deparment->$field);
		unset($subDept->$field);
	}

	if (isset($deparment->Logo)) {
		$deparment->Logo = logo_filename($deparment->Logo);
	}

	if (isset($subDept->Logo)) {
		$subDept->Logo = logo_filename($subDept->Logo);
	}

	$data = (array) $raw;
	$data['Logo'] 	= logo_filename($raw->Logo);
	$data['Type']	= lookup('service_type', $raw->ServiceType);
	$data['Scope']	= lookup('location_scope', $raw->LocationScopeID);
	$data['Department'] 	= $deparment;
	$data['SubDepartment'] 	= $subDept;
	$data['Location'] 		= lookup_address($data);
	$data['Tags'] 			= $raw->Tags ? lookup('service_tags', json_decode($raw->Tags, true)) : array();

	$support_ids = json_decode($data['Supports'], true);
	$supports 	 = array();
	foreach ($support_ids as $support_id) {
		$user = $ci->mgovdb->getRowObject('UserAccountInformation', $support_id, 'id');
		if ($user) {
			$supports[] = array(
				'id'		=> $user->id,
				'MabuhayID'	=> $user->MabuhayID,
				'FirstName'	=> $user->FirstName,
				'LastName'	=> $user->LastName,
				'AccountLevel'	=> lookup_db('UserAccountLevel', 'LevelName', $user->AccountLevelID),
				'Photo'		=> photo_filename($user->Photo)
			);
		}
	}

	$data['Supports'] = $supports;

	if ($getcomponents) {
		// get requirements, extra fields
		
		$ci->db->select('r.id AS id, DocumentID, Name, Logo, Ordering, r.Description')
				->from('Service_Requirements AS r')
				->join('Doc_Templates AS d', 'r.DocumentID = d.id', 'left')
				->where('ServiceID', $raw->id);
		$requirements = $ci->db->get()->result();

		foreach ($requirements as &$requirement) {
			$requirement->Logo = logo_filename($requirement->Logo);

			// get requirement documents extra field
			$documentData = lookup_row('Doc_Templates', $requirement->DocumentID);
			$requirement->extraFields = json_decode($documentData->ExtraFields, true);
		}


		$data['ExtraFields']  = lookup_all('Service_ExtraFormFields', array('ServiceID' => $raw->id), 'Ordering', false);
		$data['Requirements'] = $requirements;
	}

	return $data;
}

function prepare_document_data($raw)
{
	$data = (array) $raw;
	unset($data['deletedAt']);
	unset($data['deletedBy']);


	$data['Logo'] 	= logo_filename($raw->Logo);
	$data['TypeName']	= lookup('document_type', $raw->Type);
	$data['Department'] = lookup_row('Dept_Departments', $raw->DepartmentID, 'id', array('id','Code','Name','Logo'));
	$data['SubDepartment'] = lookup_row('Dept_ChildDepartment', $raw->SubDepartmentID, 'id', array('id','Code','Name','Logo'));

	return $data;
}


/**
* current user
*/
function current_user($view = 'id')
{
	$ci =& get_instance();
	$id = $ci->session->userdata('identifier');

	if ($view == 'full') {
		return user_account_details($id);
	} else if ($view == 'name') {
		$data = user_account_details($id);
		return user_full_name($data, 0);
	}

	return $id;
}

/**
* get user profile pic
*/
function get_user_photo($userID)
{
	$ci =& get_instance();
	$user = $ci->mgovdb->getRowObject('UserAccountInformation', $userID, 'id');
	return photo_filename(($user ? $user->Photo : ''));
}

/**
* get user/account details
*/
function user_account_details($id = false, $field = 'id', $publicData = true)
{
	$ci =& get_instance();

	if ($id === false) {
		$id = $ci->session->userdata('identifier');
	}

	$accountData = $ci->mgovdb->getUserAccount($id, $field);
	$accountData->Photo = photo_filename($accountData->Photo);
	$accountData->QR = get_qr_file($accountData->MabuhayID);

	if ($publicData) {
		// get public office data base on user city
		$accountData->CityData = lookup_row('UtilLocCityMun', $accountData->MunicipalityCityID, 'citymunCode');
		$accountData->PublicOffice = lookup_row('PublicOffices', $accountData->CityData->psgcCode, 'PSGC');
	}

	return $accountData;
}

function photo_filename($filename)
{
	// replace by default avatar if not exists
	return (!empty($filename) && file_exists(PUBLIC_DIRECTORY . 'assets/profile/' . $filename) ? $filename : 'avatar_default.jpg');
}

function logo_filename($filename)
{
	// replace by default logo if not exists
	return (!empty($filename) && file_exists(PUBLIC_DIRECTORY . 'assets/logo/' . $filename) ? $filename : 'default_logo.png');
}

function uploads_filename($filename)
{
	return (!empty($filename) && file_exists(PUBLIC_DIRECTORY . 'assets/uploads/' . $filename) ? $filename : 'default.png');
}

function etc_filename($filename)
{
	return (!empty($filename) && file_exists(PUBLIC_DIRECTORY . 'assets/etc/' . $filename) ? $filename : 'avatar_default.jpg');
}

/**
* generate full name
* return string
*/
function user_full_name($data, $m = 1)
{	
	$data = (array) $data;
	$middle = ' '; // default
	if (!empty($data['MiddleName'])) {
		if ($m == 1) {
			$middle = ' ' . $data['MiddleName'] . ' ';
		} else if ($m == 2) {
			$middle = ' ' . substr($data['MiddleName'], 0, 1) . ' ';
		}
	}
	return ucwords($data['FirstName'] . $middle . $data['LastName']);
}

/**
* generate full address
* return string
*/
function user_full_address($data, $street = false, $region = false)
{
	$data = (array) $data;
	
	$adr = array();

	if ($street && trim($data['StreetPhase']) != '') {
		$adr[] = $data['StreetPhase'];
	}

	if (trim($data['brgyDesc']) != '') {
		$adr[] = $data['brgyDesc'];
	}
	if (trim($data['citymunDesc']) != '') {
		$adr[] = $data['citymunDesc'];
	}
	if (trim($data['provDesc']) != '') {
		$adr[] = $data['provDesc'];
	}

	if ($region && trim($data['regDesc']) != '') {
		$adr[] = $data['regDesc'];
	}

	return implode(', ', $adr);
}


/**
* prepare document extra form fields
* json string to array
* image type url
*/
function prepare_document_extra_fields($extrafieldstring)
{
	$extraFields = (array) @json_decode($extrafieldstring, true);
    foreach ($extraFields as $key => &$extraField) {
        if ($extraField['type'] == 3) {
            $extraField['url'] = public_url('get/i/' . $key) . 'dummy';
        }
    }

    return $extraFields;
}


/**
* get service process and order
* service main function and requirement with their functions
* return array || false if service not found
*/
function get_service_process_order($serviceID)
{	
	$ci =& get_instance();

	$service = $ci->mgovdb->getRowObject('Service_Services', $serviceID, 'id');
	if ($service) {

		$functionTypes 		= lookup("function_type");
		$requirementCount 	= 0;
		$functionCount 		= 0;
		$cleanProcess 		= array();
		$requirementFunctionCount = array();

		// get main service functions
    	$mainServiceFunctions   = (array) lookup_all('Service_Functions', array('ServiceID' => $serviceID, 'FuntionFor' => 'Main'), 'Ordering', false);
    	$serviceRequirements   = (array) lookup_all('Service_Requirements', array('ServiceID' => $serviceID), 'Ordering', false);

		foreach ($mainServiceFunctions as $mainServiceFunction) {
			$cleanProcess[] = array(
				'id'				=> $mainServiceFunction['id'],
				'FunctionTypeID' 	=> $mainServiceFunction['FunctionTypeID'],
				'FunctionName'		=> $functionTypes[$mainServiceFunction['FunctionTypeID']],
				'Description'		=> $mainServiceFunction['Description'],
				'Ordering'			=> $mainServiceFunction['Ordering'],
				'For'				=> 'Main'
			);

			$functionCount++;
		}

		foreach ($serviceRequirements as $serviceRequirement) {

			$requirementFunctions   = (array) lookup_all('Service_Functions', 
															array(
																'ServiceID' => $serviceID, 
																'FuntionFor' => 'Requirement', 
																'RequirementID' => $serviceRequirement['id']
															), 'Ordering', false);

			foreach ($requirementFunctions as $requirementFunction) {
				$cleanProcess[] = array(
					'id'				=> $requirementFunction['id'],
					'FunctionTypeID' 	=> $requirementFunction['FunctionTypeID'],
					'FunctionName'		=> $functionTypes[$requirementFunction['FunctionTypeID']],
					'Description'		=> $requirementFunction['Description'],
					'Ordering'			=> $serviceRequirement['Ordering'] . '.' . $requirementFunction['Ordering'],
					'For'				=> $serviceRequirement['id']
				);

				$functionCount++;
			}

			$requirementFunctionCount[$serviceRequirement['id']] = count($requirementFunctions);
			$requirementCount++;

		}

    	usort($cleanProcess, function($a, $b) {
    		if ($a['Ordering'] == $b['Ordering']) {
		        return 0;
		    }
		    return ($a['Ordering'] < $b['Ordering']) ? -1 : 1;
    	});

    	return array(
    		'orderedProcess' 	=> $cleanProcess,
    		'requirementCount' 	=> $requirementCount,
    		'requirementFunctionCount' => $requirementFunctionCount,
    		'functionCount'		=> $functionCount

    	);
    }

    return false;
}



/**
* generate document from template
* replace keywords with actual data
* return html
*/
function generate_document_from_template($documentID, $data)
{
	$ci =& get_instance();

	$document = $ci->mgovdb->getRowObject('Doc_Templates', $documentID, 'id');
	if ($document) {

		$document_keywords = lookup('document_template_keywords');


		$userData 			= $data['userData'];
		$extraFields 		= $data['extraFields'];
		$uploadedFiles 		= $data['uploadedFiles'];
		$deparmentData  	= lookup_row('Dept_Departments', $document->DepartmentID);
		$subDeptData 		= lookup_row('Dept_ChildDepartment', $document->SubDepartmentID);
		$regionData  		= lookup_row('UtilLocRegion', $userData->RegionalID, 'regCode');
		$provinceData  		= lookup_row('UtilLocProvince', $userData->ProvincialID, 'provCode');
		$municityData  		= lookup_row('UtilLocCityMun', $userData->MunicipalityCityID, 'citymunCode');
		$brgyData  			= lookup_row('UtilLocBrgy', $userData->BarangayID, 'brgyCode');


		// map keyword data
		$default_keyword_data = array(
			'MABUHAYID'			=> $userData->MabuhayID,
			'FIRSTNAME' 		=> $userData->FirstName,
			'MIDDLENAME'		=> $userData->MiddleName,
			'LASTNAME'			=> $userData->LastName,
			'EMAIL'				=> $userData->EmailAddress,
			'CONTACTNO'			=> $userData->ContactNumber,
			'BIRTHDAY'			=> $userData->BirthDate,
			'GENDER'			=> lookup('gender', $userData->GenderID),
			'MARITALSTATUS'		=> lookup('marital_status', $userData->MaritalStatusID),
			'EDUCATION'			=> lookup('education', $userData->EducationalAttainmentID),
			'LIVELIHOOD'		=> lookup('livelihood', $userData->LivelihoodStatusID),
			'CREGION'			=> $userData->regDesc,
			'CPROVINCE'			=> $userData->provDesc,
			'CMUNICITY'			=> $userData->citymunDesc,
			'CBRGY'				=> $userData->brgyDesc,
			'CSTREET'			=> $userData->StreetPhase,
			'DCODE'				=> $deparmentData->Code,
			'DNAME'				=> $deparmentData->Name,
			'DADDRESS'			=> $deparmentData->Address,
			'SDCODE'			=> ($subDeptData ? $subDeptData->Code: 'N/A'),
			'SDNAME'			=> ($subDeptData ? $subDeptData->Code : 'N/A'),
			'SDADDRESS'			=> ($subDeptData ? $subDeptData->Address : 'N/A'),
			'LREGION'			=> $userData->regDesc,
			'LPROVINCE'			=> $userData->provDesc,
			'LMUNICITY'			=> $userData->citymunDesc,
			'LBRGY'				=> $userData->brgyDesc,
			'CURDAY'			=> date('j'),
			'CURMONTH'			=> date('F'),
			'CURYEAR'			=> date('Y')
		);

		$default_image_data 	= array(
			'i/profile/dummy'	=> base_url() . 'assets/profile/' . $userData->Photo,
			'i/dept/logo'		=> base_url() . 'assets/logo/' . logo_filename($deparmentData->Logo),
			'i/subdept/logo'	=> base_url() . 'assets/logo/' . logo_filename(($subDeptData ? $subDeptData->Logo : 'na')),
			'i/region/logo'		=> base_url() . 'assets/logo/' . logo_filename(($regionData ? $regionData->logo : 'na')),
			'i/prov/logo'		=> base_url() . 'assets/logo/' . logo_filename(($provinceData ? $provinceData->logo : 'na')),
			'i/city/logo'		=> base_url() . 'assets/logo/' . logo_filename(($municityData ? $municityData->logo : 'na')),
			'i/brgy/logo'		=> base_url() . 'assets/logo/' . logo_filename(($brgyData ? $brgyData->logo : 'na')),
		);

		$document_extra_fields = json_decode($document->ExtraFields, true);

		if (is_array($document_extra_fields)) {
			foreach ($document_extra_fields as $id => $field) {
				if ($field['type'] == 3) {
					$default_image_data["i/{$id}/dummy"] = base_url() . 'assets/uploads/' . uploads_filename((isset($uploadedFiles[$id]) ? $uploadedFiles[$id] : 'na'));
				} else {
					$default_keyword_data[$field['keyword']] = (isset($extraFields[$id]) ? $extraFields[$id] : 'N/A');
				}
			}
		}

		$html = $document->Content;

		// echo '<pre>';
		// print_r($data);
		// print_r($document_keywords);
		// print_r($default_keyword_data);
		// print_r($default_image_data);
		// echo '</pre>';

		// replace keywords
		foreach ($default_keyword_data as $keyword => $value) {
			if (stripos($html, "#{$keyword}") !== false) {
				$html = str_replace("#{$keyword}", htmlentities($value), $html);
			}
		}

		// replace image source
		foreach ($default_image_data as $path => $value) {
			if (stripos($html, "{$path}") !== false) {
				$pattern 		= '/src="([^"]+)'. str_replace('/', '\/', $path) .'"/';
				$replacement 	= 'src="'. $value .'"';
				$html = preg_replace($pattern, $replacement, $html);
			}
		}

		return $html;
	}

	return false;
}


/**
* get service provided by department
*/
function get_department_service_provided($departmentID, $cache = array())
{

	if (!isset($cache[$departmentID])) {
		$count = 0;
		$applications   = lookup_all('Service_Applications', array(
			'DepartmentID' => $departmentID,
			'Status IN (2, 3)' => NULL
		));

		$count += count($applications);

		foreach ($applications as $application) {
			$applications_requirements   = lookup_all('Service_Application_Requirements', array(
				'ApplicationID' => $application['id'],
				'Status IN (2, 3)' => NULL
			), false, false);

			$count += count($applications_requirements);
		}

		return $count;
	} else {
		return $cache[$departmentID];
	}

}

/**
* get service provider
*/
function get_service_providers($serviceID, $completed = false)
{
	$ci =& get_instance();
	$service = $ci->mgovdb->getRowObject('Service_Services', $serviceID, 'id');
	if ($service) {
		if ($completed) {
			$ci->db->select('DISTINCT(uai.id) AS ID, CONCAT(FirstName, " ", LastName) AS Name, Photo')
					->from('Service_Application_Functions AS saf')
					->join('UserAccountInformation AS uai', 'saf.OfficerID = uai.id')
					->where('ServiceID', $service->id);
		} else {
			$ci->db->select('DISTINCT(uai.id) AS ID, CONCAT(FirstName, " ", LastName) AS Name, Photo')
					->from('Service_FunctionOfficers AS sf')
					->join('UserAccountInformation AS uai', 'sf.AccountID = uai.id')
					->where('ServiceID', $service->id);
		}
		$users = $ci->db->get()->result_array();

		foreach ($users as &$user) {
			$user['Photo'] = public_url('assets/profile/') . photo_filename($user['Photo']);
		}

		if ($service->LocationScopeID == 4 || $service->LocationScopeID == 5 || $service->LocationScopeID == 3) {
			// get public office servants
			if ($service->LocationScopeID == 3) {
				$psgcCode = str_pad($service->ProvincialID, 9, '0');
			} else {
				$psgcCode = str_pad($service->MunicipalityCityID, 9, '0');
			}
			$publicoffice = $ci->mgovdb->getRowObject('PublicOffices', $psgcCode, 'PSGC');
			if ($publicoffice) {
				$servants = json_decode($publicoffice->PublicServants, true);
				uasort($servants, function($a, $b) {
		    		if ($a['Ordering'] == $b['Ordering']) {
				        return 0;
				    }
				    return ($a['Ordering'] < $b['Ordering']) ? -1 : 1;
		    	});
		    	$servants = array_slice($servants, 0, 5);
		    	foreach ($servants as $k => $servant) {
		    		$users[] = array(
		    			'ID'	=> $k,
		    			'Name'	=> $servant['Position'] . ' ' . $servant['Firstname'] . ' ' . $servant['Lastname'],
		    			'Photo'	=> public_url('assets/etc/') . etc_filename($servant['Photo'])
		    		);
		    	}
			}
		}

		return $users;
	} else {
		return array();
	}
}


/**
* prepare payment receipt data
*/
function prepare_payment_receipt_data($paymentData)
{

	$ci =& get_instance();
	$payorData      = user_account_details($paymentData->ApplicantID,'id', false);
    $officerData    = user_account_details($paymentData->OfficerID,'id', false);
    $serviceData    = $ci->mgovdb->getRowObject('Service_Services', $paymentData->ServiceID);

    $scopeName = '';
    $scopeLogo = '';
    switch (strtolower($paymentData->scope)) {
        case 'national':
            break;
        case 'regional':
            $loc       = $ci->mgovdb->getRowObject('UtilLocRegion', $officerData->RegionalID, 'regCode');
            $scopeName = $loc->regDesc;
            $scopeLogo = $loc->logo;
            break;
        case 'provincial':
            $loc       = $ci->mgovdb->getRowObject('UtilLocProvince', $officerData->ProvincialID, 'provCode');
            $scopeName = $loc->provDesc;
            $scopeLogo = $loc->logo;
            break;
        case 'city':
        case 'municipality':
            $loc       = $ci->mgovdb->getRowObject('UtilLocCityMun', $officerData->MunicipalityCityID, 'citymunCode');
            $scopeName = $loc->citymunDesc;
            $scopeLogo = $loc->logo;
            break;
        case 'barangay':
            $cityloc   = $ci->mgovdb->getRowObject('UtilLocCityMun', $officerData->MunicipalityCityID, 'citymunCode');
            $loc       = $ci->mgovdb->getRowObject('UtilLocBrgy', $officerData->BarangayID, 'brgyCode');
            $scopeName = 'Barangay ' . $loc->brgyDesc;
            $scopeLogo = (stripos($loc->logo, 'default') === false ? $loc->logo : $cityloc->logo);
            break;
        case 'department':
            if ($serviceData->SubDepartmentID) {
                $dept       = $ci->mgovdb->getRowObject('Dept_ChildDepartment', $serviceData->SubDepartmentID);
            } else {
                $dept       = $ci->mgovdb->getRowObject('Dept_Departments', $serviceData->DepartmentID);
            }
            $scopeName = $dept->Name;
            $scopeLogo = $dept->Logo;
            break;
    }

    return array(
        'paymentData'   => $paymentData,
        'payorData'     => $payorData,
        'scopeName'     => $scopeName,
        'scopeLogo'     => $scopeLogo
    );
}