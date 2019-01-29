<?php 
defined('BASEPATH') or exit('No direct script access allowed');

/**
* MgovDB
* General model to do basic CRUD;
*/


class Mgovdb extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/*
	* return single row object
	*/
	public function getRowObject($table, $find, $field = 'id')
	{
		$q = $this->db->where($field, $find)->get($table);
		if ($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}

	/*
	* return single row object
	* multiple condition
	*/
	public function getRowObjectWhere($table, $where)
	{
		$q = $this->db->where($where)->get($table);
		if ($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}

	/**
	* basic table search
	* return result array
	*/
	public function getRecords($tableName, $where = false, $order = false, $limit = false)
	{

		if ($where != false) {
			$this->db->where($where);
		}

		if ($order != false) {
			$this->db->order_by($order);
		}

		if ($limit != false) {
			call_user_func_array(array($this->db, 'limit'), $limit);
		}

		return $this->db->get($tableName)->result_array();
		// echo $this->db->last_query();

	}

	/**
	* basic table search
	* return result array
	*/
	public function getCount($tableName, $where = false)
	{

		if ($where != false) {
			$this->db->where($where);
		}

		return 	$this->db->count_all_results($tableName);

	}

	/**
	* insert or update data
	*/
	public function saveData($table, $data)
	{

		$do_update = false;

		if (isset($data['id'])) {
			$q = $this->db->where('id', $data['id'])->get($table);
			if ($q->num_rows() > 0) {
				$do_update = true;
			} else {
				//record does not exists, return invalid update.
				return false;
			}
		}


		if ($do_update) {
			$id = $data['id'];
			unset($data['id']);
			$this->db
					->set($data)
					->where('id', $id)
					->update($table);

			return $this->db->affected_rows();
		} else {
			$this->db->insert($table, $data);
			return $this->db->insert_id();
		}

	}

	/**
	* delete single item
	*/
	public function deleteData($table, $id)
	{
		if (!empty($id)) {
			$this->db->where('id', $id);
			return $this->db->delete($table);
		}
		return false;
	}

	/*
	* return table columns
	*/
	public function tableColumns($table)
	{
		return $this->db->list_fields($table);
	}


	/**
	* get account info with other data
	*/
	public function getUserAccount($id, $field = 'id')
	{
		$this->db->select('uai.*, regDesc, provDesc, citymunDesc, brgyDesc,
							ual.LevelName AS AccountLevelName'
						);
		$this->db->from('UserAccountInformation AS uai');
		$this->db->join('UserAccountLevel AS ual', 'uai.AccountLevelID = ual.id', 'left');
		$this->db->join('UtilLocBrgy AS ulb', 'uai.BarangayID = ulb.brgyCode', 'left');
		$this->db->join('UtilLocCityMun AS ulc', 'uai.MunicipalityCityID = ulc.citymunCode', 'left');
		$this->db->join('UtilLocProvince AS ulp', 'uai.ProvincialID = ulp.provCode', 'left');
		$this->db->join('UtilLocRegion AS ulr', 'uai.RegionalID = ulr.regCode', 'left');

		$this->db->where('uai.' . $field, $id);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			$row = $q->row();
			unset($row->Password);
			return $row;
		}
		return false;
	}

	/**
	* paginate data
	*/
	public function getPaginationData($tableName, $limit, $start, $where = false, $order = false)
	{
		
		// GET COUNT
		if ($where != false) {
			$this->db->where($where);
		}

		$count 	= $this->db->count_all_results($tableName);

		// GET RESULTS DATA

		if ($where != false) {
			$this->db->where($where);
		}

		if ($order != false) {
			$this->db->order_by($order);
		}

		$data 	= $this->db->get($tableName, $limit, $start)->result();
		// echo $this->db->last_query();

		return array(
			'count'	=> $count,
			'data'	=> $data
		);
	}


	/**
	* transaction feeds
	*/
	public function getFeeds($where = array())
	{
		$query = "SELECT 
					sa.ServiceID,
					sa.ApplicantID,
					sa.Code ApplicationCode,
					sa.DepartmentID,
					sa.SubDepartmentID,
					sa.DateCompleted,
					sa.DateApplied,
					sa.id ApplicationID,
					ss.Name ServiceName,
					ss.Description,
					ss.Code ServiceCode,
					ss.Logo
				FROM Service_Applications AS sa
				JOIN Service_Services AS ss ON ss.id = sa.ServiceID";

		if (count($where)) {
			$query .= ' WHERE ' . implode(' AND ', $where);
		}

		$query .= " ORDER BY sa.DateCompleted DESC, sa.DateApplied DESC";

		$query .= " LIMIT 100";

		return $this->db->query($query)->result_array();
	}


	/**
	* get assigned function on officer
	* used on quickserve to get list of application functions that needs to process
	*/
	public function getAssignedFunction($where = array())
	{
		$query = "SELECT
					saf.id safID,
					saf.ApplicationID,
					saf.ApplicantID,
					saf.RequirementID,
					saf.Status safStatus,
					saf.DateAdded StartedTime,
					saf.DateCompleted EndedTime,
					ss.Name ServiceName,
					ss.LocationScopeID,
					ss.Fee,
					ss.ServiceType,
					uai.FirstName,
					uai.LastName,
					uai.MabuhayID,
					uai.BarangayID,
					uai.MunicipalityCityID,
					uai.ProvincialID,
					sfo.AccountID AssignedID,
					sr.DocumentID,
					dt.Name documentName,
					sa.Code ApplicationCode,
					sa.ServiceCode,
					sa.RequirementCount,
					sa.RequirementProcessedCount,
					sa.FunctionCount,
					sa.FunctionProcessedCount,
					sa.LastUpdate LastUpdate,
					sa.Status saStatus,
					sa.DateCompleted,
					sa.ExtraFields,
					sf.FuntionFor,
					sf.FunctionTypeID,
					lft.Value FunctionName
				FROM Service_FunctionOfficers AS sfo
				JOIN Service_Application_Functions AS saf ON sfo.FunctionID = saf.FunctionID
				JOIN Service_Applications AS sa ON saf.ApplicationID = sa.id
				JOIN Service_Services AS ss ON ss.id = saf.ServiceID
				JOIN UserAccountInformation AS uai ON uai.id = saf.ApplicantID
				JOIN Service_Functions AS sf ON sf.id = sfo.FunctionID
				JOIN Lookup_function_type AS lft ON sf.FunctionTypeID = lft.id
				LEFT JOIN Service_Requirements AS sr ON sr.id = saf.RequirementID
				LEFT JOIN Doc_Templates AS dt ON dt.id = sr.DocumentID";

		if (count($where)) {
			$query .= ' WHERE ' . implode(' AND ', $where);
		}

		$query .= " ORDER BY saf.Status ASC, saf.DateAdded ASC";

		return $this->db->query($query)->result_array();
	}

	/**
	* get service applications with service info
	*/
	public function getServiceApplications($where = false, $order = false)
	{

		$this->db->select('sa.id ID,
					sa.ApplicantID,
					sa.Code ApplicationCode,
					sa.ServiceCode,
					sa.LastUpdate LastUpdate,
					sa.Status Status,
					sa.DateCompleted,
					sa.DateApplied,
					ss.Name ServiceName,
					ss.Logo sLogo,
                    dd.id ddID,
                    dd.Name ddName,
                    dd.Logo ddLogo,
                    dc.id dcID,
                    dc.Name dcName,
                    dc.Logo dcLogo')
				->from('Service_Applications AS sa')
				->join('Service_Services AS ss', 'ss.id = sa.ServiceID', 'left')
				->join('Dept_Departments AS dd', 'dd.id = ss.DepartmentID', 'left')
				->join('Dept_ChildDepartment AS dc', 'dc.id = ss.SubDepartmentID', 'left outer');

		if ($where) {
			$this->db->where($where);
		}

		if ($order) {
			$this->db->order_by($order);
		}

		return $this->db->get()->result_array();
	}


	/**
	* get user organization services
	*/
	public function getOrganizationServices($params)
	{

		$keywordFilter = '';
		if (trim($params['keyword']) != '') {
			$keyword = trim($params['keyword']);
			$keywordFilter = 'AND (so.Keyword LIKE "%'.$keyword.'%" OR so.MenuName LIKE "%'.$keyword.'%" OR ss.Name LIKE "%'.$keyword.'%" OR ss.Description LIKE "%'.$keyword.'%")';
		}

		$sql = "SELECT ss.id,ss.Code,ss.Name,ss.Logo,COUNT(sa.id) AS Applications,so.MenuName,so.Category,so.Keyword FROM Service_Services ss
                JOIN UserAccountInformation ua ON (
                ua.id = ? AND (
                    (ss.LocationScopeID = 1) OR
                    (ss.RegionalID = ua.RegionalID AND ss.LocationScopeID = 2) OR 
                    (ss.ProvincialID = ua.ProvincialID AND ss.LocationScopeID = 3) OR
                    (ss.MunicipalityCityID = ua.MunicipalityCityID AND (ss.LocationScopeID = 4 OR ss.LocationScopeID = 5)) OR
                    (ss.BarangayID = ua.BarangayID AND ss.LocationScopeID = 6)
                    )
                )
                LEFT OUTER JOIN Service_Applications sa ON sa.ServiceID = ss.id
                LEFT JOIN Service_Organization so ON ss.id = so.ServiceID
                WHERE ss.deletedAt IS NULL
                AND ss.Status = 1
                AND ss.InOrganization = 1
                AND ss.SubDepartmentID = ?
                {$keywordFilter}
                GROUP BY ss.id
                ORDER BY Applications DESC";

        return $this->db->query($sql, array(
	        								$params['userID'],
	        								$params['organization']
	        							))->result_array();
	}


}