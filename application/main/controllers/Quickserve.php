<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Quickserve extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();
    }

    public function index()
    {
        
        $viewData = array(
            'pageTitle'     => 'QuickServe',
            'accountInfo'   => user_account_details(),
            'fullwidth'     => true,
            'nosidebar'     => true,
            'jsModules'     => array(
                'quickserve'
            )
        );

        $where = array(
            'sfo.AccountID = ' . current_user()
        );

        if (get_post('mabuhayID')) {
            $where[] = 'uai.MabuhayID = "' .  get_post('mabuhayID') . '"';
        }
        if (get_post('applicationCode')) {
            $where[] = 'sa.Code = "' .  get_post('applicationCode') . '"';
        }
        if (get_post('status') !== '' && in_array(get_post('status'), array(0, 1, 2))) {
            $where[] = 'saf.Status = "' .  get_post('status') . '"';
        }
        if (get_post('date')) {
            $date = date('Y-m-d', strtotime(get_post('date')));
            $where[] = '(saf.DateAdded BETWEEN "'.$date.' 00:00:00" AND "'.$date.' 23:59:59")';
        }

        $items = $this->mgovdb->getAssignedFunction($where);

        foreach ($items as &$item) {
            $item['applicationStatus']  = lookup('service_application_status', $item['saStatus']);
            $item['functionStatus']     = lookup('service_application_status', $item['safStatus']);

            $item['Barangay']           = lookup_row('UtilLocBrgy', $item['BarangayID'], 'brgyCode')->brgyDesc;

            // completed function
            if ($item['safStatus'] >= 2) {
                $item['duration']           = time_ago($item['StartedTime'], $item['EndedTime']);
            } else {
                $item['duration']           = time_ago($item['StartedTime']);    
            }
            
            $item['reqProgress']        = $item['RequirementProcessedCount'] . '/' . $item['RequirementCount'];
            $item['progress']           = round(($item['FunctionProcessedCount'] / $item['FunctionCount']) * 100);
            $item['documentName']       = ($item['documentName'] ? $item['documentName'] : 'Main Service');
        }

        // print_data($items);

        $viewData['items'] = $items;

        view('main/quickserve', $viewData, 'templates/mgov');
    }


    /**
    * get details
    * ajax
    */
    public function details($applicationCode)
    {
        $applicationData = $this->mgovdb->getRowObject('Service_Applications', $applicationCode, 'Code');
        if ($applicationData) {

            $serviceData        = $this->mgovdb->getRowObject('Service_Services', $applicationData->ServiceID, 'id');
            $requirementsData   = $this->mgovdb->getRecords('Service_Application_Requirements', array('ApplicationID' => $applicationData->id));
            $serviceData        = prepare_service_data($serviceData, true);
            $userData           = user_account_details($applicationData->ApplicantID, 'id', false);
            // print_r($applicationData);
            // print_r($serviceData);
            // print_r($requirementsData);
            // print_r($userData);
            $otherFields  = array();
            $requirements = array();

            $ExtraFields   = json_decode($applicationData->ExtraFields, true);
            $UploadedFiles = json_decode($applicationData->UploadedFiles, true);

            foreach ($serviceData['ExtraFields'] as $item) {
                if ($item['FieldType'] == 3) {
                    $value = (isset($UploadedFiles[$item['FieldID']]) ? $UploadedFiles[$item['FieldID']] : '');
                    $value = public_url() . 'assets/uploads/' . uploads_filename($value);
                } else {
                    $value = (isset($ExtraFields[$item['FieldID']]) ? $ExtraFields[$item['FieldID']] : '');
                }
                $otherFields[] = array(
                    'id'    => $item['FieldID'],
                    'label' => $item['FieldLabel'],
                    'type'  => $item['FieldType'],
                    'value' => $value
                );
            }

            $reqCodes = array();
            foreach ($requirementsData as $item) {
                $reqCodes[$item['RequirementID']] = $item;
            }

            foreach ($serviceData['Requirements'] as $item) {
                $requirements[] = array(
                    'name'  => $item->Name,
                    'logo'  => $item->Logo,
                    'desc'  => $item->Description,
                    'code'  => (isset($reqCodes[$item->id]) ? $reqCodes[$item->id]['Code'] : false),
                    'status'=> (isset($reqCodes[$item->id]) ? lookup('service_application_status', $reqCodes[$item->id]['Status']) : false),
                    'update'=> (isset($reqCodes[$item->id]) ? $reqCodes[$item->id]['LastUpdate'] : false),
                );
            }

            $userData->Fullname   = user_full_name($userData, false);
            $userData->Civil      = lookup('marital_status', $userData->MaritalStatusID);
            $userData->Gender     = lookup('gender', $userData->GenderID);
            $userData->Education  = lookup('education', $userData->EducationalAttainmentID);
            $userData->Livelihood = lookup('livelihood', $userData->LivelihoodStatusID);
            $userData->Address    = ucwords(strtolower(user_full_address($userData, true)));

            $return_data = array(
                'status'    => true,
                'data'      => array(
                    'service'       => $serviceData,
                    'applicant'     => $userData,
                    'application'   => array(
                        'otherFields'       => $otherFields,
                        'requirements'      => $requirements
                    )
                )
            );
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid application data.'
            );
        }
        response_json($return_data);
    }

    /**
    * approve application function
    * complete application if all functions are processed
    * generate citizen document if requirement function is complete
    */
    public function approve()
    {
        $safID = get_post('safID');
        if ($safID) {
            $safData = $this->mgovdb->getRowObject('Service_Application_Functions', $safID, 'id');

            if ($safData) {

                $applicationData = $this->mgovdb->getRowObject('Service_Applications', $safData->ApplicationID, 'id');
                if ($applicationData) {

                    $processOrder = json_decode($applicationData->ProcessOrder, true);

                    $continue_main_application_update = false;
                    $continue_requirement_update = true;

                    // print_r($safData);
                    // print_r($processOrder);

                    $safUpdate = array(
                        'id'            => $safData->id,
                        'Status'        => 2,
                        'Remarks'       => get_post('Remarks'),
                        'OfficerID'     => current_user(),
                        'DateCompleted' => date('Y-m-d H:i:s'),
                        'LastUpdate'    => date('Y-m-d H:i:s')
                    );

                    $this->db->trans_begin();

                    if ($this->mgovdb->saveData('Service_Application_Functions', $safUpdate)) {

                        // if main, update application process count
                        // if requirement, update application requirement process count, and application process count
                        // if requirement process count is completed, save the document (user document)
                        // if all process is completed, complete the application transaction

                        $completedRequirementCount = $applicationData->RequirementProcessedCount;
                        $applicationUpdate = array(
                            'id'            => $applicationData->id,
                            'UpdatedBy'     => current_user(),
                            'LastUpdate'    => date('Y-m-d H:i:s'),
                            'Status'        => 1
                        );

                        // if function is for requirement
                        if ($safData->RequirementID) {

                            // get application requirement
                            $applicationRequirementData = $this->mgovdb->getRowObjectWhere('Service_Application_Requirements', array(
                                'ApplicationID' => $safData->ApplicationID,
                                'RequirementID' => $safData->RequirementID
                            ));

                            if ($applicationRequirementData) {

                                $requirementProcessedFunctionCount = $this->mgovdb->getCount('Service_Application_Functions', array(
                                                        'ApplicationID' => $safData->ApplicationID,
                                                        'RequirementID' => $safData->RequirementID,
                                                        'Status'        => 2
                                                    ));

                                $applicationRequirementUpdate = array(
                                    'id'            => $applicationRequirementData->id,
                                    'UpdatedBy'     => current_user(),
                                    'LastUpdate'    => date('Y-m-d H:i:s'),
                                    'FunctionProcessedCount'    => $requirementProcessedFunctionCount,
                                );

                                if ($requirementProcessedFunctionCount == $applicationRequirementData->FunctionCount) {

                                    // save the actual the citizen document
                                    // but check if there's no same active document, just update if found
                                    $currentDocumentData = $this->mgovdb->getRowObjectWhere('UserDocuments', array(
                                        'AccountID'     => $applicationRequirementData->ApplicantID,
                                        'DocumentID'    => $applicationRequirementData->DocumentID,
                                        'Status'        => 1,
                                        'ExpirationDate > '=> date('Y-m-d')
                                    ));

                                    $citizenDocumentData = array(
                                        'Code'              => $applicationRequirementData->Code,
                                        'ApplicationID'     => $applicationRequirementData->ApplicationID,
                                        'DocumentContent'   => $applicationRequirementData->DocumentDraft,
                                        'ExpirationDate'    => compute_expiration_date(lookup_db('Doc_Templates', 'Validity', $applicationRequirementData->DocumentID)),
                                        'Status'            => 1,
                                        'CreatedBy'         => current_user(),
                                        'LastUpdate'        => date('Y-m-d H:i:s')
                                    );

                                    if ($currentDocumentData) {
                                        $citizenDocumentData['id'] = $currentDocumentData->id;
                                    } else {
                                        $citizenDocumentData['AccountID'] = $applicationRequirementData->ApplicantID;
                                        $citizenDocumentData['DocumentID']= $applicationRequirementData->DocumentID;
                                        $citizenDocumentData['DateAdded'] = date('Y-m-d H:i:s');
                                    }

                                    if (!$this->mgovdb->saveData('UserDocuments', $citizenDocumentData)) {
                                        $continue_requirement_update = false;
                                        $return_data = array(
                                            'status'    => false,
                                            'message'   => 'Creating document record failed.'
                                        );
                                        $this->db->trans_rollback();
                                    }

                                    $applicationRequirementUpdate['Status']         = 2;
                                    $applicationRequirementUpdate['DateCompleted']  = date('Y-m-d H:i:s');
                                    $applicationRequirementUpdate['ApproverID']     = current_user();

                                } else {
                                    $applicationRequirementUpdate['Status'] = 1;
                                }

                                if ($continue_requirement_update) {

                                    if ($this->mgovdb->saveData('Service_Application_Requirements', $applicationRequirementUpdate)) {

                                        // count completed requirements
                                        $completedRequirementCount = $this->mgovdb->getCount('Service_Application_Requirements', array(
                                                            'ApplicationID' => $safData->ApplicationID,
                                                            'Status'        => 2,
                                                        ));

                                        $applicationUpdate['RequirementProcessedCount'] = $completedRequirementCount;

                                        $continue_main_application_update = true;

                                    } else {
                                        $return_data = array(
                                            'status'    => false,
                                            'message'   => 'Updating requirement process failed.'
                                        );
                                        $this->db->trans_rollback();
                                    }

                                }

                            } else {
                                $return_data = array(
                                    'status'    => false,
                                    'message'   => 'Invalid process requirement data.'
                                );
                            }
                            
                        } else {
                            $continue_main_application_update = true;
                        }

                        if ($continue_main_application_update) {

                            // get all completed functions
                            $processedCount = $this->mgovdb->getCount('Service_Application_Functions', array(
                                                    'ApplicationID' => $safData->ApplicationID,
                                                    'Status'        => 2
                                                ));

                            $applicationUpdate['FunctionProcessedCount'] = $processedCount;

                            // check if all functions and requirements are completed
                            if ($processedCount == $applicationData->FunctionCount && $completedRequirementCount == $applicationData->RequirementCount) {

                                $applicationUpdate['DateCompleted'] = date('Y-m-d H:i:s');
                                $applicationUpdate['ApproverID']    = current_user();
                                $applicationUpdate['Status']        = 2;

                                if ($this->mgovdb->saveData('Service_Applications', $applicationUpdate)) {
                                    $return_data = array(
                                        'status'    => true,
                                        'message'   => 'Process has been completed. This is also the last step for application completion.'
                                    );
                                    $this->db->trans_commit();
                                } else {
                                    $return_data = array(
                                        'status'    => false,
                                        'message'   => 'Updating application failed.'
                                    );
                                    $this->db->trans_rollback();
                                }

                            } else {
                                // if not done, update application and create new entry for the next function to process
                                if ($this->mgovdb->saveData('Service_Applications', $applicationUpdate)) {

                                    // get the next function process
                                    $next = false;
                                    foreach ($processOrder as $k => &$v) {
                                        if ($safData->FunctionID == $v['id']) {
                                            if (isset($processOrder[$k+1])) {
                                                $next = $processOrder[$k+1];
                                            }
                                            break;
                                        }
                                    }

                                    if ($next) {

                                        $nextApplicationFunctionData = array(
                                            'ServiceID'     => $safData->ServiceID,
                                            'ApplicationID' => $safData->ApplicationID,
                                            'ApplicantID'   => $safData->ApplicantID,
                                            'FunctionID'    => $next['id'],
                                            'DateAdded'     => date('Y-m-d H:i:s')
                                        );
                                        if ($next['For'] != 'Main') {
                                            $nextApplicationFunctionData['RequirementID'] = $next['For'];
                                        }

                                        if ($this->mgovdb->saveData('Service_Application_Functions', $nextApplicationFunctionData)) {
                                            $return_data = array(
                                                'status'    => true,
                                                'message'   => 'Process has been completed.'
                                            );
                                            $this->db->trans_commit();
                                        } else {
                                            $return_data = array(
                                                'status'    => false,
                                                'message'   => 'Cannot save the next task to process. Try again later.'
                                            );
                                            $this->db->trans_rollback();
                                        }

                                    } else {
                                        $return_data = array(
                                            'status'    => false,
                                            'message'   => 'Cannot find next task to process. Try again later.'
                                        );
                                        $this->db->trans_rollback();
                                    }

                                } else {
                                    $return_data = array(
                                        'status'    => false,
                                        'message'   => 'Updating application failed.'
                                    );
                                    $this->db->trans_rollback();
                                }
                            }

                        }

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Updating process failed.'
                        );
                        $this->db->trans_rollback();
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Invalid application data.'
                    );
                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid application process.'
                );
            }

        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid request.'
            );
        }

        response_json($return_data);
    }

    /**
    * decline process
    * decline application requirement if process is for requirement
    * cancel application requirements that is not yet started
    * decline application
    */
    public function decline()
    {
        $safID = get_post('safID');
        if ($safID) {

            $safData = $this->mgovdb->getRowObject('Service_Application_Functions', $safID, 'id');

            if ($safData) {

                $applicationData = $this->mgovdb->getRowObject('Service_Applications', $safData->ApplicationID, 'id');
                if ($applicationData) {

                    $selectedStatus = (in_array(get_post('Status'), array(3, 4)) ? ((int)get_post('Status')) : 3);

                    $continue_main_application_update = false;

                    $safUpdate = array(
                        'id'            => $safData->id,
                        'Status'        => $selectedStatus,
                        'Remarks'       => get_post('Remarks'),
                        'OfficerID'     => current_user(),
                        'DateCompleted' => date('Y-m-d H:i:s'),
                        'LastUpdate'    => date('Y-m-d H:i:s')
                    );

                    $this->db->trans_begin();

                    if ($this->mgovdb->saveData('Service_Application_Functions', $safUpdate)) {

                        $applicationUpdate = array(
                            'id'            => $applicationData->id,
                            'UpdatedBy'     => current_user(),
                            'LastUpdate'    => date('Y-m-d H:i:s'),
                            'DateCompleted' => date('Y-m-d H:i:s'),
                            'Status'        => $selectedStatus
                        );

                        // if function is for requirement
                        if ($safData->RequirementID) {

                            // get application requirement
                            $applicationRequirementData = $this->mgovdb->getRowObjectWhere('Service_Application_Requirements', array(
                                'ApplicationID' => $safData->ApplicationID,
                                'RequirementID' => $safData->RequirementID
                            ));

                            if ($applicationRequirementData) {

                                $applicationRequirementUpdate = array(
                                    'id'            => $applicationRequirementData->id,
                                    'UpdatedBy'     => current_user(),
                                    'LastUpdate'    => date('Y-m-d H:i:s'),
                                    'DateCompleted' => date('Y-m-d H:i:s'),
                                    'Status'        => $selectedStatus
                                );

                                if ($this->mgovdb->saveData('Service_Application_Requirements', $applicationRequirementUpdate)) {
                                    $continue_main_application_update = true;
                                } else {
                                    $return_data = array(
                                        'status'    => false,
                                        'message'   => 'Updating requirement process failed.'
                                    );
                                    $this->db->trans_rollback();
                                }

                            } else {
                                $return_data = array(
                                    'status'    => false,
                                    'message'   => 'Invalid process requirement data.'
                                );
                            }

                        } else {
                            $continue_main_application_update = true;
                        }

                        if ($continue_main_application_update) {
                            if ($this->mgovdb->saveData('Service_Applications', $applicationUpdate)) {
                                $return_data = array(
                                    'status'    => true,
                                    'message'   => 'Service application has been ' . strtolower(lookup('service_application_status', $selectedStatus))
                                );
                                $this->db->trans_commit();
                            } else {
                                $return_data = array(
                                    'status'    => false,
                                    'message'   => 'Updating application failed.'
                                );
                                $this->db->trans_rollback();
                            }
                        }

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Updating process failed.'
                        );
                        $this->db->trans_rollback();
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Invalid application data.'
                    );
                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid application process.'
                );
            }

        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid request.'
            );
        }

        response_json($return_data);

    }

}
