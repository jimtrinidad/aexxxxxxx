<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function lookup($collection, $id = null)
{

	$ci = &get_instance();

	$items = $ci->config->item($collection);

    if ($items == null) {
        // get item from database
        $items = array();
        $table = 'Lookup_' . $collection;
        if ($ci->db->table_exists($table)) {

            $ci->db->where('Status', 1);
            $ci->db->order_by('Ordering');
            $results = $ci->db->get($table)->result();
            foreach ($results as $result) {
                $items[$result->id] = $result->Value;
            }

        }
    }
	
	if ($id != null) {

        if (is_array($id)) {
            $match = array();
            foreach ($id as $i) {
                if (isset($items[$i])) {
                    $match[] = $items[$i];
                }
            }
            return $match;
        } else if (isset($items[$id])) {
            return $items[$id];
        }

        return false;
    } else {
        return $items;
    }

}

function lookup_db($tableName, $fieldName, $id = null, $formatted = true, $options = array())
{

    $ci = &get_instance();

    if ($id != null) {
        $ci->db->where('id', $id);
        $q = $ci->db->get($tableName);
        if ($q->num_rows() > 0) {
            return $q->row()->{$fieldName};
        }
        return false;
    }

    if (is_array($options)) {
        foreach ($options as $k => $v) {
            if (is_array($v)) {
                $ins = $ci->db;
                @call_user_func_array(array($ins, $k), $v);
            } else {
                @$ci->db->{$order_by}($v);
            }
        }
    }

    $results = $ci->db->get($tableName)->result_array();
    if ($formatted === false) {
        return $results;
    }

    $items = array();
    foreach ($results as $result) {
        $items[$result['id']] = $result[$fieldName];
    }

    return $items;
}

function lookup_muni_city($id = null, $formatted = true, $options = false)
{
    if ($options == false) {
        // default order by name
        $options = array(
            'join'     => array('UtilLocProvince', 'UtilLocProvince.provCode = UtilLocCityMun.provCode'),
            'order_by' => array('provDesc ASC, citymunDesc ASC'),
        );
    }
    return lookup_db('UtilLocCityMun', 'citymunDesc', $id, $formatted, $options);
}

function lookup_barangay($id = null, $formatted = true, $options = false)
{
    if ($options == false) {
        // default order by name
        $options = array(
            'order_by' => array('brgyDesc'),
        );
    }
    return lookup_db('UtilLocBrgy', 'brgyDesc', $id, $formatted, $options);
}

function lookup_all($tableName, $where = false, $order = false, $exclude_deleted = true)
{
    $ci = &get_instance();
    if ($where !== false) {
        $ci->db->where($where);
    }

    if ($exclude_deleted === true) {
        $ci->db->where('deletedAt', NULL);
    }

    if ($order !== false) {
        $ci->db->order_by($order);
    }

    return $ci->db->get($tableName)->result_array();
}

/**
 * return single row object
 * use getRowObject from mgovph model
 */
function lookup_row($tableName, $find, $field = 'id', $select = false)
{
    $ci = &get_instance();
    $record = $ci->mgovdb->getRowObject($tableName, $find, $field);
    if (is_array($select) && $record) {
        $clean = array();
        foreach ($record as $key => $value) {
            if (in_array($key, $select)) {
                $clean[$key] = $value;
            }
        }
        return (object) $clean;
    } 
    return $record;

}

/**
 * get address name via id
 */
