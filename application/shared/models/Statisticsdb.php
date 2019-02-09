<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Statisticsdb extends CI_Model {

	public function __construct() {
		parent::__construct();
	}


	public function organizationMonthlyApplication ($where, $params)
	{
		$and_where = '';
		if (count($where)) {
			$and_where .= ' AND ' . implode(' AND ', $where);
		}
		$query = "SELECT ss.id,ss.Code,ss.Name,ss.Logo,so.MenuName,so.Category,so.Keyword, MONTH(sa.DateApplied) month, COUNT(sa.id) AS applicationCount
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

}