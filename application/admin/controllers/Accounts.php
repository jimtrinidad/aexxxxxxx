<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
* Account management class
* This is different from Account class that handle authentication
*/ 

class Accounts extends CI_Controller
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
            'pageTitle'         => 'Account Manager',
            'pageDescription'   => '',
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'accounts',
                'utils'
            ),
        );

        // print_r($this->session->userdata());
        // print_data($viewData['accountInfo']);

        $page_limit = 50;
        $page_start = (int) $this->uri->segment(3);

        $where = array(
            'deletedAt' => NULL,
            // 'CONCAT(FirstName, " ", LastName) LIKE ' => '%ran%'
        );
        $order = 'StatusID, FirstName';

        // SET SEARCH FILTER
        $filters = array(
            'search_mid',
            'search_name',
            'search_account_status',
            'search_account_level',
            'search_account_city'
        );
        foreach ($filters as $filter) {

            $$filter           = false;

            if (get_post($filter) !== null) {
                $$filter            = get_post($filter);
                $this->session->set_userdata('account_' . $filter, $$filter);
            } else {
                if (isset($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], 'accounts') !== false && $this->session->has_userdata('account_' . $filter)) {
                    $$filter    = $this->session->userdata('service_' . $filter);
                } else {
                    $this->session->unset_userdata('accounts_' . $filter);
                }
            }

            if ($filter == 'search_name' && $$filter != false) {
                $where['CONCAT(FirstName, " ", LastName) LIKE ']  = "%{$search_name}%";
            } else if ($filter == 'search_mid' && $$filter != false) {
                $where['MabuhayID']  = $search_mid;
            } else if ($filter == 'search_account_level' && $$filter != false) {
                $where['AccountLevelID']  = $search_account_level;
            } else if ($filter == 'search_account_status' && $$filter != false) {
                // 100 = pending, replace to actual value on query
                if ($search_account_status == 100) {
                    $where['StatusID']  = 0;
                } else {
                    $where['StatusID']  = $search_account_status;
                }
            } else if ($filter == 'search_account_city' && $$filter != false) {
                $where['MunicipalityCityID']  = $search_account_city;
            } 

            // account level limitation filter
            // only lower or same level of the user
            $user_account_level = $viewData['accountInfo']->AccountLevelID;
            $where['AccountLevelID <= '] = $user_account_level;

            // only show users on the same brangay if barangay level            
            // only show user on city/muni if muni city level
            // and so on upto regional level
            if ($user_account_level == 8) {
                $where['BarangayID'] = $viewData['accountInfo']->BarangayID;
            } else if ($user_account_level == 9 || $user_account_level == 10) {
                $where['MunicipalityCityID'] = $viewData['accountInfo']->MunicipalityCityID;
            } else if ($user_account_level == 11) {
                $where['ProvincialID'] = $viewData['accountInfo']->ProvincialID;
            } else if ($user_account_level == 12) {
                $where['RegionalID'] = $viewData['accountInfo']->RegionalID;
            }

            // search params
            $viewData[$filter] = $$filter;

        }

        $paginatationData = $this->mgovdb->getPaginationData('UserAccountInformation', $page_limit, $page_start, $where, $order);

        // prepare account data
        $accounts = array();
        foreach ($paginatationData['data'] as $item) {
            $accounts[] = prepare_account_data($item);
        }

        // echo '<pre>';print_r($accounts);exit;

        $paginationConfig = array(
            'base_url'      => base_url('accounts/index'),
            'total_rows'    => $paginatationData['count'],
            'per_page'      => $page_limit,
            'full_tag_open' => '<ul class="pagination pagination-sm no-margin pull-right">'
        );

        $viewData['accounts']   = $accounts;
        $viewData['pagination'] = paginate($paginationConfig);
        $levels = lookup_db('UserAccountLevel', false, false, false);
        foreach ($levels as $k => $v) {
            if ($k <= $this->session->userdata('alevel')) {
                $viewData['account_levels'][$k] = $v;
            }
        }

        // echo '<pre>';print_r($viewData['account_levels']);exit;

        view('pages/accounts/index', $viewData, 'templates/mgovadmin');
    }

    /**
    * update account status 
    */
    public function update_account()
    {
        if (validate('account_registration') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $accountData = $this->mgovdb->getRowObject('UserAccountInformation', get_post('id'), 'id');

            if ($accountData != false) {

                $photoFilename  = md5($accountData->RegistrationID);

                // validate file upload
                $this->load->library('upload', array(
                    'upload_path'   => PHOTO_DIRECTORY,
                    'allowed_types' => 'gif|jpg|png',
                    'max_size'      => '2000', // 2mb
                    'max_width'     => '1024',
                    'max_height'    => '768',
                    'overwrite'     => true,
                    'file_name'     => $photoFilename
                ));

                if (!empty($_FILES['avatarFile']['name']) && $this->upload->do_upload('avatarFile') == false) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Uploading picture failed.',
                        'fields'    => array('avatarFile' => $this->upload->display_errors('',''))
                    );
                } else {

                    // do save

                    $fields         = $this->mgovdb->tableColumns('UserAccountInformation');
                    $exclude_fields = array('MabuhayID','Username','NationalZoneID','GovernmentID','RegistrationDate');
                    $locInfo        = $this->mgovdb->getRowObject('UtilLocBrgy', $this->input->post('BarangayID'), 'brgyCode');

                    $updateData     = array(
                        'id'                => get_post('id'),
                        'BirthDate'         => date('Y-m-d', strtotime($this->input->post('BirthDate'))),
                        'RegionalID'        => ($locInfo ? $locInfo->regCode : ''),
                        'ProvincialID'      => ($locInfo ? $locInfo->provCode : ''),
                        'LastUpdate'        => date('Y-m-d H:i:s')
                    );

                    if (!empty($_FILES['avatarFile']['name'])) {
                        $updateData['Photo'] = $this->upload->data('file_name');
                    }

                    foreach ($fields as $field) {
                        if (!array_key_exists($field, $updateData) && !in_array($field, $exclude_fields) && get_post($field) !== null) {
                            $updateData[$field] = $this->input->post($field);
                        }
                    }

                    if ($this->mgovdb->saveData('UserAccountInformation', $updateData)) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Account has been updated successfully.'
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Account update failed. Please try again later.'
                        );
                        @unlink($this->upload->data('full_name'));
                    }

                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Account does not exists.'
                );
            }
        }
        response_json($return_data);
    }


    /**
    * set account status
    *     account type
    *     account level  
    */
    public function approve_account()
    {
        if (validate('account_approval') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {
            $accountID  = get_post('id');
            $typeID     = get_post('AccountTypeID');
            $levelID    = get_post('AccountLevelID');

            $accountData = $this->mgovdb->getRowObject('UserAccountInformation', $accountID, 'id');

            if (!$accountData) {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Account does not exists.'
                );
            } else if ($accountData->StatusID != 0) {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Account is not on pending status.'
                );
            } else if ($accountData->ApprovedBy) {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Account was already approved on ' . date('M d, Y', strtotime($accountData->ApprovedDate))
                );
            } else {

                $random_password = random_password();
                $updateData = array(
                    'ApprovedBy'        => current_user(),
                    'ApprovedDate'      => date('Y-m-d H:i:s'),
                    'LastUpdate'        => date('Y-m-d H:i:s'),
                    'AccountTypeID'     => $typeID,
                    'AccountLevelID'    => $levelID,
                    'StatusID'          => 1, // active id
                    'Password'          => $this->authentication->hash_password($random_password),
                    'id'                => $accountID
                );

                if (($ID = $this->mgovdb->saveData('UserAccountInformation', $updateData))) {

                    // send approval email
                    $emailTemplateData = array(
                        'account'   => $accountData,
                        'password'  => $random_password
                    );
                    $emailData = array(
                        'from'      => array('info@mgov.ph', 'MGov Info'),
                        'to'        => array($accountData->EmailAddress),
                        'subject'   => 'Welcome to MGOV',
                        'message'   => view('email_templates/account_approval', $emailTemplateData, null, true)
                    );
                    send_email($emailData, true);

                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Account status has been set successfully.',
                        'id'        => $ID
                    );

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Setting account status failed. Please try again.'
                    );
                }
            }

        }

        response_json($return_data);
    }

    /**
    * delete account
    */
    public function delete($id)
    {
        if ($id) {
            $record = $this->mgovdb->getRowObject('UserAccountInformation', $id, 'RegistrationID');
            if ($record) {
                $updateData = array(
                    'id'         => $record->id,
                    'deletedBy'  => current_user(),
                    'deletedAt'  => date('Y-m-d H:i:s')
                );
                if ($this->mgovdb->saveData('UserAccountInformation', $updateData)) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Account has been deleted.'
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Deleting account failed.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid account.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid account.'
            );
        }

        response_json($return_data);
    }

    /**
    * reset password
    */
    public function rpassword($id)
    {
        if ($id) {
            $record = $this->mgovdb->getRowObject('UserAccountInformation', $id, 'RegistrationID');
            if ($record) {

                $random_password = random_password();

                $updateData = array(
                    'id'         => $record->id,
                    'Password'   => $this->authentication->hash_password($random_password),
                    'LastUpdate' => date('Y-m-d H:i:s')
                );

                if ($this->mgovdb->saveData('UserAccountInformation', $updateData)) {

                    // send approval email
                    $emailTemplateData = array(
                        'account'   => $record,
                        'password'  => $random_password
                    );
                    $emailData = array(
                        'from'      => array('info@mgov.ph', 'MGov Info'),
                        'to'        => array($record->EmailAddress),
                        'subject'   => 'MgovPh Password Reset',
                        'message'   => view('email_templates/password_reset', $emailTemplateData, null, true)
                    );
                    send_email($emailData, true);

                    $return_data = array(
                        'status'    => true,
                        'message'   => 'New password has been set and email has been sent.'
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Generating new account password failed.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid account.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid account.'
            );
        }

        response_json($return_data);
    }

}