function lookup_address($codes)
{
    $address = array();

    if (isset($codes['RegionalID']) && $codes['RegionalID']) {
        $tableData = lookup_row('UtilLocRegion', $codes['RegionalID'], 'regCode');
        if ($tableData) {
            $address['Region'] = $tableData->regDesc;
        }
    }

    if (isset($codes['ProvincialID']) && $codes['ProvincialID']) {
        $tableData = lookup_row('UtilLocProvince', $codes['ProvincialID'], 'provCode');
        if ($tableData) {
            $address['Province'] = $tableData->provDesc;
        }
    }

    if (isset($codes['MunicipalityCityID']) && $codes['MunicipalityCityID']) {
        $tableData = lookup_row('UtilLocCityMun', $codes['MunicipalityCityID'], 'citymunCode');
        if ($tableData) {
            $address['MuniCity'] = $tableData->citymunDesc;
        }
    }

    if (isset($codes['BarangayID']) && $codes['BarangayID']) {
        $tableData = lookup_row('UtilLocBrgy', $codes['BarangayID'], 'brgyCode');
        if ($tableData) {
            $address['Barangay'] = $tableData->brgyDesc;
        }
    }

    return $address;
}


/**
* get locations by scope
*/
function lookup_scope_locations($scope)
{	
	$ci 	= &get_instance();
	$items 	= array();

	if ($scope == 1) {
		// National
	} elseif ($scope == 2) {
		// Regional
	    $items = lookup_db('UtilLocRegion', 'regDesc', null, false);
	} else if ($scope == 3) {
		// Provincial
		$options = array(
            'order_by' => array('provDesc'),
        );
		$items = lookup_db('UtilLocProvince', 'provDesc', null, false, $options);
	} else if ($scope == 4) {
		// City
		$options = array(
			'where'	   => array('type = 2'),
			'join'     => array('UtilLocProvince', 'UtilLocProvince.provCode = UtilLocCityMun.provCode'),
            'order_by' => array('provDesc ASC, citymunDesc ASC'),
        );
		$items = lookup_db('UtilLocCityMun', 'citymunDesc', null, false, $options);
	} else if ($scope == 5) {
		// Municipal
		$options = array(
			'where'	   => array('type = 1'),
			'join'     => array('UtilLocProvince', 'UtilLocProvince.provCode = UtilLocCityMun.provCode'),
            'order_by' => array('provDesc ASC, citymunDesc ASC'),
        );
		$items = lookup_db('UtilLocCityMun', 'citymunDesc', null, false, $options);
	} else if ($scope == 6) {
		// Barangay
		// Need city filter because too many results
		$options = array(
            'order_by' => array('brgyDesc'),
        );
		$items = lookup_db('UtilLocBrgy', 'brgyDesc', null, false, $options);
	}

	return $items;
}

/**
* get location break down by scope and location code 
*/
function lookup_get_location_breakdown($scope, $locCode)
{
    $ci     = &get_instance();
    $items  = array();

    if ($scope == 1) {
        // National
        $items['RegionalID'] = null;
        $items['ProvincialID'] = null;
        $items['MunicipalityCityID'] = null;
        $items['BarangayID'] = null;
    } elseif ($scope == 2) {
        // Regional
        $tableData = lookup_row('UtilLocRegion', $locCode, 'regCode');
        if ($tableData) {
            $items['RegionalID'] = $tableData->regCode;
            $items['ProvincialID'] = null;
            $items['MunicipalityCityID'] = null;
            $items['BarangayID'] = null;
        }
    } else if ($scope == 3) {
        // Provincial
        $tableData = lookup_row('UtilLocProvince', $locCode, 'provCode');
        if ($tableData) {
            $items['RegionalID'] = $tableData->regCode;
            $items['ProvincialID'] = $tableData->provCode;
            $items['MunicipalityCityID'] = null;
            $items['BarangayID'] = null;
        }
    } else if ($scope == 4 || $scope == 5) {
        // City || Municipal
        $tableData = lookup_row('UtilLocCityMun', $locCode, 'citymunCode');
        if ($tableData) {
            $items['RegionalID'] = $tableData->regCode;
            $items['ProvincialID'] = $tableData->provCode;
            $items['MunicipalityCityID'] = $tableData->citymunCode;
            $items['BarangayID'] = null;
        }
    } else if ($scope == 6) {
        // Barangay
        $tableData = lookup_row('UtilLocBrgy', $locCode, 'brgyCode');
        if ($tableData) {
            $items['RegionalID'] = $tableData->regCode;
            $items['ProvincialID'] = $tableData->provCode;
            $items['MunicipalityCityID'] = $tableData->citymunCode;
            $items['BarangayID'] = $tableData->brgyCode;
        }
    }

    return $items;
}


