<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Coa extends CI_Controller
{

    private $user;
    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();

        $user = $this->mgovdb->getRowObject('UserAccountInformation', current_user(), 'id');
        if (!$user->OrganizationID || !in_array($user->OrganizationID, lookup('coa_organizations'))) {
            redirect();
        }

        $this->user = $user;

    }

    public function projects()
    {
        $viewData = array(
            'pageTitle'     => 'Organization - Projects',
            'accountInfo'   => user_account_details(),
            'jsModules'         => array(
                'coa',
            ),
        );

        $organizationData        = $this->mgovdb->getRowObject('Dept_ChildDepartment', $this->user->OrganizationID);
        $organizationData->Setup = $this->mgovdb->getRowObject('Dept_OrganizationSetup', $organizationData->UniqueCode, 'UniqueCode');

        $viewData['Organization']   = $organizationData;

        $projects = array();
        $items = lookup_all('OrganizationProjects', array(
                    'OrganizationID'    => $this->user->OrganizationID
                ), 'Name');
        foreach ($items as $item) {
            $projects[] = array(
                    'id'    => $item['id'],
                    'Code'  => $item['Code'],
                    'Name'  => $item['Name'],
                    'Description'   => $item['Description'],
                    'Scope'         => $item['LocationScopeID'],
                    'Categories'    => count($this->mgovdb->getRecords('OrganizationProjectServices', array('ProjectID' => $item['id'], 'Status' => 1))),
                    'Allocations'   => $this->db->select_sum('Allocation')->select_sum('Quantity')->select_sum('1', 'Count')
                                                    ->from('OrganizationProjectServiceItems psi')
                                                    ->join('OrganizationProjectServices ps', 'ps.id = psi.ProjectServiceID')
                                                    ->where('psi.ProjectID', $item['id'])
                                                    ->where('ps.Status', 1      )
                                                    ->get()
                                                    ->row_array()
            ); 
        }

        // print_data($projects, true);

        $viewData['projects'] = $projects;

        view('main/coa/projects', $viewData, 'templates/mgov');
    }

    public function saveproject()
    {
        if (validate('save_project') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $organization = $this->mgovdb->getRowObject('Dept_ChildDepartment', get_post('OrganizationID'));
            if ($organization) {

                $saveData = array(
                    'Name'              => get_post('Name'),
                    'Description'       => get_post('Description'),
                    'LocationScopeID'   => get_post('LocationScopeID'),
                    'LastUpdate'        => date('Y-m-d H:i:s')
                );

                $projectData = $this->mgovdb->getRowObject('OrganizationProjects', get_post('Code'), 'Code');
                if ($projectData) {
                    $saveData['id'] = $projectData->id;
                } else {
                    $saveData['Code']           = microsecID();
                    $saveData['OrganizationID'] = get_post('OrganizationID');
                    $saveData['CreatorID']      = current_user();
                    $saveData['DateAdded']      = date('Y-m-d H:i:s');
                }

                if (($ID = $this->mgovdb->saveData('OrganizationProjects', $saveData))) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Project has been saved.',
                        'id'        => $ID
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Saving project failed. Please try again later.'
                    );
                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Organization not found.'
                );
            }
        }

        response_json($return_data);
    }

    public function deleteproject($id)
    {
        if ($id) {
            $record = $this->mgovdb->getRowObject('OrganizationProjects', $id, 'Code');
            if ($record) {
                $updateData = array(
                    'id'         => $record->id,
                    'deletedBy'  => current_user(),
                    'deletedAt'  => date('Y-m-d H:i:s')
                );
                if ($this->mgovdb->saveData('OrganizationProjects', $updateData)) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Organization project has been deleted.'
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Deleting proejct failed.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid project.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid project.'
            );
        }

        response_json($return_data);
    }


    /**
    * project details
    */
    public function project($code)
    {
        if ($code) {
            $record = $this->mgovdb->getRowObject('OrganizationProjects', $code, 'Code');
            if ($record) {
                
                $projectservices = $this->db->select('ps.id AS id, ps.Status, ss.id AS ServiceID, ss.Code, ss.Name, ss.Description, ss.Logo')
                                        ->from('OrganizationProjectServices ps')
                                        ->join('Service_Services ss', 'ss.id = ps.ServiceID')
                                        ->where('ps.ProjectID', $record->id)
                                        ->order_by('Status DESC, Name')
                                        ->get()->result_array();
                
                foreach ($projectservices as &$ps) {
                    $ps['items']   = $this->mgovdb->getRecords('OrganizationProjectServiceItems', array('ProjectServiceID' => $ps['id']), 'Name');
                }

                $viewData = array(
                    'pageTitle'     => 'Organization - Projects',
                    'accountInfo'   => user_account_details(),
                    'jsModules'         => array(
                        'coa',
                    ),
                );

                $viewData['projectData']    = array(
                    'id'    => $record->id,
                    'Code'  => $record->Code,
                    'Name'  => $record->Name,
                    'Description'   => $record->Description,
                    'Categories'    => $projectservices,
                );

                $viewData['availableServices'] = $this->mgovdb->getProjectServices(array(
                    'userID'        => current_user(),
                    'organization'  => $this->user->OrganizationID,
                    'category'      => 7, // category id of expenses
                ));

                // print_data($viewData);

                view('main/coa/projectdetails', $viewData, 'templates/mgov');

            } else {
                redirect('coa/projects');
            }
        } else {
            redirect('coa/projects');
        }
    }


    public function addprojectcategory()
    {
        $record = $this->mgovdb->getRowObject('OrganizationProjects', get_post('Code'), 'Code');
        if ($record) {

            if (get_post('Service')) {

                $psData = $this->mgovdb->getRowObjectWhere('OrganizationProjectServices', array(
                                'ProjectID' => $record->id,
                                'ServiceID' => get_post('Service')
                            ));

                if ($psData) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Category was already added on this project.'
                    );
                } else {
                    $insertData = array(
                        'ProjectID' => $record->id,
                        'ServiceID' => get_post('Service')
                    );

                    if ($this->mgovdb->saveData('OrganizationProjectServices', $insertData)) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Category has been added succesfully.'
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Adding category failed. Please try again later'
                        );
                    }
                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Category is required.'
                );
            }

        } else {
             $return_data = array(
                'status'    => false,
                'message'   => 'Invalid project.'
            );
        }

        response_json($return_data);
    }

    public function projectcategorystatus($code = null, $psid = null, $status = null)
    {
        $record = $this->mgovdb->getRowObject('OrganizationProjects', $code, 'Code');
        if ($record) {
            $psData = $this->mgovdb->getRowObject('OrganizationProjectServices', $psid);
            if ($psData) {
                if (in_array($status, array(1,0))) {
                    $updateData = array(
                        'id'        => $psData->id,
                        'Status'    => $status
                    );
                    if ($this->mgovdb->saveData('OrganizationProjectServices', $updateData)) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Category status has been updated.'
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Updating category status failed. Please try again later'
                        );
                    }
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Invalid status'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Project category not found.'
                );
            }
        } else {
             $return_data = array(
                'status'    => false,
                'message'   => 'Invalid project.'
            );
        }

        response_json($return_data);
    }

    public function savecategoryitem()
    {
        if (validate('save_project_category_item') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $psData = $this->mgovdb->getRowObject('OrganizationProjectServices', get_post('ProjectServiceID'));
            if ($psData) {

                $saveData = array(
                    'Name'              => get_post('Name'),
                    'Description'       => get_post('Description'),
                    'Quantity'          => get_post('Quantity'),
                    'Allocation'        => get_post('Allocation'),
                    'LastUpdate'        => date('Y-m-d H:i:s')
                );

                $itemData = $this->mgovdb->getRowObject('OrganizationProjectServiceItems', get_post('id'));
                if ($itemData) {
                    $saveData['id'] = $itemData->id;
                } else {
                    $saveData['ProjectID']      = $psData->ProjectID;
                    $saveData['ServiceID']      = $psData->ServiceID;
                    $saveData['ProjectServiceID'] = $psData->id;
                }

                if (($ID = $this->mgovdb->saveData('OrganizationProjectServiceItems', $saveData))) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Category item has been saved.',
                        'id'        => $ID
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Saving category item failed. Please try again later.'
                    );
                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Project category not found.'
                );
            }
        }

        response_json($return_data);
    }

    public function deletecategoryitem($id)
    {
        $itemData = $this->mgovdb->getRowObject('OrganizationProjectServiceItems', $id);
        if ($itemData) {
            $this->mgovdb->deleteData('OrganizationProjectServiceItems', $itemData->id);
        }

        response_json(array(
            'status'    => true,
            'message'   => 'Item has been deleted.'
        ));
    }

}