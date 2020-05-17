<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        // check_authentication();
    }

    public function index()
    {
        $viewData = array(
            'pageTitle'     => 'Dashboard',
            'accountInfo'   => user_account_details(),
            'jsModules'         => array(
                'wallet',
            ),
        );        

        // print_data(lookup_dbp_billers());
        view('main/dashboard/index', $viewData, 'templates/mgov');
        // view('main/blank', $viewData, 'templates/mgov');
    }

    public function services()
    {
        $records = $this->mgovdb->getServicesForUser(array(
            'userID'    => current_user(),
            'keyword'   => get_post('keyword')
        ));
        
        $grouped = array();
        foreach ($records as $record) {
            if ($record['CategoryID']) {
                $record['Logo'] = logo_filename($record['Logo']);
                $grouped[$record['CategoryID']][] = $record;
            }
        }
        ksort($grouped);

        $results = array();
        foreach ($grouped as $cat => $items) {
            if (lookup('service_categories', $cat)) {
                $results[] = array(
                    'category'  => lookup('service_categories', $cat),
                    'items'     => $items
                );
            }
        }

        $viewData = array(
            'pageTitle'     => 'Dashboard',
            'accountInfo'   => user_account_details()
        );

        $viewData['categories'] = $results;
        view('main/dashboard/services', $viewData, 'templates/mgov');

    }

}
