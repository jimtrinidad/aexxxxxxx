<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Marketplace extends CI_Controller
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
            'pageTitle'     => 'Marketplace',
            'accountInfo'   => user_account_details()
        );

        $page_limit = 20;
        $page_start = (int) $this->uri->segment(3);

        $order = 'LastUpdate Desc';
        $where = array();

        $params     = array();
        $sellers    = array();
        $sql = "SELECT * FROM BusinessItems
                WHERE id IS NOT NULL";

        $products = $this->db->query($sql, $params)->result_array();
        foreach ($products as &$product) {
            if (!isset($sellers[$product['BusinessID']])) {
                $sellers[$product['BusinessID']] = lookup_business_data($product['BusinessID']);
            }
            $product['seller'] = $sellers[$product['BusinessID']];
        }


        $paginatationData = $this->mgovdb->getPaginationData('BusinessItems', $page_limit, $page_start, $where, $order);

        $products = array();
        foreach ($paginatationData['data'] as $product) {
            $product = (array) $product;
            if (!isset($sellers[$product['BusinessID']])) {
                $sellers[$product['BusinessID']] = lookup_business_data($product['BusinessID']);
            }
            $product['seller'] = $sellers[$product['BusinessID']];

            $products[] = $product;
        }

        $paginationConfig = array(
            'base_url'      => base_url('marketplace/index'),
            'total_rows'    => $paginatationData['count'],
            'per_page'      => $page_limit,
            'full_tag_open' => '<ul class="pagination pagination-sm no-margin pull-right">'
        );

        $viewData['products']   = $products;
        $viewData['pagination'] = paginate($paginationConfig);
        // print_data($products, true);

        view('main/marketplace/index', $viewData, 'templates/mgov');
    }

}
