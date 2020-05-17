<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Livefeed extends CI_Controller
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
            'pageTitle'     => 'Livefeed',
            'accountInfo'   => user_account_details()
        );        

        view('main/livefeed', $viewData, 'templates/mgov');
    }

}
