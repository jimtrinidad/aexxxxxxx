<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wallet extends CI_Controller
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
            'pageTitle'     => 'My Wallet',
            'accountInfo'   => user_account_details()
        );        

        view('main/wallet/index', $viewData, 'templates/mgov');
    }

}
