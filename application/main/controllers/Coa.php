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

    private function getProjects($projectCode = null)
    {
        $projectFilter = array(
            'OrganizationID'    => $this->user->OrganizationID
        );

        if ($projectCode) {
            $projectFilter['Code'] = $projectCode;
        }

        $projects = array();
        $items    = lookup_all('OrganizationProjects', $projectFilter, 'Name');
        foreach ($items as $item) {
            $projects[$item['Code']] = array(
                    'id'    => $item['id'],
                    'Code'  => $item['Code'],
                    'Name'  => $item['Name'],
                    'Description'   => $item['Description'],
                    'Scope'         => $item['LocationScopeID'],
                    'TargetDate'    => $item['TargetDate'],
                    'Categories'    => count($this->mgovdb->getRecords('OrganizationProjectServices', array('ProjectID' => $item['id'], 'Status' => 1))),
                    'Allocations'   => $this->db->select_sum('Allocation')->select_sum('Quantity')->select_sum('1', 'Count')
                                                    ->from('OrganizationProjectServiceItems psi')
                                                    ->join('OrganizationProjectServices ps', 'ps.id = psi.ProjectServiceID')
                                                    ->where('psi.ProjectID', $item['id'])
                                                    ->where('ps.Status', 1)
                                                    ->get()
                                                    ->row_array()
            ); 
        }

        return $projects;

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

        $projects = $this->getProjects();
        
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
                    'TargetDate'        => get_post('TargetDate'),
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
                    'Allocations'   => $this->db->select_sum('Allocation')->select_sum('Quantity')->select_sum('1', 'Count')
                                                    ->from('OrganizationProjectServiceItems psi')
                                                    ->join('OrganizationProjectServices ps', 'ps.id = psi.ProjectServiceID')
                                                    ->where('psi.ProjectID', $record->id)
                                                    ->where('ps.Status', 1)
                                                    ->get()
                                                    ->row_array()
                );

                $expensesCategory = $this->db->where('Name', 'Expenditure')
                                                ->where('OrganizationID',$this->user->OrganizationID)
                                                ->get('OrganizationCategories')->row();
                $viewData['availableServices'] = $this->mgovdb->getProjectServices(array(
                    'userID'        => current_user(),
                    'organization'  => $this->user->OrganizationID,
                    'category'      => ($expensesCategory ? $expensesCategory->id : 0)
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




    /**
    * PROCUREMENT
    */

    public function procurement($projectCode = false)
    {
        $viewData = array(
                    'pageTitle'     => 'Procurement',
                    'accountInfo'   => user_account_details(),
                    'jsModules'         => array(
                        'coa',
                    ),
                );

        $viewData['projectSelected'] = false;

        if ($projectCode) {
            $project = $this->mgovdb->getRowObjectWhere('OrganizationProjects', array('OrganizationID'    => $this->user->OrganizationID, 'Code' => $projectCode));
            if ($project) {

                $viewData['projectSelected'] = true;
                $viewData['projectData']    = array(
                    'id'    => $project->id,
                    'Code'  => $project->Code,
                    'Name'  => $project->Name,
                    'TargetDate'    => $project->TargetDate,
                    'Description'   => $project->Description,
                    'Allocations'   => $this->db->select_sum('Allocation')->select_sum('Quantity')->select_sum('1', 'Count')
                                                    ->from('OrganizationProjectServiceItems psi')
                                                    ->join('OrganizationProjectServices ps', 'ps.id = psi.ProjectServiceID')
                                                    ->where('psi.ProjectID', $project->id)
                                                    ->where('ps.Status', 1)
                                                    ->get()
                                                    ->row_array()
                );

                $items = $this->db->select('i.id,i.Name,i.Description,i.Quantity,i.Allocation,i.ServiceID')
                                    ->from('OrganizationProjectServiceItems i')
                                    ->join('OrganizationProjectServices ps', 'ps.id = i.ProjectServiceID')
                                    ->where('ps.Status', 1)
                                    ->where('i.ProjectID', $project->id)
                                    ->get()
                                    ->result_array();

                foreach ($items as &$item) {
                    // get selected supplier
                    // if not selected, find from posible item match. rank by name match and lower price
                    $item['suppliers'] = array();
                    $match_suppliers   = lookup_match_suppliers($item['Name']);
                    $suppliers = array();
                    // suppliers
                    for ($i = 1; $i <= 3; $i++) {
                        $item['suppliers'][$i] = false;
                        $supplier = $this->mgovdb->getRowObjectWhere('OrganizationPSI_Suppliers', array(
                                        'ProjectID' => $project->id,
                                        'Rank'      => $i,
                                        'ItemID'    => $item['id']
                                    ));

                        if ($supplier) {
                            if ($supplier->SupplierID) {
                                // check if item still exists
                                if ($this->mgovdb->getRowObject('BusinessItems', $supplier->SupplierItemID)) {
                                    $suppliers[] = $supplier->SupplierID;
                                    $item['suppliers'][$i] = (array) $supplier;
                                }
                            }
                        } else {
                            // get supplier from posible match
                            foreach($match_suppliers as $k => $match) {
                                if (!in_array($match['BusinessID'], $suppliers)) {
                                    // auto add
                                    $saveData = array(
                                        'ProjectID'     => $project->id,
                                        'ItemID'        => $item['id'],
                                        'SupplierID'    => $match['BusinessID'],
                                        'SupplierItemID'=> $match['id'],
                                        'Rank'          => $i,
                                        'Remarks'       => 'Automatically assigned',
                                        'LastUpdate'    => date('Y-m-d H:i:s')
                                    );
                                    $saveData['id'] = $this->mgovdb->saveData('OrganizationPSI_Suppliers', $saveData);
                                    $item['suppliers'][$i]   = $saveData;
                                    unset($match_suppliers[$k]);
                                    break;
                                } else {
                                    unset($match_suppliers[$k]);
                                }
                            }
                        }

                        if ($item['suppliers'][$i]) {
                            $item['suppliers'][$i]['SupplierInfo'] = lookup_business_data($item['suppliers'][$i]['SupplierID']);
                            $item['suppliers'][$i]['SupplierItemInfo'] = (array) $this->mgovdb->getRowObject('BusinessItems', $item['suppliers'][$i]['SupplierItemID']);
                        }
                    }

                }

                $viewData['projectItems'] = $items;

                // print_data($viewData);

            } else {
                redirect(site_url('coa/procurement'));
            }
        } else {
            $projects = $this->getProjects();
            $viewData['projects'] = $projects;
        }

        view('main/coa/procurement', $viewData, 'templates/mgov');
    }

    public function findsupplieritems()
    {
        $match = lookup_match_suppliers(get_post('matcher'), true);
        if (count($match)) {

            $match   = array_slice($match, 0, 100);

            $forItem = $this->mgovdb->getRowObject('OrganizationProjectServiceItems', get_post('item'));
            $hits    = array();
            $nonhits = array();
            
            foreach ($match as &$m) {
                $m['savings']      = price_savings($forItem->Allocation, ($forItem->Quantity * $m['Price']));
                $m['supplierInfo'] = lookup_business_data($m['BusinessID']);
                $m['imageurl']     = public_url('assets/logo/') . logo_filename($m['Image']);

                // group by hit and not hit (base on item name)
                if (wordMatch(get_post('matcher'), $m['Name'])) {
                    $hits[] = $m;
                } else {
                    $nonhits[] = $m;
                }
            }

            response_json(array(
                'status'    => true,
                'data'      => array(
                    'hits'      => $hits,
                    'others'    => $nonhits
                )
            ));
        } else {
            response_json(array(
                'status'    => false,
                'data'      => 'No item found'
            ));
        }
    }

    public function setitemsupplier()
    {
        // check if already assign on other supplier option
        $sql = "SELECT * FROM OrganizationPSI_Suppliers
                WHERE ProjectID = ?
                AND Rank != ?
                AND ItemID = ?
                AND SupplierID = ?";
        $results = $this->db->query($sql, array(
                        get_post('project'),
                        get_post('rank'),
                        get_post('item'),
                        get_post('supplierid')
                    ))->result_array();
        if (count($results)) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Selected supplier is already assigned as other option.'
            );
        } else {

            // get existing supplier assigned if set
            $assigned = $this->mgovdb->getRowObjectWhere('OrganizationPSI_Suppliers', array(
                            'ProjectID' => get_post('project'),
                            'ItemID'    => get_post('item'),
                            'Rank'      => get_post('rank')
                        ));

            if ($assigned) {
                $saveData = array(
                    'id'            => $assigned->id,
                    'SupplierID'    => get_post('supplierid'),
                    'SupplierItemID'=> get_post('selecteditem'),
                    'Remarks'       => 'Manually assigned',
                    'LastUpdate'    => date('Y-m-d H:i:s')
                );
            } else {
                $saveData = array(
                    'ProjectID'     => get_post('project'),
                    'ItemID'        => get_post('item'),
                    'Rank'          => get_post('rank'),
                    'Remarks'       => 'Manually assigned',
                    'SupplierID'    => get_post('supplierid'),
                    'SupplierItemID'=> get_post('selecteditem'),
                    'LastUpdate'    => date('Y-m-d H:i:s')
                );
            }

            if ($this->mgovdb->saveData('OrganizationPSI_Suppliers', $saveData)) {
                $return_data = array(
                    'status'    => true,
                    'message'   => 'Supplier has been selected.'
                );
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Saving selected supplier failed.'
                );
            }
        }

        response_json($return_data);
    }

    public function removeitemsupplier()
    {
        $assigned = $this->mgovdb->getRowObject('OrganizationPSI_Suppliers', get_post('id'));
        if ($assigned) {
            $saveData = array(
                'id'    => $assigned->id,
                'SupplierID'        => null,
                'SupplierItemID'    => null,
                'Remarks'           => 'Removed',
                'LastUpdate'        => date('Y-m-d H:i:s')
            );

            if ($this->mgovdb->saveData('OrganizationPSI_Suppliers', $saveData)) {
                $return_data = array(
                    'status'    => true,
                    'message'   => 'Supplier has been removed.'
                );
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Removing selected supplier failed.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid supplier.'
            );
        }

        response_json($return_data);
    }

    public function test()
    {
        $r = wordMatch(get_post('i'), get_post('a'));
        // print_data($r);
        var_dump($r);
    }


    public function procurementreport($projectCode = null)
    {
        $viewData = array(
                    'pageTitle'     => 'Procurement',
                    'accountInfo'   => user_account_details(),
                    'nosidebar'     => true,
                    'shownav'       => true,
                    'jsModules'         => array(
                        'coa',
                    ),
                );

        if ($projectCode) {
            $project = $this->mgovdb->getRowObjectWhere('OrganizationProjects', array('OrganizationID'    => $this->user->OrganizationID, 'Code' => $projectCode));
            if ($project) {

                $userData = $this->mgovdb->getRowObject('UserAccountInformation', $project->CreatorID);
                $address  = lookup_address((array) $userData);

                $items = $this->db->select('i.id,i.Name,i.Description,i.Quantity,i.Allocation,i.ServiceID')
                                    ->from('OrganizationProjectServiceItems i')
                                    ->join('OrganizationProjectServices ps', 'ps.id = i.ProjectServiceID')
                                    ->where('ps.Status', 1)
                                    ->where('i.ProjectID', $project->id)
                                    ->get()
                                    ->result_array();
                $totalSavings = 0;
                $totalAmount  = 0;
                foreach ($items as &$item) {

                    $chosensupplier = false;
                    for ($i = 1; $i <= 3; $i++) {
                        $item['suppliers'][$i] = false;
                        $supplier = $this->mgovdb->getRowObjectWhere('OrganizationPSI_Suppliers', array(
                                        'ProjectID' => $project->id,
                                        'Rank'      => $i,
                                        'ItemID'    => $item['id']
                                    ));

                        if ($supplier && $supplier->SupplierID) {
                            $supplierItem = $this->mgovdb->getRowObject('BusinessItems', $supplier->SupplierItemID);
                            if ($supplierItem) {
                                $suppliers[] = $supplier->SupplierID;
                                $item['suppliers'][$i] = (array) $supplier;
                                $item['suppliers'][$i]['SupplierInfo'] = lookup_business_data($item['suppliers'][$i]['SupplierID']);
                                $item['suppliers'][$i]['SupplierItemInfo'] = (array) $supplierItem;
                            }
                        }

                        if ($item['suppliers'][$i]) {
                            if ($chosensupplier == false) {
                                $chosensupplier = $item['suppliers'][$i];
                            }
                        }
                    }

                    if ($chosensupplier) {
                        $item['savings'] = ($item['Allocation'] - ($chosensupplier['SupplierItemInfo']['Price'] * $item['Quantity']));
                        $item['uprice']  = $chosensupplier['SupplierItemInfo']['Price'] * $item['Quantity'];
                        $totalSavings += $item['savings'];
                        $totalAmount += $item['uprice']; 
                    } else {
                        $item['savings'] = 0;
                        $item['uprice']  = 0;
                    }

                }

                $viewData['projectData']  = $project;
                $viewData['address']      = $address;
                $viewData['items']        = $items;
                $viewData['totalAmount']  = $totalAmount;
                $viewData['totalSavings'] = $totalSavings;
                $viewData['owner']        = array(
                    'FirstName' => $userData->FirstName,
                    'LastName'  => $userData->LastName
                );

                $viewData['pAllocation'] = $this->db->select_sum('Allocation')->select_sum('Quantity')->select_sum('1', 'Count')
                                                    ->from('OrganizationProjectServiceItems psi')
                                                    ->join('OrganizationProjectServices ps', 'ps.id = psi.ProjectServiceID')
                                                    ->where('psi.ProjectID', $project->id)
                                                    ->where('ps.Status', 1)
                                                    ->get()
                                                    ->row_array();

                view('main/coa/procurementreport', $viewData, 'templates/mgov');

            } else {
                redirect(site_url('coa/projects'));
            }
        } else {
            redirect(site_url('coa/projects'));
        }

    }

}