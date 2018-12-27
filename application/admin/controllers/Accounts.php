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

        $page_limit = 50;
        $page_start = (int) $this->uri->segment(3);

        $where = array(
            'deletedAt' => NULL,
            // 'CONCAT(FirstName, " ", LastName) LIKE ' => '%ran%'
        );
        $order = 'StatusID, FirstName';

        // SET SEARCH FILTER
        $search_mid            = false;
        $search_name           = false;
        $search_account_status = false;
        $search_account_level  = false;

        if (get_post('search_name') !== null) {
            $search_mid             = get_post('search_mid');
            $search_name            = get_post('search_name');
            $search_account_status  = get_post('search_account_status');
            $search_account_level   = get_post('search_account_level');
            $this->session->set_userdata('account_search_mid', $search_mid);
            $this->session->set_userdata('account_search_name', $search_name);
            $this->session->set_userdata('account_search_account_status', $search_account_status);
            $this->session->set_userdata('account_search_account_level', $search_account_level);
        } else {
            if (isset($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], 'accounts') !== false && $this->session->has_userdata('account_search_name')) {
                $search_mid             = $this->session->userdata('account_search_mid');
                $search_name            = $this->session->userdata('account_search_name');
                $search_account_status  = $this->session->userdata('account_search_account_status');
                $search_account_level   = $this->session->userdata('account_search_account_level');
            } else {
                $this->session->unset_userdata('account_search_mid');
                $this->session->unset_userdata('account_search_name');
                $this->session->unset_userdata('account_search_account_status');
                $this->session->unset_userdata('account_search_account_level');
            }
        }

        if ($search_name != false) {
            $where['CONCAT(FirstName, " ", LastName) LIKE ']  = "%{$search_name}%";
        }
        if ($search_account_status != false) {
            // 100 = pending, replace to actual value on query
            if ($search_account_status == 100) {
                $where['StatusID']  = 0;
            } else {
                $where['StatusID']  = $search_account_status;
            }
        }
        if ($search_account_level != false) {
            $where['AccountLevelID']  = $search_account_level;
        }

        if ($search_mid != false) {
            $where['MabuhayID']  = $search_mid;
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


        // search params
        $viewData['search_mid']             = $search_mid;
        $viewData['search_name']            = $search_name;
        $viewData['search_account_status']  = $search_account_status;
        $viewData['search_account_level']   = $search_account_level;

        $viewData['accounts']   = $accounts;
        $viewData['pagination'] = paginate($paginationConfig);
        $viewData['account_levels'] = lookup_db('UserAccountLevel', false, false, false);

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

}
