<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Servicesdb extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/**
	* new service transaction setup
	* with requirements, functions, and officers
	*/
	public function addNewService($data)
	{
		$this->db->trans_begin();

		$serviceData = $data['information'];

		// add service data
		$service_id = $this->mgovdb->saveData('Service_Services', $serviceData);


		// add extra form fields
		foreach ($data['extraFields'] as $item) {
			$this->mgovdb->saveData('Service_ExtraFormFields', array(
				'ServiceID'		=> $service_id,
				'FieldGroup'	=> $item['Group'],
				'FieldType'		=> $item['Type'],
				'FieldID'		=> $item['ID'],
				'FieldLabel'	=> $item['Label'],
				'Ordering'		=> $item['Ordering']
			));
		}


		// add main service function if there's any
		foreach ($data['mainFunctions'] as $item) {

			$function_id = $this->mgovdb->saveData('Service_Functions', array(
								'ServiceID'		=> $service_id,
								'FuntionFor'	=> 'Main',
								'FunctionTypeID'=> $item['Type'],
								'Description'	=> $item['Desc'],
								'Ordering'		=> $item['Ordering']
							));

			// assign function officer
			foreach ($item['Officer'] as $officerID) {
				$this->mgovdb->saveData('Service_FunctionOfficers', array(
					'ServiceID'		=> $service_id,
					'AccountID'		=> $officerID,
					'FunctionID'	=> $function_id
				));
			}
		}


		// add requirements and its function & officers
		foreach($data['requirements'] as $item) {
			$requirement_id = $this->mgovdb->saveData('Service_Requirements', array(
									'ServiceID'		=> $service_id,
									'DocumentID'	=> $item['DocID'],
									'Description'	=> $item['Desc'],
									'Ordering'		=> $item['Ordering']
								));
			// add function
			foreach ($item['Function'] as $function) {

				$function_id = $this->mgovdb->saveData('Service_Functions', array(
									'ServiceID'		=> $service_id,
									'FuntionFor'	=> 'Requirement',
									'RequirementID'	=> $requirement_id,
									'FunctionTypeID'=> $function['Type'],
									'Description'	=> $function['Desc'],
									'Ordering'		=> $function['Ordering']
								));

				// assign function officer
				foreach ($function['Officer'] as $officerID) {
					$this->mgovdb->saveData('Service_FunctionOfficers', array(
						'ServiceID'		=> $service_id,
						'RequirementID'	=> $requirement_id,
						'AccountID'		=> $officerID,
						'FunctionID'	=> $function_id
					));
				}
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}

	}


	public function addNewApplication($data)
	{
		$this->db->trans_begin();

		// add service application data
		$application_id = $this->mgovdb->saveData('Service_Applications', $data['application']);


		// add application requirement data
		foreach ($data['requirements'] as $item) {
			$item['ApplicationID'] = $application_id;
			$this->mgovdb->saveData('Service_Application_Requirements', $item);
		}


		// add initial function needed for application
		$functionData = $data['function'];
		$functionData['ApplicationID'] = $application_id;
		$this->mgovdb->saveData('Service_Application_Functions', $functionData);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

}