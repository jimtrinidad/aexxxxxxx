<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Department extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();

        $this->load->model('departmentdb');
    }

    /**
    * main and sub department listing and manager
    */
    public function index()
    {
        $viewData = array(
            'pageTitle'         => 'Department Manager',
            // 'pageDescription'   => 'Manage department settings.',
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'department',
                'utils'
            ),
        );        

        // if admin, get parent department and child department
        // if pr. redirect to department officers

        $viewData['departments'] = $this->getDepartment(get_post('searchKeyword'));

        view('pages/department/index', $viewData, 'templates/mgovadmin');
    }


    /**
    * page where we can assign officers for each scope location
    * will get locations under PR level
    * eg: Regional PR can setup department Officer for Provinces under his/her region
    */
    public function officers()
    {
        $viewData = array(
            'pageTitle'         => 'Department Location & Officer Manager',
            'pageDescription'   => 'Manage department location scope and officers.',
            'jsModules'         => array(
                'department',
                'utils'
            ),
            'accountInfo'       => user_account_details(),
        );

        view('pages/department/officers', $viewData, 'templates/mgovadmin');
    }


    /**
    * save parent department settings
    */
    public function save_department()
    {

        if (validate('save_department') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $randomLogoName = md5(microsecID());

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

                // do save
                $uploadData     = $this->upload->data();

                $getCodeWhere   = array('Code =' => get_post('Code'));

                $fields         = $this->mgovdb->tableColumns('Dept_Departments');
                $excludeField   = array('id', 'Logo', 'deletedAt');

                $existingData   = false;
                $insertData     = array(
                    'LastUpdate'  => date('Y-m-d H:i:s')
                );

                foreach ($fields as $field) {
                    if (!in_array($field, $excludeField) && !array_key_exists($field, $insertData) && get_post($field) !== null) {
                        $insertData[$field] = get_post($field);
                    }
                }

                if (!empty($_FILES['Logo']['name'])) {
                    $insertData['Logo'] = $uploadData['file_name'];
                }

                if (get_post('id')) {
                    $insertData['id'] = get_post('id');
                    $existingData = $this->mgovdb->getRowObject('Dept_Departments', get_post('id'), 'id');
                    $getCodeWhere['id !='] = get_post('id');
                }

                // if edit, skip code checking
                $codeExists = $this->mgovdb->getRecords('Dept_Departments', $getCodeWhere);
                if (count($codeExists) == 0) {

                    if (($ID = $this->mgovdb->saveData('Dept_Departments', $insertData))) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Department has been saved successfully.',
                            'id'        => $ID,
                            'data'      => $insertData
                        );

                        // delete old logo if edited
                        if ($existingData !== false && isset($insertData['Logo'])) {
                            @unlink(LOGO_DIRECTORY . $existingData->Logo);
                        }

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Saving department failed. Please try again.'
                        );
                        @unlink($uploadData['full_path']);
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Possible department duplicate. Code already exists.'
                    );
                    @unlink($uploadData['full_path']);
                }

            }
        }
        response_json($return_data);
    }

    /**
    * save parent department settings
    */
    public function save_sub_department()
    {

        if (validate('save_sub_department') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $randomLogoName = md5(microsecID());

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

                // do save
                $uploadData     = $this->upload->data();

                $getCodeWhere   = array('Code =' => get_post('Code'));

                $fields         = $this->mgovdb->tableColumns('Dept_ChildDepartment');
                $excludeField   = array('id', 'Logo', 'deletedAt');

                $existingData   = false;
                $insertData     = array(
                    'LastUpdate'  => date('Y-m-d H:i:s')
                );

                foreach ($fields as $field) {
                    if (!in_array($field, $excludeField) && !array_key_exists($field, $insertData) && get_post($field)) {
                        $insertData[$field] = get_post($field);
                    }
                }

                if (!empty($_FILES['Logo']['name'])) {
                    $insertData['Logo'] = $uploadData['file_name'];
                }

                if (get_post('id')) {
                    $insertData['id'] = get_post('id');
                    $existingData = $this->mgovdb->getRowObject('Dept_ChildDepartment', get_post('id'), 'id');
                    $getCodeWhere['id !='] = get_post('id');
                }

                // if edit, skip code checking
                $codeExists = $this->mgovdb->getRecords('Dept_ChildDepartment', $getCodeWhere);
                if (count($codeExists) == 0) {

                    if (($ID = $this->mgovdb->saveData('Dept_ChildDepartment', $insertData))) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Sub department has been saved successfully.',
                            'id'        => $ID,
                            'data'      => $insertData
                        );

                        // delete old logo if edited
                        if ($existingData !== false && isset($insertData['Logo'])) {
                            @unlink(LOGO_DIRECTORY . $existingData->Logo);
                        }

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Saving sub department failed. Please try again.'
                        );
                        @unlink($uploadData['full_path']);
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Possible sub department duplicate. Code already exists.'
                    );
                    @unlink($uploadData['full_path']);
                }

            }
        }
        response_json($return_data);
    }

    /**
    * delete department
    */
    public function delete_department($id)
    {
        if ($id) {
            $item = $this->mgovdb->getRowObject('Dept_Departments', $id, 'id');
            if ($item) {
                $updateData = array(
                    'id'         => $item->id,
                    'deletedBy'  => current_user(),
                    'deletedAt'  => date('Y-m-d H:i:s')
                );
                if ($this->mgovdb->saveData('Dept_Departments', $updateData)) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Department has been deleted.'
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Deleting department failed.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid department code.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid department code.'
            );
        }

        response_json($return_data);
    }

    /**
    * delete sub department
    */
    public function delete_sub_department($id)
    {
        if ($id) {
            $item = $this->mgovdb->getRowObject('Dept_ChildDepartment', $id, 'id');
            if ($item) {
                $updateData = array(
                    'id'         => $item->id,
                    'deletedBy'  => current_user(),
                    'deletedAt'  => date('Y-m-d H:i:s')
                );
                if ($this->mgovdb->saveData('Dept_ChildDepartment', $updateData)) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Sub department has been deleted.'
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Deleting sub department failed.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid sub department code.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid sub department code.'
            );
        }

        response_json($return_data);
    }


    /**
    * get location and officers
    * json response
    */
    public function get_locations_officers()
    {
        $items       = array();
        $scope       = get_post('scope');
        
        if ($scope) {

            if ((int)$scope !== 1 && !get_post('location_code')) {

                $return_data = array(
                    'status'    => false,
                    'message'   => 'Location is required.'
                );

            } else {
            
                $departments = $this->getDepartment(get_post('keyword'));
                foreach ($departments as $department) {

                    // get main department locations if has any
                    // main has no sub department id
                    $locationWhere = array(
                                        'deletedAt IS NULL',
                                        'DepartmentID'    => $department['id'],
                                        'SubDepartmentID' => 0, 
                                        'LocationScope' => $scope
                                    );
                    // not national
                    if ((int)$scope !== 1) {
                        $locationWhere['LocationCode'] = get_post('location_code');
                    }

                    $location = $this->mgovdb->getRecords('Dept_ScopeLocations', $locationWhere, 'id', array(1));
                    if (count($location)) {
                        $location = $location[0];
                        $location['officers'] = $this->departmentdb->getDepartmentOfficer($location['id'], 'DepartmentLocationID');
                    } else {
                        $location = false;
                    }
                    $department['location'] = $location;

                    $subDepartments = array();
                    foreach ($department['subDepartment'] as $subDepartment) {
                        // get sub department locations if has any
                        $locationWhere = array(
                                            'deletedAt IS NULL',
                                            'DepartmentID'    => $department['id'],
                                            'SubDepartmentID' => $subDepartment['id'], 
                                            'LocationScope' => $scope
                                        );

                        // not national
                        if ((int)$scope !== 1) {
                            $locationWhere['LocationCode'] = get_post('location_code');
                        }

                        $location = $this->mgovdb->getRecords('Dept_ScopeLocations', $locationWhere, 'id', array(1));
                        if (count($location)) {
                            $location = $location[0];
                            $location['officers'] = $this->departmentdb->getDepartmentOfficer($location['id'], 'DepartmentLocationID');
                        } else {
                            $location = false;
                        }

                        $subDepartment['location'] = $location;

                        if (get_post('result_filter') == 1) {
                            // all active
                            if ($subDepartment['location'] != false && $subDepartment['location']['Status']) {
                                $subDepartments[] = $subDepartment;
                            }
                        } else if (get_post('result_filter') == 2) {
                            // active with officer
                            if ($subDepartment['location'] != false && $subDepartment['location']['Status'] && $subDepartment['location']['officers'] != false) {
                                $subDepartments[] = $subDepartment;
                            }
                        } else if (get_post('result_filter') == 3) {
                            // active without officer
                            if ($subDepartment['location'] != false && $subDepartment['location']['Status'] && $subDepartment['location']['officers'] == false) {
                                $subDepartments[] = $subDepartment;
                            }
                        } else if (get_post('result_filter') == 4) {
                            // inactive
                            if ($subDepartment['location'] == false || ($subDepartment['location'] != false && $subDepartment['location']['Status'] == 0)) {
                                $subDepartments[] = $subDepartment;
                            }
                        } else {
                            // show all
                            $subDepartments[] = $subDepartment;
                        }
                    }

                    $department['subDepartment'] = $subDepartments;

                    $exclude = true;
                    // if no sub department. also filter main department result
                    if (get_post('result_filter') == 1) {
                        // all active
                        if ($department['location'] != false && $department['location']['Status']) {
                            $items[] = $department;
                            $exclude = false;
                        }
                    } else if (get_post('result_filter') == 2) {
                        // active with officer
                        if ($department['location'] != false && $department['location']['Status'] && $department['location']['officers'] != false) {
                            $items[] = $department;
                            $exclude = false;
                        }
                    } else if (get_post('result_filter') == 3) {
                        // active without officer
                        if ($department['location'] != false && $department['location']['Status'] && $department['location']['officers'] == false) {
                            $items[] = $department;
                            $exclude = false;
                        }
                    } else if (get_post('result_filter') == 4) {
                        // inactive
                        if ($department['location'] == false || ($department['location'] != false && $department['location']['Status'] == 0)) {
                            $items[] = $department;
                            $exclude = false;
                        }
                    } else {
                        // show all
                        $items[] = $department;
                        $exclude = false;
                    }

                    if (count($subDepartments) && $exclude) {
                        $department['hideParent'] = true;
                        $items[] = $department;
                    }

                }

                if (count($items)) {
                    $return_data = array(
                        'status'    => true,
                        'data'      => $items
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'No record found.'
                    );
                }

            }

        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Scope is required.'
            );
        }

        response_json($return_data);
    }


    /**
    * save department scope location
    */
    public function save_department_location()
    {
        if (validate('save_department_location') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {
            $locID           = get_post('id');
            $DepartmentID    = get_post('DepartmentID');
            $SubDepartmentID = (int) get_post('SubDepartmentID');
            $scopeCode       = (int) get_post('LocationScope');
            $locationCode    = get_post('LocationCode');

            // find loc by id
            $locData = $this->mgovdb->getRowObject('Dept_ScopeLocations', $locID, 'id');
            if (!$locData) {
                // find by data
                $locationWhere = array(
                                    'DepartmentID'    => $DepartmentID,
                                    'SubDepartmentID' => $SubDepartmentID, 
                                    'LocationScope'   => $scopeCode,
                                    'LocationCode'    => $locationCode
                                );
                $locations = $this->mgovdb->getRecords('Dept_ScopeLocations', $locationWhere, 'id', array(1));
                if (count($locations)) {
                    $locData = (object) $locations[0];
                }
            }

            if ($locData != false) {
                // exists, just update
                $insertData = array(
                    'id'        => $locData->id
                );

            } else {
                // new record
                $insertData = array(
                    'DepartmentID'      => $DepartmentID,
                    'SubDepartmentID'   => $SubDepartmentID,
                    'LocationScope'     => $scopeCode,
                    'LocationCode'      => $locationCode
                );
            }

            $insertData['Status']     = get_post('Status');
            $insertData['Address']    = get_post('Address');
            $insertData['Contact']    = get_post('Contact');
            $insertData['LastUpdate'] = date('Y-m-d H:i:s');

            if (($ID = $this->mgovdb->saveData('Dept_ScopeLocations', $insertData))) {
                $return_data = array(
                    'status'    => true,
                    'message'   => 'Department scope location has been saved successfully.',
                    'id'        => $ID,
                    'data'      => $insertData,
                );

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Saving department scope location failed. Please try again.',
                );
            }
        }

        response_json($return_data);
    }

    /**
    * add department location officer
    */
    public function add_department_officer()
    {
        if (validate('save_department_officer') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {
            $locID           = get_post('DepartmentLocationID');
            $DepartmentID    = get_post('DepartmentID');
            $SubDepartmentID = (int) get_post('SubDepartmentID');
            $accountID       = get_post('SelectedAccountID');
            $functionTypeID  = (int) get_post('FunctionTypeID');
            $position        = get_post('Position');

            // find by data
            $officerWhere = array(
                                'DepartmentID'          => $DepartmentID,
                                'SubDepartmentID'       => $SubDepartmentID, 
                                'DepartmentLocationID'  => $locID,
                                'AccountID'             => $accountID
                            );
            $officer = $this->mgovdb->getRecords('Dept_Officers', $officerWhere, 'id', array(1));
            if (count($officer)) {
                $officer = (object) $officer[0];
            }

            if ($officer == false) {
                // new record
                $insertData = array(
                    'DepartmentID'      => $DepartmentID,
                    'SubDepartmentID'   => $SubDepartmentID,
                    'DepartmentLocationID'  => $locID,
                    'AccountID'         => $accountID,
                    'FunctionTypeID'    => $functionTypeID,
                    'Position'          => $position,
                    'LastUpdate'        => date('Y-m-d H:i:s')
                );

                if (($ID = $this->mgovdb->saveData('Dept_Officers', $insertData))) {
                    usleep(100);//minor delay fetch after add
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Department officer has been assigned successfully.',
                        'id'        => $ID,
                        'data'      => $this->departmentdb->getDepartmentOfficer($ID, 'id')
                    );

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Assigning department officer failed. Please try again.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Officer already assigned on this deparment.',
                );
            }

        }

        response_json($return_data);
    }


    /**
    * remove officer from department location scope
    */
    public function remove_officer()
    {
        if ($this->mgovdb->deleteData('Dept_Officers', get_post('o'))) {
            $return_data = array(
                'status'    => true,
                'message'   => 'Officer has been removed.'
            );
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Removing officer failed.'
            );
        }

        response_json($return_data);
    }

    /**
    * update officer function
    */
    public function set_officer_function()
    {
        if (get_post('pk')) {
            
            $updateData = array(
                'id'             => get_post('pk'),
                'FunctionTypeID' => (int) get_post('value'),
                'LastUpdate'     => date('Y-m-d H:i:s')
            );

            if ($this->mgovdb->saveData('Dept_Officers', $updateData)) {
                $return_data = array(
                    'status'    => true,
                    'message'   => 'Officer function has been updated.'
                );
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Updating officer function failed.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Officer is required.'
            );
        }

        response_json($return_data);
    }


    /**
    * update officer position
    */
    public function set_officer_position()
    {
        if (get_post('pk')) {
            
            $updateData = array(
                'id'        => get_post('pk'),
                'Position'  => get_post('value'),
                'LastUpdate'=> date('Y-m-d H:i:s')
            );

            if ($this->mgovdb->saveData('Dept_Officers', $updateData)) {
                $return_data = array(
                    'status'    => true,
                    'message'   => 'Officer position has been updated.'
                );
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Updating officer position failed.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Officer is required.'
            );
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
    * get location by scope
    * use helper
    */
    public function get_scope_locations()
    {    
        response_json(lookup_scope_locations(get_post('scope')));
    }


    /**
    * get department and subdepartment by scope and locationcode
    */
    public function get_departments_by_scope_location()
    {
        $scope      = get_post('scope');
        $location   = get_post('location');

        $locationWhere = array(
                            'deletedAt IS NULL',
                            'Status' => 1,
                            'LocationScope' => $scope
                        );

        // not national
        if ((int)$scope !== 1) {
            $locationWhere['LocationCode'] = $location;
        }

        $departments = array();
        $department_locations = $this->mgovdb->getRecords('Dept_ScopeLocations', $locationWhere);

        foreach ($department_locations as $dept) {

            // sub department
            if ($dept['SubDepartmentID'] != 0) {
                $parentData = $this->mgovdb->getRowObject('Dept_ChildDepartment', $dept['SubDepartmentID'], 'id');
                $dept['type'] = 'sub';
            } else {
                $parentData = $this->mgovdb->getRowObject('Dept_Departments', $dept['DepartmentID'], 'id');
                $dept['type'] = 'main';
            }

            unset($dept['LastUpdate']);
            unset($dept['deletedAt']);

            if ($parentData) {

                $parentData->Logo = logo_filename($parentData->Logo);

                unset($parentData->LastUpdate);
                unset($parentData->deletedAt);

                $dept['parent'] = (array) $parentData;
                $departments[]  = $dept;
            }
        }

        response_json($departments);
    }


    /**
    * private get departments and its subdepartment
    */
    private function getDepartment($keyword)
    {
        $mainDepartments = $this->departmentdb->getDepartment($keyword);
        $departments     = array();

        foreach ($mainDepartments as $mainDepartment) {

            // if match on main dept, show all sub
            // else filter child result with keyword
            if (stripos($mainDepartment['Name'], $keyword) !== false || stripos($mainDepartment['Code'], $keyword) !== false) {
                $subDepartments = $this->departmentdb->getChildDepartment($mainDepartment['id']);
            } else {
                $subDepartments = $this->departmentdb->getChildDepartment($mainDepartment['id'], $keyword);
            }
            
            $mainDepartment['subDepartment'] = array();
            foreach ($subDepartments as $subDepartment) {

                $subDepartment['Logo'] = logo_filename($subDepartment['Logo']);
                $mainDepartment['subDepartment'][$subDepartment['id']] = $subDepartment;

            }

            $mainDepartment['Logo'] = logo_filename($mainDepartment['Logo']);

            $departments[$mainDepartment['id']] = $mainDepartment;
        }

        return $departments;
    }

}
