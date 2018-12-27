<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Trabaho extends CI_Controller
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
            'pageTitle'     => 'My Trabaho',
            'accountInfo'   => user_account_details()
        );        

        // echo '<pre>';print_r($viewData);echo '</pre>';
        view('main/mytrabaho', $viewData, 'templates/mgov');
        // view('main/blank', $viewData, 'templates/mgov');
    }

}
