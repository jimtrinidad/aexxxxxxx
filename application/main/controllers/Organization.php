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

        $organizationData->Setup = $this->mgovdb->getRowObject('Dept_OrganizationSetup', $organizationData->UniqueCode, 'UniqueCode');

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

    /**
    * monthly violations report for the whole year
    */
    public function monthlyreports()
    {   

        $this->load->model('statisticsdb');

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
            'pageTitle'     => 'Organization - Monthly Reports',
            'accountInfo'   => user_account_details(),
            'nosidebar'     => true,
            'shownav'       => true,
            'reportData'    => $report_data
        );

        view('reports/organization/monthly', $viewData, 'templates/mgov');
    }

    public function yearlyreports()
    {

        $this->load->model('statisticsdb');

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

        view('reports/organization/yearly', $viewData, 'templates/mgov');
    }

    public function dailyreport()
    {
        $this->load->model('statisticsdb');

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

        view('reports/organization/daily', $viewData, 'templates/mgov');
    }
}
