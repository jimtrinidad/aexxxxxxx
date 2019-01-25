<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Organization extends CI_Controller
{

    private $user;
    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();

        $user = $this->mgovdb->getRowObject('UserAccountInformation', current_user(), 'id');
        if (!$user->OrganizationID) {
            redirect();
        }

        $this->user = $user;
    }

    public function index()
    {
        $viewData = array(
            'pageTitle'     => 'Organization',
            'accountInfo'   => user_account_details(),
            'jsModules'         => array(
                'organization',
            ),
        );

        $organizationData = $this->mgovdb->getRowObject('Dept_ChildDepartment', $this->user->OrganizationID);

        $viewData['Organization']   = $organizationData;
        // print_data($organizationData);
        view('main/organization', $viewData, 'templates/mgov');

    }

    public function services()
    {
        // get services under organization and user scope
        $records = $this->mgovdb->getOrganizationServices(array(
            'userID'        => current_user(),
            'organization'  => $this->user->OrganizationID,
            'keyword'       => get_post('keyword')
        ));

        $grouped = array();
        foreach ($records as $record) {
            $record['Logo'] = logo_filename($record['Logo']);
            $grouped[$record['Category']][] = $record;
        }
        ksort($grouped);

        $results = array();
        foreach ($grouped as $cat => $items) {
            $results[] = array(
                'category'  => lookup('service_organization_category', $cat),
                'items'     => $items
            );
        }

        if (count($records)) {
            $return_data = array(
                'status'    => true,
                'data'      => $results
            );
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'No record found.'
            );
        }

        response_json($return_data);
    }

}