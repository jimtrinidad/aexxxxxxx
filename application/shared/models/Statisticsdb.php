<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Statisticsdb extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function getServicesByID($ids)
	{
		$query = "SELECT ss.id,ss.Code,ss.Name,ss.Logo,so.MenuName,so.Category,so.Keyword
					FROM Service_Services ss
	                LEFT JOIN Service_Organization so ON ss.id = so.ServiceID
	                WHERE ss.deletedAt IS NULL
		                AND ss.Status = 1
		                AND ss.ServiceType = 13
		                AND ss.InOrganization = 1
		                AND ss.Code IN ($ids)
	                ORDER BY MenuName";

		return $this->db->query($query)->result_array();
	}

	/**
	* monthly violation reports
	*/
	public function organizationMonthlyViolationReport ($where, $params)
	{
		$and_where = '';
		if (count($where)) {
			$and_where .= ' AND ' . implode(' AND ', $where);
		}
		$query = "SELECT ss.id,ss.Code,ss.Name,ss.Logo,so.MenuName,so.Category,so.Keyword,GROUP_CONCAT(sa.AddedViolations) as added_violations,
						MONTH(sa.DateApplied) month, 
						COUNT(sa.id) AS applicationCount
					FROM Service_Applications sa
	                LEFT JOIN Service_Services ss ON sa.ServiceID = ss.id
	                LEFT JOIN Service_Organization so ON ss.id = so.ServiceID
	                WHERE ss.deletedAt IS NULL
		                AND ss.Status = 1
		                AND ss.ServiceType = 13
		                AND ss.InOrganization = 1
		                {$and_where}
	                GROUP BY ss.id, month
	                ORDER BY MenuName";

		return $this->db->query($query, $params)->result_array();
	}

	/**
	* monthly violation reports
	*/
	public function organizationDailyViolationReport ($where, $params)
	{
		$and_where = '';
		if (count($where)) {
			$and_where .= ' AND ' . implode(' AND ', $where);
		}
		$query = "SELECT ss.id,ss.Code,ss.Name,ss.Logo,so.MenuName,so.Category,so.Keyword,GROUP_CONCAT(sa.AddedViolations) as added_violations,
						DATE(sa.DateApplied) day, 
						COUNT(sa.id) AS applicationCount
					FROM Service_Applications sa
	                LEFT JOIN Service_Services ss ON sa.ServiceID = ss.id
	                LEFT JOIN Service_Organization so ON ss.id = so.ServiceID
	                WHERE ss.deletedAt IS NULL
		                AND ss.Status = 1
		                AND ss.ServiceType = 13
		                AND ss.InOrganization = 1
		                {$and_where}
	                GROUP BY ss.id, day
	                ORDER BY MenuName";

		return $this->db->query($query, $params)->result_array();
	}

	/**
	* collection report on completed transaction
	*/
	public function organizationCollectioReport($where, $params)
	{
        $and_where = '';
		if (count($where)) {
			$and_where .= ' AND ' . implode(' AND ', $where);
		}
		$query = "SELECT ss.id,ss.Code,ss.Name,ss.Logo,so.MenuName,so.Category,so.Keyword,GROUP_CONCAT(sa.AddedViolations) as added_violations,	
						MONTH(sa.DateApplied) month, 
						COUNT(sa.id) AS applicationCount, 
						SUM(sp.Total) AS Total
					FROM Service_Applications sa
	                LEFT JOIN Service_Services ss ON sa.ServiceID = ss.id
	                LEFT JOIN Service_Organization so ON ss.id = so.ServiceID
	                JOIN Service_Payments sp ON sp.ApplicationID = sa.id
	                WHERE ss.deletedAt IS NULL
		                AND ss.Status = 1
		                AND ss.ServiceType = 13
		                AND ss.InOrganization = 1
		                AND sa.Status = 2
		                {$and_where}
	                GROUP BY ss.id, month
	                ORDER BY MenuName";

		return $this->db->query($query, $params)->result_array();
	}

	public function organizationCollectioDetails($where, $params)
	{
        $and_where = '';
		if (count($where)) {
			$and_where .= ' AND ' . implode(' AND ', $where);
		}
		$query = "SELECT ss.id,ss.Code,ss.Name,ss.Logo,so.MenuName,so.Category,so.Keyword,sa.AddedViolations as added_violations,
						sa.id AS ApplicationID, sa.Code AS appCode,
						sp.id AS PaymentID, 
						sp.Total,sa.ExtraFields,uai.FirstName, uai.LastName, sa.DateApplied 
					FROM Service_Applications sa
	                LEFT JOIN Service_Services ss ON sa.ServiceID = ss.id
	                LEFT JOIN Service_Organization so ON ss.id = so.ServiceID
	                JOIN Service_Payments sp ON sp.ApplicationID = sa.id
	                JOIN UserAccountInformation AS uai ON uai.id = sa.ApplicantID
	                WHERE ss.deletedAt IS NULL
		                AND ss.Status = 1
		                AND ss.ServiceType = 13
		                AND ss.InOrganization = 1
		                AND sa.Status = 2
		                {$and_where}";

		return $this->db->query($query, $params)->result_array();
	}

	/**
	* yearly categorized violation report
	*/
	public function organizationYearlyCategorizeReport($where, $params)
	{
		$and_where = '';
		if (count($where)) {
			$and_where .= ' AND ' . implode(' AND ', $where);
		}
		$query = "SELECT so.Category, 
						MONTH(sa.DateApplied) month, 
						SUM(sa.Status = 2 || sa.Status = 0 || sa.Status = 3) application, 
						SUM(sa.Status = 2) as completed, 
						SUM(sa.Status = 0) as pending, 
						SUM(sa.Status = 3) as canceled,
						SUM(if(sa.Status = 2, sp.Total, 0)) as completedAmount,
                        SUM(if(sa.Status = 0, ss.Fee, 0)) as pendingAmount
					FROM Service_Applications sa
	                LEFT JOIN Service_Services ss ON sa.ServiceID = ss.id
	                LEFT JOIN Service_Organization so ON ss.id = so.ServiceID
	                LEFT JOIN Service_Payments sp ON sa.id = sp.ApplicationID
	                WHERE ss.deletedAt IS NULL
		                AND ss.Status = 1
		                AND ss.ServiceType = 13
		                AND ss.InOrganization = 1
		                {$and_where}
	                GROUP BY so.Category, month
	                ORDER BY so.Category, month";

	    return $this->db->query($query, $params)->result_array();
	}


	/**
	* get daily apprehension
	*/
	public function organizationDailyApprehension($where, $params)
	{
		$and_where = '';
		if (count($where)) {
			$and_where .= ' AND ' . implode(' AND ', $where);
		}
		$query = "SELECT ss.id AS ServiceID,ss.Code,ss.Name,so.MenuName,so.Category,sa.id AS reportid, sa.ExtraFields,uai.FirstName, uai.LastName, DATE(sa.DateApplied) AS dateapplied, sa.Status, sa.Code appCode, sp.collections, sp.Total, sa.AddedViolations as added_violations
					FROM Service_Applications sa
					LEFT JOIN Service_Services ss ON sa.ServiceID = ss.id
					LEFT JOIN Service_Organization so ON ss.id = so.ServiceID
					LEFT JOIN UserAccountInformation AS uai ON uai.id = sa.ApplicantID
					LEFT OUTER JOIN Service_Payments sp ON sp.ApplicationID = sa.id
					WHERE ss.deletedAt IS NULL
						AND ss.Status = 1
						AND ss.ServiceType = 13
						AND ss.InOrganization = 1
						AND sa.Status IN (0,2,3)
	                	{$and_where}
	                ORDER BY reportid";

	    return $this->db->query($query, $params)->result_array();
	}

}