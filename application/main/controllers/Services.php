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
            'pageTitle'     => 'Services',
            'accountInfo'   => user_account_details($this->session->userdata('identifier'))
        );        

        // echo '<pre>';print_r($viewData);echo '</pre>';
        view('main/services', $viewData, 'templates/mgov');
        // view('main/blank', $viewData, 'templates/mgov');
    }

    public function view($code)
    {
        $service = $this->mgovdb->getRowObject('Service_Services', $code, 'Code');
        if ($service) {
            $serviceData = prepare_service_data($service, true);

            // check if there's an existing application
            // check if no active application
            $pending_application = lookup_all('Service_Applications', array(
                'ServiceID'     => $service->id,
                'ApplicantID'   => current_user(),
                'Status'        => 0
            ));

            if (count($pending_application)) {
                $serviceData['hasPending'] = 1;

                $pending_application = $pending_application[0];
                // get requirement status
                $application_requirements = lookup_all('Service_Application_Requirements', array(
                    'ServiceID'     => $service->id,
                    'ApplicationID' => $pending_application['id'],
                    'ApplicantID'   => current_user()
                ), 'id', false);
                foreach ($application_requirements as $application_requirement)
                {
                    foreach ($serviceData['Requirements'] as &$service_requirement) {
                        if ($application_requirement['RequirementID'] == $service_requirement->id) {
                            $service_requirement->status        = lookup('service_application_status', $application_requirement['Status']);
                            $service_requirement->last_updated  = date('Y-m-d', strtotime(($application_requirement['LastUpdate'] ? $application_requirement['LastUpdate'] : $application_requirement['DateApplied'])));
                            $service_requirement->app_req       = $application_requirement['Code'];
                            continue 1;
                        }
                    }
                }
            } else {
                $serviceData['hasPending'] = 0;
            }

            $serviceData['serviceProvided'] = get_department_service_provided($serviceData['DepartmentID']);
            $serviceData['serviceProvider'] = get_service_providers($serviceData['id']);

            $return_data = array(
                'status'    => true,
                'data'      => $serviceData
            );
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid service code.'
            );
        }

        response_json($return_data);
    }

    public function save_application()
    {   
        $sCode    = get_post('ServiceCode');
        $service = $this->mgovdb->getRowObject('Service_Services', $sCode, 'Code');
        if ($service) {

            // check if no active application
            $pending_application = lookup_all('Service_Applications', array(
                'ServiceID'     => $service->id,
                'ApplicantID'   => current_user(),
                'Status'        => 0
            ));

            if (count($pending_application) == 0 || in_array($service->ServiceType, lookup('report_service_type'))) {

                // load upload library for image upload
                $this->load->library('upload');

                $serviceData = prepare_service_data($service, true);
                $validation = $this->validateApplicationFormFields($serviceData);
                // print_r($validation);
                if ($validation['status'] === true) {

                    // prepare needed data
                    $applicationCode    = microsecID();
                    $userData           = user_account_details(current_user());
                    $serviceProcessData = get_service_process_order($serviceData['id']);

                    $serviceApplicationData = array(
                        'ServiceID'     => $serviceData['id'],
                        'ServiceCode'   => $serviceData['Code'],
                        'Code'          => $applicationCode,
                        'ApplicantID'   => current_user(),
                        'ExtraFields'   => json_encode($validation['fields']),
                        'UploadedFiles' => json_encode($validation['uploads']),
                        'ProcessOrder'  => json_encode($serviceProcessData['orderedProcess']),
                        'RequirementCount'  => $serviceProcessData['requirementCount'],
                        'FunctionCount'     => $serviceProcessData['functionCount'],
                        'LocationScopeID'   => $serviceData['LocationScopeID'],
                        'LocationCode'      => $serviceData['LocationCode'],

                        // applicant location info,  for reporting
                        'RegionalID'        => $userData->RegionalID,
                        'ProvincialID'      => $userData->ProvincialID,
                        'MunicipalityCityID'=> $userData->MunicipalityCityID,
                        'BarangayID'        => $userData->BarangayID,

                        'DepartmentID'      => $serviceData['DepartmentID'],
                        'SubDepartmentID'   => $serviceData['SubDepartmentID'],
                        'LastUpdate'        => date('Y-m-d H:i:s'),
                        'DateApplied'       => date('Y-m-d H:i:s')
                    );

                    $applicationRequirements = array();
                    foreach ($serviceData['Requirements'] as $requirement) {
                        $applicationRequirements[] = array(
                            'Code'          => md5($serviceData['id'] . current_user() . $requirement->id . microsecID()),
                            'ServiceID'     => $serviceData['id'],
                            'ApplicantID'   => current_user(),
                            'RequirementID' => $requirement->id,
                            'DocumentID'    => $requirement->DocumentID,
                            'FunctionCount' => $serviceProcessData['requirementFunctionCount'][$requirement->id],
                            'DocumentDraft' => generate_document_from_template($requirement->DocumentID, array(
                                                    'userData'      => $userData,
                                                    'extraFields'   => $validation['fields'],
                                                    'uploadedFiles' => $validation['uploads']
                                                )),
                            'LastUpdate'    => date('Y-m-d H:i:s'),
                            'DateApplied'   => date('Y-m-d H:i:s')
                        );
                    }

                    // for now get the first function,
                    // later, check existing first, start only on not existing requirement function
                    $firstFunction = $serviceProcessData['orderedProcess'][0];
                    $applicationFunctionData = array(
                        'ServiceID'     => $serviceData['id'],
                        'ApplicantID'   => current_user(),
                        'FunctionID'    => $firstFunction['id'],
                        'DateAdded'     => date('Y-m-d H:i:s')
                    );

                    if ($firstFunction['For'] != 'Main') {
                        $applicationFunctionData['RequirementID'] = $firstFunction['For'];
                    }

                    $saveData = array(
                        'application'   => $serviceApplicationData,
                        'requirements'  => $applicationRequirements,
                        'function'      => $applicationFunctionData
                    );

                    if ($this->servicesdb->addNewApplication($saveData)) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Service application has been saved.'
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Saving service application failed. Please try again.'
                        );
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Some fields have errors.',
                        'fields'    => $validation['errors']
                    );
                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'You already have a pending application for this service.'
                );
            }

        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid service code.'
            );
        }

        response_json($return_data);

        // print_r($_REQUEST);
        // print_r($_FILES);
        // response_json(array_merge($_REQUEST, $_FILES));
    }


    /**
    * service application extra fields validation
    */
    private function validateApplicationFormFields($serviceData)
    {

        $errors         = array();
        $validFields    = array();
        $uploaded       = array();
        $uploadedfiles  = array();

        $extraFields        = get_post('ExtraField');
        $requirementFields  = get_post('RequirementField');

        // validate service extra fields
        foreach ($serviceData['ExtraFields'] as $item) {
            if ($item['FieldType'] == 3) {
                // file
                $imageUpload = $this->validateAndUploadImage($item['FieldID'], $item['FieldLabel']);
                if ($imageUpload['status']) {
                    $uploaded[$item['FieldID']] = $imageUpload['data']['file_name'];
                    $uploadedfiles[$item['FieldID']] = $imageUpload['data']['full_path'];
                } else {
                    $errors[$item['FieldID']] = $imageUpload['error'];
                }
            } else {
                if (!isset($extraFields[$item['FieldID']]) || trim($extraFields[$item['FieldID']]) == '') {
                    $errors[$item['FieldID']] = ucfirst(strtolower($item['FieldLabel'])) . ' field is required.';
                } else {
                    $validFields[$item['FieldID']] = trim($extraFields[$item['FieldID']]);
                }
            }
        }

        // validate requirements fields
        foreach ($serviceData['Requirements'] as $requirement) {
            $requirement = (array) $requirement;
            if (isset($requirement['extraFields']) && is_array($requirement['extraFields'])) {
                foreach ($requirement['extraFields'] as $fieldID => $item) {
                    if ($item['type'] == 3) {
                        // file
                        $imageUpload = $this->validateAndUploadImage($fieldID, $item['label']);
                        if ($imageUpload['status']) {
                        $uploaded[$fieldID] = $imageUpload['data']['file_name'];
                        $uploadedfiles[$fieldID] = $imageUpload['data']['full_path'];
                    } else {
                        $errors[$fieldID] = $imageUpload['error'];
                    }
                    } else {
                        if (!isset($requirementFields[$requirement['id']][$fieldID]) || trim($requirementFields[$requirement['id']][$fieldID]) == '') {
                            $errors[$fieldID] = ucfirst(strtolower($item['label'])) . ' field is required.';
                        } else {
                            $validFields[$fieldID] = trim($requirementFields[$requirement['id']][$fieldID]);
                        }
                    }
                }
            }
        }

        if (count($errors)) {
            // delete success upload if there's any
            foreach ($uploadedfiles as $uploadedfile) {
                @unlink($uploadedfile);
            }
            return array(
                'status'    => false,
                'errors'    => $errors
            );
        } else {
            return array(
                'status'    => true,
                'fields'    => $validFields,
                'uploads'   => $uploaded      
            );
        }

    }

    private function validateAndUploadImage($fieldID, $fieldLabel)
    {
        $Images  = isset($_FILES['Image']) ? $_FILES['Image'] : array();
        if (isset($Images['name'][$fieldID]) && !empty($Images['name'][$fieldID])) {

            $randomfilename = md5($fieldID . microsecID());

            $upload_options = array(
                'upload_path'   => UPLOADS_DIRECTORY,
                'allowed_types' => 'gif|jpg|png',
                'max_size'      => '2000', // 2mb
                'overwrite'     => true,
                'file_name'     => $randomfilename
            );

            $_FILES['imagefile']['name']    = $Images['name'][$fieldID];
            $_FILES['imagefile']['type']    = $Images['type'][$fieldID];
            $_FILES['imagefile']['tmp_name']= $Images['tmp_name'][$fieldID];
            $_FILES['imagefile']['error']   = $Images['error'][$fieldID];
            $_FILES['imagefile']['size']    = $Images['size'][$fieldID];    

            $this->upload->initialize($upload_options);
            if ($this->upload->do_upload('imagefile')) {
                return array(
                    'status'    => true,
                    'data'      => $this->upload->data()
                );
            } else {
                return array(
                    'status'    => false,
                    'error'   => $this->upload->display_errors('','')
                );
            }

        } else {
            return array(
                'status'    => false,
                'error'   => ucfirst(strtolower($fieldLabel)) . ' image file is required.'
            );
        }
    }

}