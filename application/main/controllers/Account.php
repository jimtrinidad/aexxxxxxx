<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

    public function test() {
        // echo generate_mabuhay_id('De Guzman');
        // var_dump(lookup('function_type'));
        // $data = array(
        //     'from'  => array('info@mgov.ph', 'MGov Info'),
        //     'to'    => array('jimtrinidad002@gmail.com'),
        //     'subject'   => 'This another is a test ' . date('Y-m-d H:i:s'),
        //     'message'   => 'This is a new <b>test</b> message from info smtp'
        // );
        // print_r($data);
        // send_email($data, false);exit;

        // die(compute_expiration_date($this->uri->segment(3)));

        // $accountData = $this->mgovdb->getRowObject('UserAccountInformation', 66, 'id');
        // $emailTemplateData = array(
        //                 'account'   => $accountData,
        //                 'password'  => random_password()
        //             );

        // echo view('email_templates/account_approval', $emailTemplateData, null, true); exit;
        
        //             $emailData = array(
        //                 'from'      => array('info@mgov.ph', 'MGov Info'),
        //                 'to'        => array($accountData->EmailAddress),
        //                 'subject'   => 'Welcome to MGOV',
        //                 'message'   => view('email_templates/account_approval', $emailTemplateData, null, true)
        //             );

        //             echo '<pre>';print_r($emailData);

        // var_dump(get_department_service_provided(17, array(17 => 5)));exit;
        // print_data(get_service_providers(1, array()));
        // print_data(user_account_details(), true); exit;

        $data['userData']       = user_account_details();
        $data['extraFields']    = json_decode('{"HxODTWeU":"main pet name","ihOfLUPQ":"desc","QqrsxG":"the <b>brgy</b> purpose"}', true);
        $data['uploadedFiles']  = json_decode('{"AcJYBReD":"7ea9ec34483248931987844e2a13f88e.png","cxqutl":"665fdd4b55894e2587780c08734fa3eb.png"}', true);
        $html = generate_document_from_template(1, $data);

        // echo $html;exit;
        $mpdf = new \Mpdf\Mpdf(array('format' => 'Letter', 'mode' => 'utf-8'));

        $mpdf->SetHTMLFooter('
                <table width="100%" style="font-size:8px;">
                    <tr>
                        <td width="70%" align="left">This is a system generated document. If you have any question, ask the nearest officer in your area.</td>
                        <td width="30%" align="right">{DATE j-m-Y}</td>
                    </tr>
                </table>');

        $mpdf->WriteHTML($html);
        $mpdf->Output(); // opens in browser
    }


    /**
    * main account page
    * auth required
    */
    public function index()
    {
        if (isGuest()) {
            redirect();
        }

        $viewData = array(
            'pageTitle'     => 'Account',
            'accountInfo'   => user_account_details()
        );

        $pending_applications = $this->mgovdb->getServiceApplications(array(
            'ApplicantID'   => current_user(),
            'sa.Status'     => 0
        ));

        $completed_applications = $this->mgovdb->getServiceApplications(array(
            'ApplicantID'   => current_user(),
            'sa.Status'     => 2
        ), 'sa.DateCompleted DESC');

        $documents = $this->mgovdb->getUserActiveDocuments(current_user());

        $viewData['pending_applications']   = $pending_applications;
        $viewData['completed_applications'] = $completed_applications;
        $viewData['documents']              = $documents;
        // print_data($viewData, true);

        view('account/index', $viewData, 'templates/mgov-with-account');
    }

    /**
     * Open login page
     */
    public function signin()
    {
        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        $viewData = array(
            'pageTitle' => 'Sign in',
        );

        view('account/login', $viewData, 'templates/account');
    }

    /**
     * Attempt authentication
     */
    public function login()
    {
        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if ($username == '') {
            $return_data = array(
                'status'  => false,
                'message' => 'Username is required.',
            );
        } else if ($password == '') {
            $return_data = array(
                'status'  => false,
                'message' => 'Password is required.',
            );
        } else {

            if ($this->authentication->login($username, $password)) {
                $return_data = array(
                    'status'  => true,
                    'message' => 'Authentication successful.',
                );
            } else {
                $return_data = array(
                    'status'  => false,
                    'message' => 'Authentication failed.',
                );
            }

        }

        response_json($return_data);

    }

    /**
     * Open registration page
     */
    public function signup()
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        $viewData = array(
            'pageTitle' => 'Sign up',
            'RegistrationID' => microsecID(),
        );

        view('account/registration', $viewData, 'templates/account');
    }

    /**
     * attempt and save registration
     */
    public function register()
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

    	if (validate('account_registration') == FALSE) {
            $return_data = array(
            	'status'	=> false,
            	'message'	=> 'Some fields have errors.',
            	'fields'	=> validation_error_array()
            );
        } else {

            $registrationID = $this->input->post('RegistrationID');

            // validate file upload
            $this->load->library('upload', array(
                'upload_path'   => PHOTO_DIRECTORY,
                'allowed_types' => 'gif|jpg|png',
                'max_size'      => '2000', // 2mb
                'max_width'     => '1024',
                'max_height'    => '768',
                'overwrite'     => true,
                'file_name'     => md5($registrationID)
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
                $locInfo        = $this->mgovdb->getRowObject('UtilLocBrgy', $this->input->post('BarangayID'), 'brgyCode');

                $mabuhayID      = generate_mabuhay_id(get_post('LastName'));

                $insertData     = array(
                    'MabuhayID'         => $mabuhayID,
                    'Username'          => $mabuhayID, // copy mabuhay id, used it as username for login
                    'BirthDate'         => date('Y-m-d', strtotime($this->input->post('BirthDate'))),
                    'NationalZoneID'    => '',
                    'RegionalID'        => ($locInfo ? $locInfo->regCode : ''),
                    'ProvincialID'      => ($locInfo ? $locInfo->provCode : ''),
                    'GovernmentID'      => json_encode($this->input->post('GovernmentID')),
                    'Photo'             => (!empty($_FILES['avatarFile']['name']) ? $this->upload->data('file_name') : ''),
                    'RegistrationDate'   => date('Y-m-d H:i:s')
                );

                foreach ($fields as $field) {
                    if (!array_key_exists($field, $insertData) && $this->input->post($field)) {
                        $insertData[$field] = $this->input->post($field);
                    }
                }

                if ($this->mgovdb->getRowObject('UserAccountInformation', $mabuhayID, 'MabuhayID') === false) {

                    if (($ID = $this->mgovdb->saveData('UserAccountInformation', $insertData))) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Account registration successful. You will received an email upon approval with your Mabuhay ID and Password.',
                            'id'        => $ID
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Registration failed. Please try again later.'
                        );
                        @unlink($this->upload->data('full_name'));
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Account already exists.'
                    );
                    @unlink($this->upload->data('full_name'));
                }

            }
        }
    	response_json($return_data);
    }

    /**
    * change password
    */
    public function changep()
    {
        if (validate('change_password') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $userData = $this->mgovdb->getRowObject('UserAccountInformation', get_post('mid'), 'MabuhayID');
            if ($userData) {

                $currentPass = get_post('currentp');
                $newPass     = get_post('newp');

                if ($this->authentication->verify_password($currentPass, $userData->Password)) {
                    $updateData = array(
                        'id'         => $userData->id,
                        'Password'   => $this->authentication->hash_password($newPass),
                        'LastUpdate' => date('Y-m-d H:i:s')
                    );
                    if ($this->mgovdb->saveData('UserAccountInformation', $updateData)) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Password has been changed successfully'
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Saving new password failed.'
                        );
                    }
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Some fields have errors.',
                        'fields'    => array(
                            'currentp'  => 'Wrong current password.'
                        )
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid user.'
                );
            }
        }

        response_json($return_data);
    }

    /**
    * update profile picture
    */
    public function changeprofile()
    {

        $userData = $this->mgovdb->getRowObject('UserAccountInformation', get_post('mid'), 'MabuhayID');
        if ($userData) {

            $photoFilename  = md5($userData->RegistrationID);

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

            if (empty($_FILES['avatarFile']['name'])) {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'No image selected.'
                );
            } else {
                if ($this->upload->do_upload('avatarFile') == false) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Uploading picture failed.',
                        'fields'    => array('avatarFile' => $this->upload->display_errors('',''))
                    );
                } else {

                    $updateData = array(
                        'id'         => $userData->id,
                        'Photo'      => $this->upload->data('file_name'),
                        'LastUpdate' => date('Y-m-d H:i:s')
                    );

                    if ($this->mgovdb->saveData('UserAccountInformation', $updateData)) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Profile picture has been updated successfully'
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Saving new picture failed.'
                        );
                    }

                }
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid user.'
            );
        }

        response_json($return_data);
    }

    /**
     * destroy session, redirect to homepage/login
     */
    public function logout()
    {
        $this->authentication->logout();
        redirect();
    }


    // FORGOT PASSWORD

    public function forgot()
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        $viewData = array(
            'pageTitle' => 'Forgot Password',
        );

        view('account/forgot', $viewData, 'templates/account');
    }

    public function forgot_password()
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        if (validate('forgot_password') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $users = lookup_all('UserAccountInformation', array('EmailAddress' => get_post('account_email')), 'EmailAddress');

            $continue = true;
            if (count($users) == 0) {
                $user = false;
            } else if (count($users) == 1) {
                $user = (object) $users[0];
            } else {
                $continue = false;
                if (get_post('account_id') && strlen(trim(get_post('account_id'))) > 0) {
                    $continue = true;
                    $user = $this->mgovdb->getRowObjectWhere('UserAccountInformation', array('EmailAddress' => get_post('account_email'), 'MabuhayID' => get_post('account_id')));
                } else {
                    $return_data = array(
                        'status'    => false,
                        // 'message'   => 'Multiple user found with this email. Mabuhay ID is required to continue',
                        'fields'    => array(
                            'account_id'    => 'Multiple user found with this email.<br>Mabuhay ID is required to continue account verification.'
                        )
                    );
                }
            }

            if ($continue) {
                if ($user) {

                    $saveData = array(
                        'AccountID'     => $user->id,
                        'Code'          => sha1(microsecID(true)),
                        'Expiration'    => strtotime('+12 hours')
                    );

                    if ($this->mgovdb->saveData('ResetPassword', $saveData)) {

                        $emailData = array(
                            'from'      => array('info@mgov.ph', 'Mgov.ph'),
                            'to'        => array($user->EmailAddress),
                            'subject'   => 'Reset Password',
                            'message'   => view('email_templates/forgot_password', array(
                                'name'      => $user->FirstName . ' ' . $user->LastName,
                                'code'      => $saveData['Code']
                            ), null, true)
                        );
                        send_email($emailData, true);

                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Instruction will be send to your email addres on how to reset your password.'
                        );

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Request failed. Please try again later.'
                        );
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Account email address does not exists.'
                    );
                }
            }

        }

        response_json($return_data);
    }

    public function reset($code = '')
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        $resetCode = $this->mgovdb->getRowObject('ResetPassword', $code, 'Code');
        if ($code && $resetCode && time() <= $resetCode->Expiration) {

            $user = $this->mgovdb->getRowObject('UserAccountInformation', $resetCode->AccountID);

            $viewData = array(
                'pageTitle' => 'Change Password',
                'jsModules' => array(
                    'account'
                )
            );

            $viewData['reset_code'] = $resetCode->Code;

            view('account/reset', $viewData, 'templates/account');
        } else {
            show_404();
        }
    }

    public function reset_password()
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }
        
        if (validate('reset_password') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $resetCode = $this->mgovdb->getRowObject('ResetPassword', get_post('reset_code'), 'Code');
            if ($resetCode) {

                $user = $this->mgovdb->getRowObject('UserAccountInformation', $resetCode->AccountID);

                if ($user) {

                    $saveData = array(
                        'id'            => $user->id,
                        'Password'      => $this->authentication->hash_password(get_post('Password')),
                        'LastUpdate'    => date('Y-m-d H:i:s')
                    );

                    if ($this->mgovdb->saveData('UserAccountInformation', $saveData)) {

                        $this->mgovdb->deleteData('ResetPassword', $resetCode->id);

                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Password has been changed successfully.'
                        );

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Changing password failed. Please try again later.'
                        );
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Invalid account password reset.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid password reset request.s'
                );
            }
            
        }

        response_json($return_data);
    }

}
