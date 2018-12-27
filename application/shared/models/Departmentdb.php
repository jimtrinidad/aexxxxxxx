<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Departmentdb extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function getDepartment($keyword = false, $showDeleted = false)
	{
		$query = "SELECT * FROM Dept_Departments ";
		$where = "";

		if (!$showDeleted) {
			$notdeleted = "deletedAt IS NULL ";
			$where .= $notdeleted;
		}

		if ($keyword) {
			$like = "(name LIKE '%{$keyword}%' OR code LIKE '%{$keyword}%')";
			if ($where != '') {
				$where .= ' AND ';
			}
			$where .= '(' . $like;
			$where .= " OR id IN (SELECT DepartmentID 
					              FROM Dept_ChildDepartment 
					              WHERE {$like}";
					    if (!$showDeleted) {
					    	$where .= " AND " . $notdeleted;
					    }
			$where .= "))"; // in closing and or group closing

		}

		if ($where != '') {
			$query .= 'WHERE ' . $where;
		}

		$query .= ' ORDER BY Name ASC';
		return $this->db->query($query)->result_array();
	}


	public function getChildDepartment($departmentID = false, $keyword = false, $showDeleted = false)
	{
		$query = "SELECT * FROM Dept_ChildDepartment ";
		$where = "";

		if ($departmentID !== false) {
			$where .= "DepartmentID = {$departmentID} ";
		}

		if (!$showDeleted) {
			$notdeleted = "deletedAt IS NULL ";
			if ($where != '') {
				$where .= ' AND ';
			}
			$where .= $notdeleted;
		}

		if ($keyword) {
			$like = "(name LIKE '%{$keyword}%' OR code LIKE '%{$keyword}%')";
			if ($where != '') {
				$where .= ' AND ';
			}
			$where .= $like;

		}

		if ($where != '') {
			$query .= 'WHERE ' . $where;
		}

		$query .= ' ORDER BY DepartmentID ASC, Type ASC';
		return $this->db->query($query)->result_array();
	}


	public function getDepartmentOfficer($value, $key = 'DepartmentLocationID')
	{
		$this->db->select('do.*, RegistrationID, MabuhayID, FirstName, MiddleName, LastName, EmailAddress, ContactNumber, Photo, AccountTypeID, AccountLevelID');
		$this->db->from('Dept_Officers AS do');
		$this->db->join('UserAccountInformation AS uai', 'uai.id = do.AccountID');

		$this->db->where('do.' . $key, $value);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			return $q->result_array();
		}
		return false;
	}

}