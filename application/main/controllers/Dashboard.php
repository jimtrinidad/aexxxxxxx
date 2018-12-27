<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
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
            'pageTitle'     => 'Dashboard',
            'accountInfo'   => user_account_details()
        );        

        // echo '<pre>';print_r($viewData);echo '</pre>';
        view('main/dashboard', $viewData, 'templates/mgov');
        // view('main/blank', $viewData, 'templates/mgov');
    }

}
