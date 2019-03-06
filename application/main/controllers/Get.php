<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Get extends CI_Controller
{

    public function barangay()
    {

    	$this->db->where('citymunCode', $this->input->get('citymunCode'));

    	$this->db->order_by('brgyDesc', 'ASC');

    	$items = $this->db->get('UtilLocBrgy')->result_array();

    	if (count($items)) {
    		$return_data = array(
    				'status'	=> true,
    				'data'		=> $items
    			);
    	} else {
    		$return_data = array(
    				'status'	=> false,
    				'message'	=> 'No record found.'
    			);
    	}

    	response_json($return_data);

    }

    public function provinces()
    {

        $this->db->where('regCode', $this->input->get('regCode'));

        $this->db->order_by('provDesc', 'ASC');

        $items = $this->db->get('UtilLocProvince')->result_array();

        if (count($items)) {
            $return_data = array(
                    'status'    => true,
                    'data'      => $items
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
    * transaction feeds
    * ads feeds (to be added)
    */ 
    public function feeds()
    {
        // require authentication
        check_authentication();

        $where = array(
            'sa.Status = 2'
        );

        if (get_post('latest')) {
            $where[] = 'sa.DateCompleted > "' . date('Y-m-d H:i:s', get_post('latest')) . '"';
        }

        if (get_post('department')) {
            $where[] = 'ss.DepartmentID = "' . (int) get_post('department') . '"';
        }

        if (get_post('locScope')) {
            $where[] = 'ss.LocationScopeID = "' . (int) get_post('locScope') . '"';
        }

        if (get_post('keyword')) {
            $keyword = $this->db->escape_like_str(get_post('keyword'));
            $where[] = '(ss.Name LIKE "%'.$keyword.'%" OR ss.Description LIKE "%'.$keyword.'%")';   
        }

        $raw_feeds = $this->mgovdb->getFeeds($where);

        $feeds = array();
        $providedCounts = array();

        foreach ($raw_feeds as $v) {

            $userData       = user_account_details($v['ApplicantID'], 'id');

            $v['userFullname']  = user_full_name($userData, '');
            $v['userAddress']   = user_full_address($userData);
            $v['userAvatar']    = public_url('assets/profile/') . photo_filename($userData->Photo);
            if ($v['SubDepartmentID']) {
                $v['departmentName']    = lookup_db('Dept_ChildDepartment', 'Name', $v['SubDepartmentID']);
            } else {
                $v['departmentName']    = lookup_db('Dept_Departments', 'Name', $v['DepartmentID']);
            }
            $v['serviceDate']   = date('F d, Y', strtotime($v['DateCompleted']));
            $v['Logo']          = public_url('assets/logo/') . logo_filename($v['Logo']);
            $v['serviceQR']     = public_url('assets/qr/') . get_qr_file($v['ServiceCode'], 4);

            $providedCounts[$v['DepartmentID']] = get_department_service_provided($v['DepartmentID'], $providedCounts);
            $v['serviceProvided'] = number_format($providedCounts[$v['DepartmentID']]);
            $v['serviceProvider'] = get_service_providers($v['ServiceID'], true);

            $feeds[] = $v;

        }

        if (count($feeds)) {
            response_json(array(
                'status'    => true,
                'timestamp' => time(),
                'data'      => $feeds
            ));
        } else {
            response_json(array(
                'status'    => false,
                'message'   => 'No new items found.'
            ));
        }

    }


    /**
    * services 
    */
    public function services()
    {

        // require authentication
        check_authentication();

        $accountData = user_account_details();

        $services = array();

        // get department
        $where      = array();
        $subWhere   = array();
        if (get_post('department')) {
            $where['id'] = (int) get_post('department');
        }

        $all_deparments = lookup_all_departments_and_offices($where, 'Name');
        
        $keyword_search = false;
        $location_search = false;
        if (get_post('keyword')) {
            $keyword = $this->db->escape_like_str(get_post('keyword'));
            $keyword_search = '(Name LIKE "%'.$keyword.'%" OR Description LIKE "%'.$keyword.'%")'; 
        }

        if (get_post('locScope')) {
            $location_search = 'LocationScopeID = "' . (int) get_post('locScope') . '"';
        }

        $providedCounts = array();

        // get active services by department
        foreach ($all_deparments as $department) {

            if ($department['parent'] == 1) {
                $where = array(
                    'deletedAt IS NULL',
                    'Status = 1',
                    'InOrganization = 0',
                    'DepartmentID = ' . $department['id'],
                    'SubDepartmentID = 0'
                );
            } else {
                $where = array(
                    'deletedAt IS NULL',
                    'Status = 1',
                    'InOrganization = 0',
                    'DepartmentID = ' . $department['DepartmentID'],
                    'SubDepartmentID = ' . $department['id'],
                );
            }

            if ($keyword_search) {
                $where[] = $keyword_search;
            }
            if ($location_search) {
                $where[] = $location_search;
            }

            if (get_post('code')) {
                $where[] = 'Service_Services.Code = "' . get_post('code') . '"';
            }

            $where = implode(' AND ', $where);
            $department_services = $this->mgovdb->getRecords('Service_Services', $where, 'DateAdded DESC');

            if (count($department_services)) {
                $clean_services = array();
                foreach ($department_services as $department_service) {
                    if ($department_service['LocationScopeID'] == 6) {
                        if ($department_service['LocationCode'] == $accountData->BarangayID) {
                            $clean_services[] = $department_service;
                        }
                    } else if ($department_service['LocationScopeID'] == 5 || $department_service['LocationScopeID'] == 4) {
                        if ($department_service['LocationCode'] == $accountData->MunicipalityCityID) {
                            $clean_services[] = $department_service;
                        }
                    } else if ($department_service['LocationScopeID'] == 3) {
                        if ($department_service['LocationCode'] == $accountData->ProvincialID) {
                            $clean_services[] = $department_service;
                        }
                    } else if ($department_service['LocationScopeID'] == 2) {
                        if ($department_service['LocationCode'] == $accountData->RegionalID) {
                            $clean_services[] = $department_service;
                        }
                    } else {
                        // national, always included
                        $clean_services[] = $department_service;
                    }
                }

                foreach ($clean_services as &$clean_service) {
                    $clean_service['AddressInfo']       = array_values(array_filter(lookup_address($clean_service)));
                    $clean_service['serviceProvider']   = get_service_providers($clean_service['id']);
                    $clean_service['Logo']              = public_url('assets/logo/') . logo_filename($clean_service['Logo']);
                    foreach (array('Limit','CycleInterval','Completed','CreatorID','ApproverID','DateAdded','LastUpdate','deletedAt') as $field) {
                        unset($clean_service[$field]);
                    }
                }

                usort($clean_services, function($a, $b) {
                    if ($a['Name'] == $b['Name']) {
                        return $a['LocationCode'] > $b['LocationCode'];
                    } else {
                        return $a['Name'] > $b['Name'];
                    }
                });

                if (count($clean_services)) {

                    unset($department['LastUpdate']);
                    unset($department['deletedAt']);
                    unset($department['deletedBy']);

                    $department['services'] = $clean_services;

                    if ($department['parent'] == 1) {
                        $providedCounts[$department['id']] = get_department_service_provided($department['id'], $providedCounts);
                        $department['serviceProvided'] = number_format($providedCounts[$department['id']]);
                        $department['Logo']     = public_url('assets/logo/') . logo_filename($department['Logo']);
                    } else {
                        $providedCounts[$department['DepartmentID']] = get_department_service_provided($department['DepartmentID'], $providedCounts);
                        $department['serviceProvided'] = number_format($providedCounts[$department['DepartmentID']]);
                        $department['Logo']     = public_url('assets/logo/') . logo_filename($department['parentData']['Logo']);
                    }
                    $services[] = $department;

                }
            }

            usort($services, function($a, $b){
                return $a['Name'] > $b['Name'];
            });

        }

        if (count($services)) {
            response_json(array(
                'status'    => true,
                'data'      => $services
            ));
        } else {
            response_json(array(
                'status'    => false,
                'message'   => 'No service found.'
            ));
        }

    }


    /**
    * load image
    */
    public function i($key = '', $value = '')
    {
        $file = PUBLIC_DIRECTORY . 'resources/images/image-placeholder.png';
        header("Content-Type: image/png");
        header("Content-Length: " . filesize($file));
        readfile($file);
    }

    /**
    * open application requirement doc on pdf
    */
    public function application_doc($code, $userid = null)
    {
        check_authentication();

        $doc = $this->mgovdb->getRowObject('Service_Application_Requirements', $code, 'Code');
        if ($doc && $doc->DocumentDraft) {

            if (!$userid) {
                $userid = current_user();
            }

            if ($doc->ApplicantID == $userid) {

                $userData = user_account_details($userid, 'id', false);

                $this->load->library('qr/ciqrcode', array(
                    'cachedir'  => APPPATH . 'cache/',
                    'errorlog'  => APPPATH . 'logs/'
                ));

                $qrparams['data']   = $code;
                $qrparams['level']  = 'H';
                $qrparams['size']   = 2;
                $qrparams['savename'] = tempnam(sys_get_temp_dir(), 'qr');
                $this->ciqrcode->generate($qrparams);

                $filename = strtoupper(user_full_name($userData, 0) . ' - ' . lookup_db('Doc_Templates', 'Name', $doc->DocumentID));

                $mpdf = new \Mpdf\Mpdf(array('format' => 'Letter', 'mode' => 'utf-8', 'margin_footer' => 2));

                $mpdf->SetWatermarkText('DRAFT');
                $mpdf->showWatermarkText = true;

                $content = str_replace("\xc2\xa0",'&nbsp;',$doc->DocumentDraft);

                $mpdf->WriteHTML('<style>@page {margin: 30px;}</style>' . $content);
                $mpdf->SetHTMLFooter('
                        <table width="100%" style="font-size:8px;">
                            <tr>
                                <td width="70%" align="left" valign="bottom">This is a system generated document. If you have any question, ask the nearest officer in your area.</td>
                                <td width="30%" align="right"><img style="width:60px;height:60px;" src="'.$qrparams['savename'].'"></td>
                            </tr>
                        </table>');

                
                // overwrite html title
                $mpdf->SetTitle($filename);
                $mpdf->SetSubject($filename);
                $mpdf->SetAuthor('MGOVPH');
                $mpdf->SetCreator('MGOVPH');

                $mpdf->Output($filename . '.pdf', 'I');

            } else {
                show_404();
            }

        } else {
            show_404();
        }
    }



    /**
    * get govt transactions performanace ranking
    */
    public function performance_ranking()
    {
        // transaction count = service + service applications + service application requirements
        // department and city

        // department counter
        $all_deparments         = lookup_all_departments_and_offices();
        $department_with_counts = array();

        foreach ($all_deparments as $department) {

            $transaction_count = 0;
            // get services
            if ($department['parent'] == 1) {
                $where = array(
                    'DepartmentID'      => $department['id'],
                    'SubDepartmentID'   => 0
                );
            } else {
                $where = array(
                    'DepartmentID'      => $department['DepartmentID'],
                    'SubDepartmentID'   => $department['id']
                );
            }
            $department_services = lookup_all('Service_Services', $where);
            foreach ($department_services as $service) {

                // 1 count per service
                $transaction_count++;

                // get service applications
                $service_applications = lookup_all('Service_Applications', array('ServiceID' => $service['id']), false);

                foreach ($service_applications as $service_application) {
                    // 1 count per service application
                    $transaction_count++;

                    // each requirements has 1 count
                    $transaction_count += $service_application['RequirementCount'];
                }

            }

            if ($transaction_count > 0) {
                $department_with_counts[] = array(
                    'parent'    => $department['parent'],
                    'id'        => $department['id'],
                    'name'      => $department['Name'],
                    'code'      => trim(preg_replace("/\([^)]+\)/","", $department['Code'])),
                    'logo'      => logo_filename($department['Logo']),
                    'count'     => $transaction_count
                );
            }

        }

        usort($department_with_counts, function($a, $b) {
            if ($a['count'] == $b['count']) {
                return 0;
            }
            return ($a['count'] > $b['count']) ? -1 : 1;
        });


        // city counter
        $city_with_counts   = array();

        $city_service_counts = $this->db->query('SELECT COUNT(*) AS count, MunicipalityCityID
                                                    FROM Service_Services
                                                    WHERE MunicipalityCityID IS NOT NULL
                                                    AND deletedAt IS NULL
                                                    GROUP BY MunicipalityCityID')->result_array();

        foreach ($city_service_counts as $city_service_count) {
            $city_with_counts[$city_service_count['MunicipalityCityID']] = $city_service_count['count'];
        }

        $city_application_counts = $this->db->query('SELECT COUNT(*) AS applicationCount, SUM(RequirementCount) AS requirementsCount, MunicipalityCityID
                                                    FROM Service_Applications
                                                    WHERE MunicipalityCityID IS NOT NULL
                                                    AND deletedAt IS NULL
                                                    GROUP BY MunicipalityCityID')->result_array();
        foreach ($city_application_counts as $city_application_count) {
            if (isset($city_with_counts[$city_application_count['MunicipalityCityID']])) {
                $city_with_counts[$city_application_count['MunicipalityCityID']] += ($city_application_count['applicationCount'] + $city_application_count['requirementsCount']);
            } else {
                $city_with_counts[$city_application_count['MunicipalityCityID']] = ($city_application_count['applicationCount'] + $city_application_count['requirementsCount']);
            }
        }

        foreach ($city_with_counts as $code => $count) {
            $cityInfo = lookup_row('UtilLocCityMun', $code, 'citymunCode');
            $city_with_counts[$code] = array(
                'code'  => $code,
                'count' => $count,
                'name'  => $cityInfo->citymunDesc,
                'logo'  => logo_filename($cityInfo->logo),
                'type'  => $cityInfo->type
            );
        }

        usort($city_with_counts, function($a, $b) {
            if ($a['count'] == $b['count']) {
                return 0;
            }
            return ($a['count'] > $b['count']) ? -1 : 1;
        });

        response_json(array(
            'status'    => true,
            'data'      => array(
                    'department'    => array_slice($department_with_counts, 0, 10), // limit 10
                    'city'          => array_slice($city_with_counts, 0, 10)
                )
        ), 30);

    }

    /**
    * get trending* services for user
    */
    public function get_trending_services()
    {
        if (isGuest()) {
            // only get national
            $sql = "SELECT ss.id,ss.Code,ss.Name,COUNT(sa.id) AS Applications FROM Service_Services ss
                    LEFT OUTER JOIN Service_Applications sa ON sa.ServiceID = ss.id
                    WHERE ss.deletedAt IS NULL
                    AND ss.LocationScopeID = 1
                    AND ss.Status = 1
                    AND ss.InOrganization = 0
                    GROUP BY ss.id
                    ORDER BY Applications DESC
                    LIMIT 10";
        } else {
            $userID = current_user();
            $sql = "SELECT ss.id,ss.Code,ss.Name,COUNT(sa.id) AS Applications FROM Service_Services ss
                    JOIN UserAccountInformation ua ON (
                    ua.id = {$userID} AND (
                        (ss.LocationScopeID = 1) OR
                        (ss.RegionalID = ua.RegionalID AND ss.LocationScopeID = 2) OR 
                        (ss.ProvincialID = ua.ProvincialID AND ss.LocationScopeID = 3) OR
                        (ss.MunicipalityCityID = ua.MunicipalityCityID AND (ss.LocationScopeID = 4 OR ss.LocationScopeID = 5)) OR
                        (ss.BarangayID = ua.BarangayID AND ss.LocationScopeID = 6)
                        )
                    )
                    LEFT OUTER JOIN Service_Applications sa ON sa.ServiceID = ss.id
                    WHERE ss.deletedAt IS NULL
                    AND ss.Status = 1
                    AND ss.InOrganization = 0
                    GROUP BY ss.id
                    ORDER BY Applications DESC
                    LIMIT 10";
        }

        $results = $this->db->query($sql)->result_array();
        if (count($results)) {
            response_json(array(
                'status'    => true,
                'data'      => $results
            ));
        } else {
            response_json(array(
                'status'    => false,
                'message'   => 'No service found.'
            ));
        }
    }

    /**
    * get user organitation ranking
    */
    public function organization_report_rank()
    {

        check_authentication();

        $all = get_post('all');

        $user = $this->mgovdb->getRowObject('UserAccountInformation', current_user(), 'id');

        if ($user->OrganizationID) {

            $organizationData = $this->mgovdb->getRowObject('Dept_ChildDepartment', $user->OrganizationID);

            $sql = "SELECT COUNT(sa.id) AS Applications, ua.FirstName, ua.LastName FROM Service_Applications sa
                    JOIN UserAccountInformation ua ON sa.ApplicantID = ua.id
                    LEFT JOIN Service_Services ss ON sa.ServiceID = ss.id
                    LEFT JOIN Service_Organization so ON ss.id = so.ServiceID
                    WHERE ss.deletedAt IS NULL
                    AND sa.Status = 2
                    AND ss.InOrganization = 1
                    AND ss.SubDepartmentID = ?
                    GROUP BY ua.id
                    ORDER BY Applications DESC";

            if (is_null($all)) {
                $sql .= " LIMIT 20";
            }

            $results = $this->db->query($sql, array($user->OrganizationID))->result_array();

            if (count($results)) {
                response_json(array(
                    'status'    => true,
                    'name'      => $organizationData->Name,
                    'data'      => $results
                ));
            } else {
                response_json(array(
                    'status'    => false,
                    'message'   => 'No record found.'
                ));
            }

        } else {
            response_json(array(
                'status'    => false,
                'message'   => 'No organization found.'
            ));
        }
    }


    /**
    * get currency conversion
    */
    public function currencies()
    {
        $url = 'https://api.exchangeratesapi.io/latest?base=PHP';
        $response = json_decode(file_get_contents($url), true);
        if (isset($response['rates'])) {
            $items = array();
            foreach ($response['rates'] as $cur => $rate) {
                if ($cur != 'PHP' && ($lbl = lookup('currencies', $cur))) {
                    $items[$cur] = array(
                        'name'  => $lbl,
                        'rate'  => bcdiv($rate,1, 5),
                        'toPHP' => bcdiv(1, $rate, 5)
                    );
                }
            }
            if (count($items)) {
                uasort($items, function($a, $b) {
                    return $a['name'] > $b['name'];
                });
                response_json(array(
                    'status'    => true,
                    'date'      => $response['date'],
                    'data'      => $items
                ), 720); //cache result
            } else {
                response_json(array(
                    'status'    => false,
                    'message'   => 'No currency data found.'
                ));
            }
        } else {
            response_json(array(
                'status'    => false,
                'message'   => 'No currency conversion found.'
            ));
        }
    }

}