/**
* get deparment and child department on a single list
*/
function lookup_all_departments_and_offices($where = false, $order = false, $exclude_deleted = true)
{
    
    $departments = lookup_all('Dept_Departments', $where, $order, $exclude_deleted);

    $subwhere = $where;
    if (isset($subwhere['id'])) {
        $subwhere['DepartmentID'] = $subwhere['id'];
        unset($subwhere['id']);
    }

    $subdepartments = lookup_all('Dept_ChildDepartment', $subwhere, $order, $exclude_deleted);

    $all_deparments = array();
    $parent_depts   = array();
    foreach ($departments as $department) {
        $department['parent'] = 1;
        $all_deparments[] = $department;
        $parent_depts[$department['id']] = $department;
    }
    foreach ($subdepartments as $department) {
        $department['parent'] = 0;
        $department['parentData'] = (isset($parent_depts[$department['DepartmentID']]) ? $parent_depts[$department['DepartmentID']] : array());
        $all_deparments[] = $department;
    }

    return $all_deparments;
}


/**
* get department order by most applications
*/
function lookup_trending_departments()
{

    $ci =& get_instance();
    $sql = "SELECT d.id, d.Name, COUNT(sa.DepartmentID) AS ApplicationCount
            FROM Dept_Departments d
            LEFT OUTER JOIN Service_Applications sa ON d.id = sa.DepartmentID
            WHERE d.deletedAt IS NULL
            GROUP BY d.id
            ORDER BY ApplicationCount DESC, d.Name";

    return $ci->db->query($sql)->result_array();
}


/**
* organization category
*/
function lookup_organization_category($orgID, $catID = null)
{
    $ci = &get_instance();
    $raw = $ci->mgovdb->getRecords('OrganizationCategories', array('OrganizationID' => $orgID));

    $categories = array();
    foreach ($raw as $item) {

        if ($catID) {
            if ($item['id'] == $catID) {
                return $item['Name'];
            }
        }

        if ($item['Status'] == 1) {
            $categories[$item['id']] = $item['Name'];
        }
    }

    if ($catID) {
        return false; // no match on initial loop
    }
    
    return $categories;
}


/**
* find supplier/seller best match and lowest price
*/
function lookup_match_suppliers($item = '', $all = false)
{
    $ci =& get_instance();

    $sql = "SELECT * 
            FROM BusinessItems";

    $params = array();

    if ($all == false) {

        $sql .= ' WHERE Name LIKE ? 
                  OR Description LIKE ?';

        $key = $ci->db->escape_like_str($item);
        $params = array(
            "%{$key}%",
            "%{$key}%"
        );

    }

    $results = $ci->db->query($sql, $params)->result_array();
    foreach ($results as &$result) {
        $result['lev'] = levenshtein($item, $result['Name']);
    }
    usort($results, function($a, $b){
        if ($a['lev'] == $b['lev']) {
            return $a['Price'] > $b['Price'];
        } else {
            return $a['lev'] > $b['lev'];
        }
    });
    
    return $results;
}

function lookup_business_data($businessID, $include_seller_info = false)
{
    $ci =& get_instance();
    $business = $ci->mgovdb->getRowObject('Businesses', $businessID);
    if ($business) {
        $applicationData = $ci->mgovdb->getRowObject('Service_Applications', $business->Code, 'Code');
        if ($applicationData) {
            $exfields = json_decode($applicationData->ExtraFields, true);
            $data     = array(
                'Accredication No.' => $business->Code
            );
            foreach ($exfields as $key => $xf) {
                $fdetail = $ci->mgovdb->getRowObject('Service_ExtraFormFields', $key, 'FieldID');
                if ($fdetail) {
                    $data[$fdetail->FieldLabel] = $xf;
                }
            }

            if ($include_seller_info) {
                $userData = $ci->mgovdb->getRowObject('UserAccountInformation', $business->OwnerID);
                $data['sellerData'] = array(
                    'firstname'     => $userData->FirstName,
                    'lastname'      => $userData->LastName,
                    'mabuhayID'     => $userData->MabuhayID,
                    'contact'       => $userData->ContactNumber
                );
            }

            return $data;
        }
    }

    return false;
}

