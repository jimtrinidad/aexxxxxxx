<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Businesses extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();

        $business = $this->mgovdb->getRowObject('Businesses', current_user(), 'OwnerID');
        if (!$business) {
            redirect(site_url('services/?v=1551833890076'));
        }

    }

    public function index($code = null)
    {
        $viewData = array(
            'pageTitle'     => 'Businesses',
            'accountInfo'   => user_account_details(),
            'jsModules'         => array(
            ),
        );

        $viewData['businesses'] = $this->mgovdb->getRecords('Businesses', array('OwnerID' => current_user()));
        // print_data($viewData);
        view('main/businesses/index', $viewData, 'templates/mgov');
    }

    public function view($code = null)
    {

        $business = $this->mgovdb->getRowObjectWhere('Businesses', array('Code' => $code, 'OwnerID' => current_user()));

        if ($business) {

            $businessName = ($business->Name ? $business->Name : $business->Code);

            $viewData = array(
                'pageTitle'     => 'Businesses - ' . $businessName,
                'accountInfo'   => user_account_details(),
                'jsModules'         => array(
                    'business'
                ),
                'businessName'  => $businessName,
                'businessData'  => $business
            );

            $storeItems = $this->mgovdb->getRecords('BusinessItems', array('BusinessID' => $business->id));

            $viewData['items']  = $storeItems;

            // print_data($viewData);
            view('main/businesses/view', $viewData, 'templates/mgov');
        } else {
            redirect(site_url('businesses'));
        }
    }

    public function saveitem()
    {
        if (validate('save_business_item') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $business = $this->mgovdb->getRowObject('Businesses', get_post('BusinessCode'), 'Code');
            if ($business) {

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

                    $saveData = array(
                        'Name'              => get_post('Name'),
                        'Description'       => get_post('Description'),
                        'Measurement'       => get_post('Measurement'),
                        'Price'             => get_post('Price'),
                        'LastUpdate'        => date('Y-m-d H:i:s')
                    );

                    if (!empty($_FILES['Logo']['name'])) {
                        $saveData['Image'] = $uploadData['file_name'];
                    }

                    $itemData = $this->mgovdb->getRowObject('BusinessItems', get_post('Code'), 'Code');
                    if ($itemData) {
                        $saveData['id'] = $itemData->id;
                    } else {
                        $saveData['Code']           = microsecID();
                        $saveData['BusinessID']     = $business->id;
                    }

                    if (($ID = $this->mgovdb->saveData('BusinessItems', $saveData))) {

                        // delete old logo if edited
                        if ($itemData !== false && isset($saveData['Image'])) {
                            @unlink(LOGO_DIRECTORY . $itemData->Image);
                        }

                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Business product has been saved.',
                            'id'        => $ID
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Saving product failed. Please try again later.'
                        );
                    }

                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Business not found.'
                );
            }
        }

        response_json($return_data);
    }

}
