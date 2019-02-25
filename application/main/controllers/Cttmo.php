<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cttmo extends CI_Controller
{

    private $user;
    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();

        $user = $this->mgovdb->getRowObject('UserAccountInformation', current_user(), 'id');
        if (!$user->OrganizationID || !in_array($user->OrganizationID, lookup('cttmo_organizations'))) {
            redirect();
        }

        $this->user = $user;

        $this->load->model('statisticsdb');
    }

    /**
    * monthly violations report for the whole year
    */
    public function monthlyvreports()
    {   

        $year = get_post('year') ?? date('Y');

        $where = array(
            'ss.SubDepartmentID = ?',
            'YEAR(sa.DateApplied) = ?'
        );

        $params = array(
            $this->user->OrganizationID,
            $year
        );


        $records = $this->statisticsdb->organizationMonthlyViolationReport($where, $params);

        $items = array();
        $per_month_count = array();
        $month_total = array();
        foreach ($records as $item) {
            if (!array_key_exists($item['id'], $items)) {
                $items[$item['id']] = array(
                    'Code'  => $item['Code'],
                    'Name'  => $item['Name'],
                    'CommonName'    => $item['MenuName']
                );
            }

            $per_month_count[$item['id']][$item['month']] = $item['applicationCount'];
            $month_total[$item['month']] = (isset($month_total[$item['month']]) ? ($month_total[$item['month']] + $item['applicationCount']) : $item['applicationCount']);
        }

        ksort($month_total);

        $report_data = array(
            'items'             => $items,
            'per_month_count'   => $per_month_count,
            'monthly_total'       => $month_total
        );

        // print_data($report_data, true);

        $viewData = array(
            'pageTitle'     => 'Organization - Monthly Violation Report',
            'accountInfo'   => user_account_details(),
            'nosidebar'     => true,
            'shownav'       => true,
            'reportData'    => $report_data
        );

        view('reports/cttmo/monthlyviolations', $viewData, 'templates/mgov');
    }

    /**
    * daily violations report
    * violation count
    */
    public function dailyvreports()
    {   

        if (get_post('date')) {
            $date = get_post('date');
        } else {
            $date = date('Y-m-d') . ' - ' . date('Y-m-d');
        }

        $daterange = explode(' - ', $date);
        $datefrom  = $daterange[0];
        $dateto    = $daterange[1];

        $where = array(
            'ss.SubDepartmentID = ?',
            'DATE(sa.DateApplied) >= ? AND DATE(sa.DateApplied) <= ?',
        );

        $params = array(
            $this->user->OrganizationID,
            $datefrom,
            $dateto
        );


        $records = $this->statisticsdb->organizationDailyViolationReport($where, $params);

        $items = array();
        $per_day_count = array();
        $day_total = array();
        foreach ($records as $item) {
            if (!array_key_exists($item['id'], $items)) {
                $items[$item['id']] = array(
                    'Code'  => $item['Code'],
                    'Name'  => $item['Name'],
                    'CommonName'    => $item['MenuName']
                );
            }

            $per_day_count[$item['id']][$item['day']] = $item['applicationCount'];
            $day_total[$item['day']] = (isset($day_total[$item['day']]) ? ($day_total[$item['day']] + $item['applicationCount']) : $item['applicationCount']);
        }

        ksort($day_total);

        $report_data = array(
            'items'             => $items,
            'per_day_count'     => $per_day_count,
            'daily_total'       => $day_total
        );

        // print_data($report_data, true);

        $viewData = array(
            'pageTitle'     => 'Organization - Daily Violation Report',
            'accountInfo'   => user_account_details(),
            'nosidebar'     => true,
            'shownav'       => true,
            'date'          => $date,
            'reportData'    => $report_data
        );

        view('reports/cttmo/dailyviolations', $viewData, 'templates/mgov');
    }

    public function yearlyreports()
    {

        $year = get_post('year') ?? date('Y');

        $where = array(
            'ss.SubDepartmentID = ?',
            'YEAR(sa.DateApplied) = ?'
        );

        $params = array(
            $this->user->OrganizationID,
            $year
        );


        $records = $this->statisticsdb->organizationYearlyCategorizeReport($where, $params);

        $categories = array();
        foreach ($records as $item) {
            $categories[$item['Category']][] = $item;
        }

        // print_data($categories, true);

        $viewData = array(
            'pageTitle'     => 'Organization - Yearly Reports',
            'accountInfo'   => user_account_details(),
            'nosidebar'     => true,
            'shownav'       => true,
            'categories'    => $categories
        );

        view('reports/cttmo/yearly', $viewData, 'templates/mgov');
    }

    /**
    * daily apprehension report
    * detailed list
    */
    public function dailyreport()
    {

        $category   = get_post('category') ?? 6; //default pedestrian
        if (get_post('date')) {
            $date = get_post('date');
        } else {
            $date = date('Y-m-d') . ' - ' . date('Y-m-d');
        }

        $daterange = explode(' - ', $date);
        $datefrom  = $daterange[0];
        $dateto    = $daterange[1];

        $where = array(
            'ss.SubDepartmentID = ?',
            'DATE(sa.DateApplied) >= ? AND DATE(sa.DateApplied) <= ?',
            'so.Category = ?'
        );

        $params = array(
            $this->user->OrganizationID,
            $datefrom,
            $dateto,
            $category
        );


        $records = $this->statisticsdb->organizationDailyApprehension($where, $params);

        $serviceFields = array();
        $uniquefields  = array();

        foreach ($records as &$record) {

            if (!isset($serviceFields[$record['ServiceID']])) {
                // get service extra fields label
                $results = $this->mgovdb->getRecords('Service_ExtraFormFields', array('ServiceID' => $record['ServiceID']));
                $fields = array();
                foreach ($results as $r) {
                    if (in_array($r['FieldType'], array(1,2,4))) {
                        $fields[$r['FieldID']] = trim($r['FieldLabel']);
                    }
                }
                $serviceFields[$record['ServiceID']] = $fields;
            }

            $extrafields = json_decode($record['ExtraFields'], true);

            foreach ($extrafields as $key => $val) {
                if (isset($serviceFields[$record['ServiceID']][$key])) {
                    $flabel  = $serviceFields[$record['ServiceID']][$key];
                    $fhash   = md5($flabel);
                    $record[$fhash] = $val;
                    if (!isset($uniquefields[$fhash])) {
                        $uniquefields[$fhash] = $flabel;
                    }
                }
            }

        }

        $viewData = array(
            'pageTitle'     => 'Organization - Yearly Reports',
            'accountInfo'   => user_account_details(),
            'nosidebar'     => true,
            'shownav'       => true,
            'records'       => $records,
            'fields'        => $uniquefields,
            'category'      => $category,
            'date'          => $date,
            'jsModules'         => array(
                'organization',
            ),
        );

        view('reports/cttmo/daily', $viewData, 'templates/mgov');
    }

    public function collectionreport()
    {

        $year = get_post('year') ?? date('Y');

        $where = array(
            'ss.SubDepartmentID = ?',
            'YEAR(sa.DateApplied) = ?'
        );

        $params = array(
            $this->user->OrganizationID,
            $year
        );


        $records = $this->statisticsdb->organizationCollectioReport($where, $params);

        $items = array();
        $per_month_count    = array();
        $per_month_amount   = array();
        $month_total        = array();
        $month_total_amount = array();
        foreach ($records as $item) {
            if (!array_key_exists($item['id'], $items)) {
                $items[$item['id']] = array(
                    'Code'  => $item['Code'],
                    'Name'  => $item['Name'],
                    'CommonName'    => $item['MenuName']
                );
            }

            $per_month_count[$item['id']][$item['month']] = $item['applicationCount'];
            $per_month_amount[$item['id']][$item['month']] = $item['Total'];
            $month_total[$item['month']] = (isset($month_total[$item['month']]) ? ($month_total[$item['month']] + $item['applicationCount']) : $item['applicationCount']);
            $month_total_amount[$item['month']] = (isset($month_total_amount[$item['month']]) ? ($month_total_amount[$item['month']] + $item['Total']) : $item['Total']);
        }

        ksort($month_total);

        $report_data = array(
            'items'                => $items,
            'per_month_count'      => $per_month_count,
            'monthly_total'        => $month_total,
            'per_month_amount'     => $per_month_amount,
            'monthly_total_amount' => $month_total_amount
        );

        // print_data($report_data, true);

        $viewData = array(
            'pageTitle'     => 'Organization - Collection Reports',
            'accountInfo'   => user_account_details(),
            'nosidebar'     => true,
            'shownav'       => true,
            'reportData'    => $report_data
        );

        view('reports/cttmo/collection', $viewData, 'templates/mgov');
    }

    public function collectiondetails($year = false, $month = false, $serviceCode = false)
    {

        if (!$year OR !$month OR !$serviceCode) {
            show_404();
        } else {

            $this->load->model('statisticsdb');

            $where = array(
                'ss.SubDepartmentID = ?',
                'YEAR(sa.DateApplied) = ?',
                'MONTH(sa.DateApplied) = ?',
                'ss.Code = ?'
            );

            $params = array(
                $this->user->OrganizationID,
                $year,
                $month,
                $serviceCode
            );


            $serviceData   = $this->mgovdb->getRowObject('Service_Services', $serviceCode, 'Code');
            $orgData       = $this->mgovdb->getRowObject('Service_Organization', $serviceData->id, 'ServiceID');
            $extrafields   = $this->mgovdb->getRecords('Service_ExtraFormFields', array('ServiceID' => $serviceData->id));
            $records       = $this->statisticsdb->organizationCollectioDetails($where, $params);
            $fields = array();
            foreach ($extrafields as $r) {
                if (in_array($r['FieldType'], array(1,2,4))) {
                    $fields[$r['FieldID']] = trim($r['FieldLabel']);
                }
            }

            $viewData = array(
                'pageTitle'     => 'Organization - Collection Details',
                'accountInfo'   => user_account_details(),
                'nosidebar'     => true,
                'shownav'       => true,
                'serviceData'   => $serviceData,
                'orgData'       => $orgData,
                'fields'        => $fields,
                'records'       => $records,
                'jsModules'         => array(
                    'quickserve',
                ),
            );

            // print_data($records);

            view('reports/cttmo/collectiondetails', $viewData, 'templates/mgov');
        }
    }
}
