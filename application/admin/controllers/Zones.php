<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Zones extends CI_Controller
{

    private $todelete = array();
    private $uploaded = array();
    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();

    }

    /**
    * main and sub department listing and manager
    */
    public function index($region = null, $province = null, $city = null)
    {
        $viewData = array(
            'pageTitle'         => 'Geo Zones Manager',
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'utils',
                'zones'
            ),
        );

        if ($city) {
            // get barangay
            $regionInfo = lookup_row('UtilLocRegion', $region, 'regCode');
            $provInfo   = lookup_row('UtilLocProvince', $province, 'provCode');
            $cityInfo   = lookup_row('UtilLocCityMun', $city, 'citymunCode');
            
            $breadcrumbs[site_url('zones/index')] = 'National';
            $breadcrumbs[site_url('zones/index/' . $region)] = $regionInfo->regDesc;
            $breadcrumbs[site_url('zones/index/' . $region . '/' . $province)] = $provInfo->provDesc;
            $breadcrumbs[site_url('zones/index/' . $region . '/' . $province . '/' . $city)] = $cityInfo->citymunDesc;

            $viewData['items']       = lookup_all('UtilLocBrgy', array('citymunCode' => $city), 'brgyDesc', false);
            $viewData['view']        = 'barangay';
            $viewData['tableTitle']  = 'Barangay';
            $viewData['editData']    = array(
                'brgyCode'  => 'psgc',
                'brgyDesc'  => 'name',
                'logo'      => 'logo'
            );
            $viewData['fields']      = array(
                'brgyCode'      => 'PSGC/Code',
                'brgyDesc'      => 'Name',
                'citymunCode'   => 'City/Muni Code',
                'provCode'      => 'Province Code',
                'regCode'       => 'Region Code',
            );
        } else if ($province) {
            // get cities & muni
            $regionInfo = lookup_row('UtilLocRegion', $region, 'regCode');
            $provInfo   = lookup_row('UtilLocProvince', $province, 'provCode');
            
            $breadcrumbs[site_url('zones/index')] = 'National';
            $breadcrumbs[site_url('zones/index/' . $region)] = $regionInfo->regDesc;
            $breadcrumbs[site_url('zones/index/' . $region . '/' . $province)] = $provInfo->provDesc;

            $viewData['items']      = lookup_all('UtilLocCityMun', array('provCode' => $province), 'citymunDesc', false);
            $viewData['view']       = 'city';
            $viewData['tableTitle'] = 'Cities and Municipalities';
            $viewData['lowerLink']  = array(
                'url'   => site_url("zones/index/{$region}/{$province}/"),
                'key'   => 'citymunCode',
                'name'  => 'Barangay'
            );
            $viewData['editData']    = array(
                'psgcCode'     => 'psgc',
                'citymunDesc'  => 'name',
                'type'      => 'type', //(city or muni)
                'logo'      => 'logo'
            );
            $viewData['fields']     = array(
                'psgcCode'      => 'PSGC',
                'citymunCode'   => 'Code',
                'citymunDesc'   => 'Name',
                'provCode'      => 'Province Code',
                'regCode'       => 'Region Code',
            );
        } else if ($region) {
            // get provices
            $regionInfo = lookup_row('UtilLocRegion', $region, 'regCode');
            
            $breadcrumbs[site_url('zones/index')] = 'National';
            $breadcrumbs[site_url('zones/index/' . $region)] = $regionInfo->regDesc;

            $viewData['items']      = lookup_all('UtilLocProvince', array('regCode' => $region), 'provDesc', false);
            $viewData['view']       = 'province';
            $viewData['tableTitle'] = 'Provinces';
            $viewData['lowerLink']  = array(
                'url'   => site_url("zones/index/{$region}/"),
                'key'   => 'provCode',
                'name'  => 'City/Muni'
            );
            $viewData['editData']    = array(
                'psgcCode'  => 'psgc',
                'provDesc'  => 'name',
                'logo'      => 'logo'
            );
            $viewData['fields']     = array(
                'psgcCode'   => 'PSGC',
                'provCode'   => 'Code',
                'provDesc'   => 'Name',
                'regCode'    => 'Region Code',
            );
        } else {
            // list regions
            $breadcrumbs[site_url('zones/index')] = 'National';
            $viewData['items']      = lookup_all('UtilLocRegion', false, 'regCode', false); 
            $viewData['view']       = 'region';       
            $viewData['tableTitle'] = 'Regions';
            $viewData['lowerLink']  = array(
                'url'   => site_url('zones/index/'),
                'key'   => 'regCode',
                'name'  => 'Provinces'
            );
            $viewData['editData']    = array(
                'psgcCode'  => 'psgc',
                'regDesc'   => 'name',
                'logo'      => 'logo'
            );
            $viewData['fields']     = array(
                'psgcCode'  => 'PSGC',
                'regCode'   => 'Code',
                'regDesc'   => 'Name'
            );
        }

        $viewData['breadcrumbs'] = $breadcrumbs;
        // print_data($viewData, true);
        view('pages/zones/index', $viewData, 'templates/mgovadmin');
    }


    /**
    * save zone
    */
    public function save_zone()
    {
        if (validate('save_zone') == FALSE) {
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

                $table          = false;
                $insertData     = array(
                    'lastupdate'  => date('Y-m-d H:i:s'),
                );

                if (!empty($_FILES['Logo']['name'])) {
                    $insertData['logo'] = $uploadData['file_name'];
                }

                switch(get_post('zonetype')) {
                    case 'barangay':
                        $table  = 'UtilLocBrgy';
                        $insertData['brgyDesc'] = get_post('Name');
                        break;
                    case 'city':
                        $table  = 'UtilLocCityMun';
                        $insertData['type'] = get_post('Type');
                        $insertData['citymunDesc'] = get_post('Name');
                        break;
                    case 'province':
                        $table  = 'UtilLocProvince';
                        $insertData['provDesc'] = get_post('Name');
                        break;
                    case 'region':
                        $table  = 'UtilLocRegion';
                        $insertData['regDesc'] = get_post('Name');
                        break;
                }

                $existingData = $this->mgovdb->getRowObject($table, get_post('zonepsgc'), 'psgcCode');

                if ($existingData) {
                    $insertData['id'] = $existingData->id;
                    if (($ID = $this->mgovdb->saveData($table, $insertData))) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Zone has been updated successfully.',
                            'id'        => $ID,
                            'data'      => $insertData
                        );

                        // delete old logo if edited
                        if ($existingData->logo != 'logo_default.png' && isset($insertData['logo'])) {
                            @unlink(LOGO_DIRECTORY . $existingData->logo);
                        }

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Saving zone failed. Please try again.'
                        );
                        @unlink($uploadData['full_path']);
                    }
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Zone does not exists.'
                    );
                    @unlink($uploadData['full_path']);
                }

            }
        }
        response_json($return_data);
    }


    /**
    * public office setup
    */
    public function office_setup($zonetype = '', $psgc = '')
    {
        $tables = array(
            'city'      => 'UtilLocCityMun',
            'province'  => 'UtilLocProvince'
        );

        if (isset($tables[$zonetype]) && ($locationInfo = $this->mgovdb->getRowObject($tables[$zonetype], $psgc, 'psgcCode'))) {

            $viewData = array(
                'pageTitle'         => 'Public Office Setup',
                'accountInfo'       => user_account_details(),
                'locationInfo'      => $locationInfo,
                'zonetype'          => $zonetype,
                'psgc'              => $psgc,
                'jsModules'         => array(
                    'utils',
                    'zones'
                ),
                'initialServantID'  => random_letters(6),
                'initialBannerID'   => random_letters(6),
                'returnUrl'         => (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url('zones')),
                'currentData'       => $this->mgovdb->getRowObject('PublicOffices', $psgc, 'PSGC')
            );

            // print_data($viewData);

            view('pages/zones/office_setup', $viewData, 'templates/mgovadmin');

        } else {
            redirect('zones');
        }

    }


    /**
    * save public office setup
    */
    public function save_office_setup()
    {

        $tables = array(
            'city'      => 'UtilLocCityMun',
            'province'  => 'UtilLocProvince'
        );

        $zonetype = get_post('zonetype');
        $zonepsgc = get_post('zonepsgc');

        if (isset($tables[$zonetype]) && ($locationInfo = $this->mgovdb->getRowObject($tables[$zonetype], $zonepsgc, 'psgcCode'))) {

            $publicOfficeData = $this->mgovdb->getRowObject('PublicOffices', $zonepsgc, 'PSGC');

            if (validate('save_office_setup') == FALSE) {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Some fields have errors.',
                    'fields'    => validation_error_array(),
                    'group'     => 1
                );
            } else {

                // load upload library for image upload
                $this->load->library('upload');

                $publicServants = $this->prepare_public_servants($publicOfficeData);
                $banners        = $this->prepare_banners($publicOfficeData);

                // validate and prepare servants and banners
                if (!$publicServants['status'] || !$banners['status']) {

                    foreach ($this->uploaded as $uploadedfile) {
                        @unlink($uploadedfile);
                    }

                    $errors = array();
                    if (!$publicServants['status']) {
                        $errors = array_merge($errors, $publicServants['data']);
                    }
                    if (!$banners['status']) {
                        $errors = array_merge($errors, $banners['data']);
                    }

                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Image upload error found.',
                        'fields'    => $errors
                    );

                } else {

                    $fields         = $this->mgovdb->tableColumns('PublicOffices');
                    $excludeField   = array('id', 'PSGC', 'PublicServants', 'Banners', 'UpdatedBy', 'LastUpdate');

                    $updateData     = array(
                        'UpdatedBy'     => current_user(),
                        'LastUpdate'    => date('Y-m-d H:i:s'),
                        'PublicServants'=> json_encode($publicServants['data']),
                        'Banners'       => json_encode($banners['data'])
                    );

                    foreach ($fields as $field) {
                        if (!in_array($field, $excludeField) && !array_key_exists($field, $updateData) && get_post($field) !== null) {
                            $updateData[$field] = get_post($field);
                        }
                    }

                    if ($publicOfficeData) {
                        $updateData['id'] = $publicOfficeData->id;
                    } else {
                        $updateData['PSGC'] = $zonepsgc;
                    }

                    if (($ID = $this->mgovdb->saveData('PublicOffices', $updateData))) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Public office setup has been saved successfully.',
                            'id'        => $ID,
                            'data'      => $updateData
                        );

                        // delete old logo if edited
                        foreach ($this->todelete as $item) {
                            @unlink(PUBLIC_DIRECTORY . 'assets/etc/' . $item);
                        }

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Saving public office setup failed. Please try again.'
                        );
                        // delete uploaded
                        foreach ($this->uploaded as $uploadedfile) {
                            @unlink($uploadedfile);
                        }
                    }

                }
            }

        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid request.'
            );
        }

        response_json($return_data);
    }

    private function prepare_public_servants($officeData)
    {
        $errors     = array();
        $cleanItems = array();
        $uploaded   = array();
        $currentItems = array();
        if ($officeData) {
            $currentItems = json_decode($officeData->PublicServants, true);
        }

        $Images  = isset($_FILES['Images']) ? $_FILES['Images'] : array();

        $servants = (array) get_post('Servant');
        foreach ($servants as $id => $servant) {
            if (isset($servant['Ordering'])) {
                // default to current value if exist
                if (isset($currentItems[$id]['Photo'])) {
                    $servant['Photo'] = $currentItems[$id]['Photo'];
                }

                if ($Images['name'][$id] && !empty($Images['name'][$id])) {
                    $rsp = $this->validateAndUploadImage($id);
                    if ($rsp['status']) {
                        $servant['Photo']   = $rsp['data']['file_name'];
                        $this->uploaded[]   = $rsp['data']['full_path'];
                        if (isset($currentItems[$id]['Photo'])) {
                            $this->todelete[] = $currentItems[$id]['Photo'];
                        }
                    } else {
                        $errors[$id] = $rsp['error'];
                    }
                }
                $cleanItems[$id] = $servant;
            }
        }

        // check deleted
        foreach ($currentItems as $id => $item) {
            if (!in_array($id, array_keys($servants))) {
                $this->todelete[] = $currentItems[$id]['Photo'];
            }
        }

        if (count($errors)) {
            return array(
                'status'    => false,
                'message'   => 'Image uploads have errors',
                'data'      => $errors
            );
        } else {
            uasort($cleanItems, function($a, $b) {
                if ($a['Ordering'] == $b['Ordering']) {
                    return 0;
                }
                return ($a['Ordering'] < $b['Ordering']) ? -1 : 1;
            });
            return array(
                'status'    => true,
                'data'      => $cleanItems
            );
        }

    }

    private function prepare_banners($officeData)
    {
        $errors     = array();
        $cleanItems = array();
        $currentItems = array();
        if ($officeData) {
            $currentItems = json_decode($officeData->Banners, true);
        }

        $Images  = isset($_FILES['Images']) ? $_FILES['Images'] : array();

        $banners = (array) get_post('Banners');
        foreach ($banners as $id => $banner) {
            if (isset($banner['Ordering'])) {
                // default to current value if exist
                if (isset($currentItems[$id]['Photo'])) {
                    $banner['Photo'] = $currentItems[$id]['Photo'];
                }

                if ($Images['name'][$id] && !empty($Images['name'][$id])) {
                    $rsp = $this->validateAndUploadImage($id);
                    if ($rsp['status']) {
                        $banner['Photo']   = $rsp['data']['file_name'];
                        $this->uploaded[]  = $rsp['data']['full_path'];
                        if (isset($currentItems[$id]['Photo'])) {
                            $this->todelete[] = $currentItems[$id]['Photo'];
                        }
                    } else {
                        $errors[$id] = $rsp['error'];
                    }
                }
                $cleanItems[$id] = $banner;
            }
        }

        // check deleted
        foreach ($currentItems as $id => $item) {
            if (!in_array($id, array_keys($banners))) {
                $this->todelete[] = $currentItems[$id]['Photo'];
            }
        }

        if (count($errors)) {
            return array(
                'status'    => false,
                'message'   => 'Image uploads have errors',
                'data'      => $errors
            );
        } else {
            uasort($cleanItems, function($a, $b) {
                if ($a['Ordering'] == $b['Ordering']) {
                    return 0;
                }
                return ($a['Ordering'] < $b['Ordering']) ? -1 : 1;
            });
            return array(
                'status'    => true,
                'data'      => $cleanItems
            );
        }

    }

    private function validateAndUploadImage($fieldID)
    {
        $Images  = isset($_FILES['Images']) ? $_FILES['Images'] : array();

        if (isset($Images['name'][$fieldID])) {

            $randomfilename = md5($fieldID . microsecID());

            $upload_options = array(
                'upload_path'   => PUBLIC_DIRECTORY . 'assets/etc/',
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
                'error'     => 'Invalid image.'
            );
        }
    }

}