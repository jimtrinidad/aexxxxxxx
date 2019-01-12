<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Services extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();

        $this->load->model('servicesdb');
    }

    public function index()
    {
        $viewData = array(
            'pageTitle'         => 'Service Manager',
            'pageDescription'   => '',
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'utils',
                'services'
            ),
        );

        $page_limit = 50;
        $page_start = (int) $this->uri->segment(3);

        $where = array(
            'deletedAt' => NULL
        );
        $order = 'DateAdded';

        // SET SEARCH FILTER
        $filters = array(
            'search_code',
            'search_name',
            'search_scope',
        );
        foreach ($filters as $filter) {

            $$filter           = false;

            if (get_post($filter) !== null) {
                $$filter            = get_post($filter);
                $this->session->set_userdata('service_' . $filter, $$filter);
            } else {
                if (isset($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], 'services') !== false && $this->session->has_userdata('service_' . $filter)) {
                    $$filter    = $this->session->userdata('service_' . $filter);
                } else {
                    $this->session->unset_userdata('service_' . $filter);
                }
            }

            if ($filter == 'search_name' && $$filter != false) {
                $where['CONCAT(Name, Description) LIKE ']  = "%{$$filter}%";
            } else if ($filter == 'search_code' && $$filter != false) {
                $where['Code']  = $$filter;
            } else if ($filter == 'search_scope' && $$filter != false) {
                $where['LocationScopeID']  = $$filter;
            }


            // search params
            $viewData[$filter] = $$filter;

        }

        $paginatationData = $this->mgovdb->getPaginationData('Service_Services', $page_limit, $page_start, $where, $order);

        // prepare data
        $services = array();
        foreach ($paginatationData['data'] as $item) {
            $services[] = prepare_service_data($item);
            // $services[] = $item;
        }

        $paginationConfig = array(
            'base_url'      => base_url('services/index'),
            'total_rows'    => $paginatationData['count'],
            'per_page'      => $page_limit,
            'full_tag_open' => '<ul class="pagination pagination-sm no-margin pull-right">'
        );

        // print_data($services);

        $viewData['services']   = $services;
        $viewData['pagination'] = paginate($paginationConfig);

        // echo '<pre>';print_r($viewData);exit;

        view('pages/services/index', $viewData, 'templates/mgovadmin');
    }


    /**
    * dedicated page for adding and updating service
    */
    public function setup($serviceCode = null)
    {
        $viewData = array(
            'pageTitle'         => 'Service Setup',
            'pageDescription'   => '',
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'utils',
                'servicesetup'
            ),
        );

        if ($serviceCode == null) {
            $serviceCode = microsecID();
        } else {
            $service = $this->mgovdb->getRowObject('Service_Services', $serviceCode, 'Code');
            if ($service) {
                $extraFields     = $this->mgovdb->getRecords('Service_ExtraFormFields', array('ServiceID' => $service->id), 'Ordering');
                $rawrequirements = $this->mgovdb->getRecords('Service_Requirements', array('ServiceID' => $service->id), 'Ordering');
                $rawfunctions    = $this->mgovdb->getRecords('Service_Functions', array('ServiceID' => $service->id), 'Ordering');
                $requirements    = array();
                $functions       = array();
                $officers        = array();

                foreach ($rawrequirements as $requirement) {
                    $requirement['Document'] = lookup_db('Doc_Templates', 'Name', $requirement['DocumentID']);
                    $requirements[$requirement['id']] = $requirement;
                }

                foreach ($rawfunctions as $function) {
                    if ($function['FuntionFor'] == 'Requirement') {
                        $function['Requirement'] = $requirements[$function['RequirementID']]['Document'];
                    }
                    $functions[$function['id']] = $function;
                    $officers[$function['id']] = $this->mgovdb->getRecords('Service_FunctionOfficers', array('FunctionID' => $function['id']));
                    foreach ($officers[$function['id']] as &$officer) {

                        $user = $this->mgovdb->getRowObject('UserAccountInformation', $officer['AccountID'], 'id');
                        if ($user) {
                            $user = (array) $user;
                            $userData = array(
                                'id'        => $user['id'],
                                'firstname' => $user['FirstName'],
                                'lastname'  => $user['LastName'],
                                'fullname'  => $user['FirstName'] . ' ' . $user['LastName'],
                                'mabuhayID' => $user['MabuhayID'],
                                'email'     => $user['EmailAddress'],
                                'contact'   => $user['ContactNumber'],
                                'gender'    => lookup('gender', $user['GenderID']),
                                'address'   => array_values(array_reverse(lookup_address($user))),
                                'photo'     => photo_filename($user['Photo']),
                                'actype'    => lookup('account_type', $user['AccountTypeID']),
                                'aclevel'   => lookup_db('UserAccountLevel', 'LevelName', $user['AccountLevelID'])
                            );
                            $officer['userData'] = $userData;
                        }
                    }
                }

                $processOrder = get_service_process_order($service->id);

                $viewData['serviceData']    = $service;
                $viewData['extraFields']    = $extraFields;
                $viewData['requirements']   = $requirements;
                $viewData['functions']      = $functions;
                $viewData['officers']       = $officers;
                $viewData['processOrder']   = $processOrder;

                $viewData['returnUrl']      = (isset($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], 'index') !== false ? $_SERVER['HTTP_REFERER'] : site_url('services'));
            }
        }

        $viewData['serviceCode'] = $serviceCode;

        // print_data($viewData);

        view('pages/services/setupwizard', $viewData, 'templates/mgovadmin');
    }


    public function save_setup()
    {

        if (validate('service_base_setup') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array(),
                'group'     => 1
            );
        } else {

            $fields        = get_post('Field');
            $fields_error  = array();
            $extraFields   = array();

            //validate fields
            if ($fields) {
                foreach ($fields as $fields_key => $fieldItem) {
                    if (!validate('service_fields_setup', $fieldItem)) {
                        $fields_error[$fields_key] = validation_error_array();
                    } else {
                        $fields[$fields_key]['ID'] = random_letters();
                        $extraFields = $fields;
                    }
                }
            }

            if (count($fields_error)) {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Additional fields have errors.',
                    'fields'    => $fields_error,
                    'group'     => 2
                );
            } else {

                $requirement_errors = array();
                $function_errors    = array();

                $raw_requirements   = get_post('Requirement');
                $raw_functions      = get_post('Function');
                $raw_officers       = get_post('Officer');

                $main_service_functions = array();
                $clean_requirements     = array();

                // RULES
                // require atleast 1 main function, or 1 requirement on service
                // require atleast 1 function on each requirements
                // require atleast 1 officer on each functions

                // add officers on function data
                if (is_array($raw_officers)) {
                    foreach ($raw_officers as $key => $accounts) {
                        if (isset($raw_functions[$key])) {
                            $raw_functions[$key]['Officer'] = $accounts;
                        }
                    }
                }

                // add function on requirement data
                if (is_array($raw_functions)) {
                    foreach ($raw_functions as $key => $function) {
                        if (isset($function['Officer']) && count($function['Officer'])) {
                            $requirement_key = $function['For'];
                            if ($requirement_key != 'Main') {
                                if (isset($raw_requirements[$requirement_key]['DocID'])) {
                                    if (!isset($raw_requirements[$requirement_key]['Function'])) {
                                        $raw_requirements[$requirement_key]['Function'] = array();
                                    }
                                    $raw_requirements[$requirement_key]['Function'][$key] = $function;
                                }
                            } else {
                                $main_service_functions[$key] = $function;
                            }
                        } else {
                            $function_errors[$key] = 'No assigned officer this function.';
                        }
                    }
                }

                // clean requirements
                if (is_array($raw_requirements)) {
                    foreach ($raw_requirements as $key => $requirement) {
                        if (isset($requirement['DocID'])) {
                            if (isset($requirement['Function']) && count($requirement['Function'])) {
                                $clean_requirements[$key] = $requirement;
                            } else {
                                $requirement_errors[$key] = 'No function set on this requirements';
                            }
                        }
                    }
                }

                if (count($function_errors)) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Some functions doesn\'t have any assigned officer.',
                        'fields'    => $function_errors,
                        'group'     => 3
                    );
                } else if (count($requirement_errors)) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Some requirements doesn\'t have set function.',
                        'fields'    => $requirement_errors,
                        'group'     => 3
                    );
                } else {

                    if (count($clean_requirements) == 0 && count($main_service_functions) == 0) {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Service requires atleast one main service function or one requirement.',
                            'fields'    => array()
                        );
                    } else {

                        // do service logo upload if exists
                        // use service code as filename
                        $randomLogoName = md5(get_post('Code'));

                        // validate file upload
                        $this->load->library('upload', array(
                            'upload_path'   => LOGO_DIRECTORY,
                            'allowed_types' => 'gif|jpg|png',
                            'max_size'      => '2000', // 2mb
                            'max_width'     => '1024',
                            'max_height'    => '768',
                            'overwrite'     => true,
                            'file_name'     => $randomLogoName
                        ));

                        if (!empty($_FILES['Logo']['name']) && $this->upload->do_upload('Logo') == false) {
                            $return_data = array(
                                'status'    => false,
                                'message'   => 'Uploading logo failed.',
                                'fields'    => array('Logo' => $this->upload->display_errors('',''))
                            );
                        } else {

                            $uploadData     = $this->upload->data();

                            // PREPARE DATA TO SAVE
                            $deparmentData = $this->mgovdb->getRowObject('Dept_ScopeLocations', get_post('DepartmentScope'), 'id');

                            if (!$deparmentData) {
                                $return_data = array(
                                    'status'    => false,
                                    'message'   => 'Invalid department.',
                                    'fields'    => array('DepartmentLocationID' => 'Invalid item.'),
                                    'group'     => 1
                                );
                            } else {

                                $infoData = array(
                                    'Code'              => get_post('Code'),
                                    'Name'              => get_post('Name'),
                                    'ServiceType'       => get_post('ServiceType'),
                                    'LocationScopeID'   => get_post('LocationScope'),
                                    'LocationCode'      => get_post('LocationCode'),
                                    'DepartmentID'      => $deparmentData->DepartmentID,
                                    'SubDepartmentID'   => $deparmentData->SubDepartmentID,
                                    'DepartmentLocationID' => $deparmentData->id,
                                    'Description'       => get_post('Description'),
                                    'TermsCondition'    => get_post('TermsCondition'),
                                    'Objectives'        => get_post('Objectives'),
                                    'Qualifications'    => get_post('Qualifications'),
                                    'Tags'              => (get_post('Tags') ? json_encode(get_post('Tags')) : ''),
                                    'Limit'             => get_post('Limit'),
                                    'CycleInterval'     => get_post('CycleInterval'),
                                    'LastUpdate'        => date('Y-m-d H:i:s'),
                                );

                                if (!empty($_FILES['Logo']['name'])) {
                                    $infoData['Logo'] = $uploadData['file_name'];
                                }

                                // location breakdown
                                $location_breakdown = lookup_get_location_breakdown(get_post('LocationScope'), get_post('LocationCode'));

                                $infoData = array_merge($infoData, $location_breakdown);

                                $saveData = array(
                                    'information'  => $infoData,
                                    'extraFields'  => $extraFields,
                                    'requirements' => $clean_requirements,
                                    'mainFunctions'=> $main_service_functions
                                );

                                $serviceExists = $this->mgovdb->getRowObject('Service_Services', get_post('Code'), 'Code');
                                if ($serviceExists) {
                                    // update
                                    $saveData['ServiceID'] = $serviceExists->id;
                                    $saveData['information']['id'] = $serviceExists->id;
                                    
                                    if ($this->servicesdb->updateService($saveData)) {
                                        $return_data = array(
                                            'status'    => true,
                                            'type'      => 'edit',
                                            'message'   => 'Service setup has been updated successfully.'
                                        );

                                    } else {
                                        $return_data = array(
                                            'status'    => false,
                                            'message'   => 'Updating service setup failed. Please try again.'
                                        );
                                        @unlink($uploadData['full_path']);
                                    }

                                } else {
                                    // new record
                                    $saveData['information']['CreatorID'] = current_user();
                                    $saveData['information']['DateAdded'] = date('Y-m-d H:i:s');

                                    if ($this->servicesdb->addNewService($saveData)) {
                                        $return_data = array(
                                            'status'    => true,
                                            'type'      => 'new',
                                            'message'   => 'Service has been setup successfully.'
                                        );
                                    } else {
                                        $return_data = array(
                                            'status'    => false,
                                            'message'   => 'Saving service data failed. Please try again.'
                                        );
                                        @unlink($uploadData['full_path']);
                                    }
                                }

                            }

                        }

                    }

                }

            }

        }

        response_json($return_data);
    }


    /**
    * find officer to assign
    */
    public function find_officer()
    {   
        $query = get_post('q');
        $where =  'deletedAt IS NULL' .
                    'AND (MabuhayID LIKE "' . $query . '%" OR CONCAT(FirstName, " ", LastName) LIKE "' . $query . '%")' .
                    'AND AccountTypeID IN(2,3)';
        $users = $this->mgovdb->getRecords('UserAccountInformation', $where);
        $items = array();
        foreach ($users as $user) {
            $items[] = array(
                'id'        => $user['id'],
                'firstname' => $user['FirstName'],
                'lastname'  => $user['LastName'],
                'fullname'  => $user['FirstName'] . ' ' . $user['LastName'],
                'mabuhayID' => $user['MabuhayID'],
                'email'     => $user['EmailAddress'],
                'contact'   => $user['ContactNumber'],
                'gender'    => lookup('gender', $user['GenderID']),
                'address'   => array_values(array_reverse(lookup_address($user))),
                'photo'     => photo_filename($user['Photo']),
                'actype'    => lookup('account_type', $user['AccountTypeID']),
                'aclevel'   => lookup_db('UserAccountLevel', 'LevelName', $user['AccountLevelID'])
            );
        }
        response_json($items);
    }


    /**
    * approve pending service
    */
    public function approve($code)
    {
        if ($code) {
            $service = $this->mgovdb->getRowObject('Service_Services', $code, 'Code');
            if ($service) {
                if ($service->Status == 0) {
                    $updateData = array(
                        'id'          => $service->id,
                        'Status'      => 1,
                        'ApproverID'  => current_user(),
                        'ApproveDate' => date('Y-m-d H:i:s'),
                        'LastUpdate'  => date('Y-m-d H:i:s')
                    );
                    if ($this->mgovdb->saveData('Service_Services', $updateData)) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Service has been activated.'
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Updating service status failed.'
                        );
                    }
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Service is not on Pending state.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid service code.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid service code.'
            );
        }

        response_json($return_data);
    }

    /**
    * delete service
    */
    public function delete($code)
    {
        if ($code) {
            $service = $this->mgovdb->getRowObject('Service_Services', $code, 'Code');
            if ($service) {
                $updateData = array(
                    'id'         => $service->id,
                    'deletedBy'  => current_user(),
                    'deletedAt'  => date('Y-m-d H:i:s')
                );
                if ($this->mgovdb->saveData('Service_Services', $updateData)) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Service has been deleted.'
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Deleting service failed.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid service code.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid service code.'
            );
        }

        response_json($return_data);
    }


    /**
    * assign service support
    */
    public function add_support($code)
    {
        if ($code) {
            $service = $this->mgovdb->getRowObject('Service_Services', $code, 'Code');
            if ($service) {
                $supports = json_decode($service->Supports, true);
                $officer  = get_post('officer');
                if (!in_array($officer, $supports)) {
                    $supports[] = $officer;
                    $updateData = array(
                        'id'         => $service->id,
                        'Supports'   => json_encode(array_values($supports)),
                        'LastUpdate' => date('Y-m-d H:i:s')
                    );
                    if ($this->mgovdb->saveData('Service_Services', $updateData)) {

                        $this->load->library('mahana_messaging');

                        // add officer to exising message threads
                        $threads    = $this->mgovdb->getRecords('msg_threads', array('key' => $service->Code));
                        foreach ($threads as $thread) {
                            // remove first to clear previous message if exists
                            $this->mahana_messaging->remove_participant($thread['id'], $officer);
                            
                            $this->mahana_messaging->add_participant($thread['id'], $officer);
                        }

                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Service support has been assigned.'
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Assigning service support failed.'
                        );
                    }
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Support already assigned.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid service code.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid service code.'
            );
        }

        response_json($return_data);
    }

    /**
    * remove support
    */
    public function remove_support($code)
    {
        if ($code) {
            $service = $this->mgovdb->getRowObject('Service_Services', $code, 'Code');
            if ($service) {
                $supports = json_decode($service->Supports, true);
                $officer  = get_post('officer');

                if (($key = array_search($officer, $supports)) !== false) {
                    unset($supports[$key]);
                }

                $updateData = array(
                    'id'         => $service->id,
                    'Supports'   => json_encode(array_values($supports)),
                    'LastUpdate' => date('Y-m-d H:i:s')
                );
                if ($this->mgovdb->saveData('Service_Services', $updateData)) {

                    $this->load->library('mahana_messaging');

                    // remove officer to exising message threads
                    $threads    = $this->mgovdb->getRecords('msg_threads', array('key' => $service->Code));
                    foreach ($threads as $thread) {
                        $this->mahana_messaging->remove_participant($thread['id'], $officer);
                    }

                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Service support has been removed.'
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Removing service support failed.'
                    );
                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid service code.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid service code.'
            );
        }

        response_json($return_data);
    }

}
