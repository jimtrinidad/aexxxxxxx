<?php
ini_set('memory_limit', '-1');
defined('BASEPATH') or exit('No direct script access allowed');

class Statistics extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        // check_authentication();
    }

    public function govt_performance()
    {
        $viewData = array(
            'pageTitle'     => 'Government Performance Report',
            'accountInfo'   => user_account_details(),
            'nosidebar'     => true,
            'shownav'       => true,
        );

        view('main/govt_performance_report', $viewData, 'templates/mgov');
    }

    public function get_province_transactions()
    {
        if (get_post('provinceCode')) {

            $provCode       = get_post('provinceCode');
            $provinceInfo   = lookup_row('UtilLocProvince', $provCode, 'provCode');
            $services       = lookup_all('Service_Services', array('ProvincialID' => $provCode));
            $applications   = lookup_all('Service_Applications', array('ProvincialID' => $provCode));
            $breakdown      = $this->breakdown_transaction($applications);

            $proviceData    = array(
                'name'  => strtoupper($provinceInfo->provDesc),
                'code'  => $provinceInfo->provCode,
                'logo'  => logo_filename($provinceInfo->logo),
                'services'  => count($services),
                'counts'    => $breakdown,
                'cities'    => array()
            );

            if (($proviceData['services'] + $breakdown['transactions']) > 0) {

                // get provicial cities
                $municities       = lookup_all('UtilLocCityMun', array('provCode' => $provCode), 'citymunDesc', false);
                foreach ($municities as $municity) {

                    $cityCode       = $municity['citymunCode'];
                    $services       = lookup_all('Service_Services', array('MunicipalityCityID' => $cityCode));
                    $applications   = lookup_all('Service_Applications', array('MunicipalityCityID' => $cityCode));
                    $breakdown      = $this->breakdown_transaction($applications);

                    if ((count($services) + $breakdown['transactions']) > 0) {

                        $proviceData['cities'][$cityCode]    = array(
                            'name'  => strtoupper($municity['citymunDesc']),
                            'code'  => $cityCode,
                            'logo'  => logo_filename($municity['logo']),
                            'services'  => count($services),
                            'counts'    => $breakdown,
                            'barangay'  => array()
                        );

                        // get barangay
                        $barangays       = lookup_all('UtilLocBrgy', array('citymunCode' => $cityCode), 'brgyDesc', false);
                        foreach ($barangays as $barangay) {

                            $brgyCode       = $barangay['brgyCode'];
                            $services       = lookup_all('Service_Services', array('BarangayID' => $brgyCode));
                            $applications   = lookup_all('Service_Applications', array('BarangayID' => $brgyCode));
                            $breakdown      = $this->breakdown_transaction($applications);

                            if ((count($services) + $breakdown['transactions']) > 0) {

                                $proviceData['cities'][$cityCode]['barangay'][$brgyCode]    = array(
                                    'name'  => strtoupper($barangay['brgyDesc']),
                                    'code'  => $brgyCode,
                                    'logo'  => logo_filename($barangay['logo']),
                                    'services'  => count($services),
                                    'counts'    => $breakdown
                                );

                            }
                        }

                    }
                }

            }

            $return_data = array(
                'status'    => true,
                'data'      => $proviceData
            );

        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Provincial code is required.'
            );
        }

        response_json($return_data);
    }


    /**
    * segregate transaction counts
    */
    private function breakdown_transaction($applications)
    {
        $transactions   = 0; // service + service requirement count
        $pending        = 0;
        $processed      = 0; // total application process, (approved + denied)
        $approved       = 0;
        $denied         = 0;

        foreach ($applications as $application) {
            // 1 count for ervice
            $transactions++;
            // count requirements
            $transactions += $application['RequirementCount'];

            switch ($application['Status']) {
                case 0:
                    $pending++;
                    break;
                case 2:
                case 3:
                    $processed++;
                    if ($application['Status'] == 2) {
                        $approved++;
                    } else {
                        $denied++;
                    }
                    break;
            }
        }

        return array(
            'transactions'  => $transactions,
            'pending'       => $pending,
            'processed'     => $processed,
            'approved'      => $approved,
            'denied'        => $denied
        );

    }


    public function service_summary()
    {
        $viewData = array(
            'pageTitle'     => 'Service Summary Report',
            'accountInfo'   => user_account_details(),
            'nosidebar'     => true,
            'shownav'       => true,
        );

        $date = date('Y-m-01') . ' - ' . date('Y-m-d');

        $viewData['date'] = $date;

        view('main/service_summary_report', $viewData, 'templates/mgov');
    }

    public function get_service_summary_data()
    {
        if (get_post('service_category')) {
            if (get_post('service_date_range')) {
                $date = get_post('service_date_range');
            } else {
                $date = date('Y-m-01') . ' - ' . date('Y-m-d');
            }

            $category = get_post('service_category');

            $daterange = explode(' - ', $date);
            $datefrom  = $daterange[0] . ' 00:00:00';
            $dateto    = $daterange[1] . ' 23:59:59';

            if ($category == 'null') {
                $categoryQuery = '(CategoryID = 0 OR CategoryID IS NULL)';
            } else {
                $categoryQuery = 'CategoryID = ' . (int) $category;
            }

            $services       = $this->db->query("SELECT * 
                                                FROM Service_Services 
                                                WHERE deletedAt IS NULL
                                                AND ServiceType != 13
                                                AND $categoryQuery
                                                AND DateAdded BETWEEN ? AND ?", array($datefrom, $dateto))->result_array();

            $applications = array();
            $dept_services     = array();
            $dept_applications = array();
            foreach ($services as $service) {
                $service_app   = $this->db->query("SELECT * 
                                                FROM Service_Applications 
                                                WHERE deletedAt IS NULL
                                                AND ServiceID != ?
                                                AND DateApplied BETWEEN ? AND ?", array($service['id'], $datefrom, $dateto))->result_array();
                $applications  = array_merge($applications, $service_app);

                $dept_services[$service['DepartmentID']][] = $service;

                if (!isset($dept_applications[$service['DepartmentID']])) {
                    $dept_applications[$service['DepartmentID']] = array();
                }
                $dept_applications[$service['DepartmentID']] = array_merge($dept_applications[$service['DepartmentID']], $service_app);
            }
            $breakdown      = $this->breakdown_transaction($applications);

            $proviceData    = array(
                'name'  => $category == 'null' ? 'Uncategorized' : lookup('service_categories', $category),
                'code'  => $category,
                'logo'  => '',
                'services'  => count($services),
                'counts'    => $breakdown,
                'cities'    => array()
            );

            if (($proviceData['services'] + $breakdown['transactions']) > 0) {

                // get departments
                $departments       = $this->db->where_in('id', array_keys($dept_services))->get('Dept_Departments')->result_array();
                foreach ($departments as $department) {

                    $deptCode       = $department['UniqueCode'];
                    $services       = $dept_services[$department['id']];
                    $applications   = $dept_applications[$department['id']];
                    $breakdown      = $this->breakdown_transaction($applications);

                    if ((count($services) + $breakdown['transactions']) > 0) {

                        $proviceData['cities'][$deptCode]    = array(
                            'name'  => strtoupper($department['Name']),
                            'code'  => $deptCode,
                            'logo'  => logo_filename($department['Logo']),
                            'services'  => count($services),
                            'counts'    => $breakdown,
                            'barangay'  => array()
                        );

                        // // get barangay
                        // $barangays       = lookup_all('UtilLocBrgy', array('citymunCode' => $cityCode), 'brgyDesc', false);
                        // foreach ($barangays as $barangay) {

                        //     $brgyCode       = $barangay['brgyCode'];
                        //     $services       = lookup_all('Service_Services', array('BarangayID' => $brgyCode));
                        //     $applications   = lookup_all('Service_Applications', array('BarangayID' => $brgyCode));
                        //     $breakdown      = $this->breakdown_transaction($applications);

                        //     if ((count($services) + $breakdown['transactions']) > 0) {

                        //         $proviceData['cities'][$cityCode]['barangay'][$brgyCode]    = array(
                        //             'name'  => strtoupper($barangay['brgyDesc']),
                        //             'code'  => $brgyCode,
                        //             'logo'  => logo_filename($barangay['logo']),
                        //             'services'  => count($services),
                        //             'counts'    => $breakdown
                        //         );

                        //     }
                        // }

                    }
                }

            }

            $return_data = array(
                'status'    => true,
                'data'      => $proviceData
            );

         } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Service category required.'
            );
        }

        response_json($return_data);
    }

}