/**
* find domain data from zone setup
*/
function lookup_domain($domain)
{
    $ci = &get_instance();
    $record = $ci->mgovdb->getRowObject('PublicOffices', $domain, 'Domain');
    if ($record) {
        return (object) array(
            'psgc'    => $record->PSGC,
            'name'    => $record->Name,
            'domain'  => $record->Domain,
            'address' => $record->Address,
            'id'      => $record->id
        );
    }

    return false;
}

/**
* DBP billers
*/
function lookup_dbp_billers()
{
    $ci =& get_instance();
    $records = $ci->mgovdb->getOrganizationServices(array(
        'userID'        => current_user(),
        'organization'  => DBP_ORG_ID,
        'keyword'       => get_post('keyword')
    ));

    $grouped = array();
    foreach ($records as $record) {
        $record['Logo'] = logo_filename($record['Logo']);
        $grouped[$record['Category']][] = $record;
    }
    ksort($grouped);

    $results = array();
    $categories = lookup_organization_category(DBP_ORG_ID);
    foreach ($grouped as $cat => $items) {
        if (isset($categories[$cat])) {
            $results[] = array(
                'category'  => $categories[$cat],
                'items'     => $items
            );
        }
    }

    if (count($results)) {
        return $results;
    } 

    return false;
}



/**
* mpdf document page configuration
*/
function lookup_mpdf_config($document_id)
{

    $ci =& get_instance();
    $docData = $ci->mgovdb->getRowObject('Doc_Templates', $document_id);

    if ($docData) {
        $options = array(
            'mode'          => 'utf-8',
            'orientation'   => $docData->Orientation,
            'format'        => lookup('document_size', $docData->Size),
            'margin_left'   => $docData->Margin,
            'margin_right'  => $docData->Margin,
            'margin_top'    => $docData->Margin,
            'margin_bottom' => $docData->Margin,
            'margin_header' => $docData->Margin,
            'margin_footer' => $docData->Margin
        );

        if (!$options['format']) {
            $options['format'] = 'Letter';
        }
        // if document type is ID. do not break page
        if ($docData->Type == 12) {
            $options['autoPageBreak'] = false;
        }

    } else {
        $options = array(
            'mode'          => 'utf-8',
            'format'        => 'Letter', 
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => 10,
            'margin_bottom' => 10,
            'margin_header' => 10,
            'margin_footer' => 10
        );            
    }

    return $options;
}


/**
* lookup pre registered match from imported data
*/
function lookup_imported_items($params)
{
    $ci =& get_instance();
    $fn = $params['FirstName'];
    $ln = $params['LastName'];
    $mn = $params['MiddleName'];

    $sql = "SELECT * FROM migration_items
            WHERE Surname = ?
            AND Firstname = ?
            AND MiddleName = ?
            AND status = ?";

    $results = $ci->db->query($sql, array($ln,$fn,$mn, 1))->result_array();
    if (count($results)) {
        $ids      = array();
        $services = array();
        foreach ($results as $r) {
            // get group
            $group = $ci->mgovdb->getRowObject('migration_groups', $r['group_id']);
            if ($group) {
                if ($group->service) {
                    $services[] = $group->service;
                }

                $ids[] = $r['id'];
            }
        }

        if (count($ids)) {
            $r['services'] = $services;
            $r['ids']      = $ids;

            return $r;
        }
    }

    return false;
}