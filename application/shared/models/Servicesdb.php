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


	/**
	* update service and its components
	*/
	public function updateService($data)
	{
		$this->db->trans_begin();

		$serviceID   = $data['ServiceID'];
		$serviceData = $data['information'];

		// update service data
		if ($this->mgovdb->saveData('Service_Services', $serviceData)) {

			// EXTRA FIELDS
			
			// 1) delete removed fields
			// if current not in new list, remove
			$current_extraFields     = $this->mgovdb->getRecords('Service_ExtraFormFields', array('ServiceID' => $serviceID));
			foreach ($current_extraFields as $item) {
				if (!array_key_exists($item['FieldID'], $data['extraFields'])) {
					$this->mgovdb->deleteData('Service_ExtraFormFields', $item['id']);
				}
			}
			// 2) update or add
			foreach ($data['extraFields'] as $fieldID => $item) {
				$existing = $this->mgovdb->getRowObject('Service_ExtraFormFields', $fieldID, 'FieldID');
				if ($existing) {
					$fieldData = array(
						'id'			=> $existing->id,
						'FieldGroup'	=> $item['Group'],
						'FieldType'		=> $item['Type'],
						'FieldLabel'	=> $item['Label'],
						'Ordering'		=> $item['Ordering']
					);
				} else {
					$fieldData = array(
						'ServiceID'		=> $serviceID,
						'FieldGroup'	=> $item['Group'],
						'FieldType'		=> $item['Type'],
						'FieldID'		=> $item['ID'],
						'FieldLabel'	=> $item['Label'],
						'Ordering'		=> $item['Ordering']
					);
				}
				$this->mgovdb->saveData('Service_ExtraFormFields', $fieldData);
			}
			// END EXTRA FIELDS

			// MAIN FUNCTIONS
			$mainfunctiontypes = array();
			$current_mainFunctions = $this->mgovdb->getRecords('Service_Functions', array('ServiceID' => $serviceID, 'FuntionFor' => 'Main'));

			// update or delete
			foreach ($data['mainFunctions'] as $itemID => $item) {
				$mainfunctiontypes[$item['Type']] = $item;
				$q = $this->db->where(array('ServiceID'		 => $serviceID,
											'FuntionFor'	 => 'Main',
											'FunctionTypeID' => $item['Type']
										))->get('Service_Functions');

				if ($q->num_rows() > 0) {

					$existing = $q->row();
					$this->mgovdb->saveData('Service_Functions', array(
										'id'			=> $existing->id,
										'Description'	=> $item['Desc'],
										'Ordering'		=> $item['Ordering']
									));

					//delete officer
					$current_officers     = $this->mgovdb->getRecords('Service_FunctionOfficers', array('ServiceID' => $serviceID, 'FunctionID' => $existing->id));
					$current_officers_id  = array();
					foreach ($current_officers as $current_officer) {
						$current_officers_id[] = $current_officer['AccountID'];
						if (!in_array($current_officer['AccountID'], $item['Officer'])) {
							$this->mgovdb->deleteData('Service_FunctionOfficers', $current_officer['id']);
						}
					}
					// add not existing officer
					foreach ($item['Officer'] as $officerID) {
						if (!in_array($officerID, $current_officers_id)) {
							$this->mgovdb->saveData('Service_FunctionOfficers', array(
								'ServiceID'		=> $serviceID,
								'AccountID'		=> $officerID,
								'FunctionID'	=> $existing->id
							));
						}
					}

				} else {
					$function_id = $this->mgovdb->saveData('Service_Functions', array(
										'ServiceID'		=> $serviceID,
										'FuntionFor'	=> 'Main',
										'FunctionTypeID'=> $item['Type'],
										'Description'	=> $item['Desc'],
										'Ordering'		=> $item['Ordering']
									));

					// assign function officer
					foreach ($item['Officer'] as $officerID) {
						$this->mgovdb->saveData('Service_FunctionOfficers', array(
							'ServiceID'		=> $serviceID,
							'AccountID'		=> $officerID,
							'FunctionID'	=> $function_id
						));
					}
				}

			}

			// delete not on new list
			// not existing FunctionTypeID
			foreach ($current_mainFunctions as $item) {
				if (!array_key_exists($item['FunctionTypeID'], $mainfunctiontypes)) {
					// remove item and officer records
					$this->db->where('FunctionID', $item['id'])->delete('Service_FunctionOfficers');
					$this->db->where('id', $item['id'])->delete('Service_Functions');
				}
			}
			// END MAIN FUNCTIONS


			// REQUIREMENTS
			$current_requirements = $this->mgovdb->getRecords('Service_Requirements', array('ServiceID' => $serviceID));
			$documents_req = array();
			foreach($data['requirements'] as $item) {
				$documents_req[] = $item['DocID'];

				$q = $this->db->where(array('ServiceID'		 => $serviceID,
											'DocumentID'	 => $item['DocID']
										))->get('Service_Requirements');

				if ($q->num_rows() > 0) {

					$existingreq = $q->row();
					// UPDATE REQUIREMENT
					$this->mgovdb->saveData('Service_Requirements', array(
											'id'			=> $existingreq->id,
											'Description'	=> $item['Desc'],
											'Ordering'		=> $item['Ordering']
										));
					// update requirment functions and officers
					$reqfunctiontypes = array();
					$current_reqFunctions = $this->mgovdb->getRecords('Service_Functions', array('ServiceID' => $serviceID, 'RequirementID'	 => $existingreq->id));

					foreach ($item['Function'] as $function) {
						$reqfunctiontypes[$function['Type']] = $function;
						$q = $this->db->where(array('ServiceID'		 => $serviceID,
													'RequirementID'	 => $existingreq->id,
													'FuntionFor'	 => 'Requirement',
													'FunctionTypeID' => $function['Type']
												))->get('Service_Functions');

						if ($q->num_rows() > 0) {

							$existing = $q->row();
							$this->mgovdb->saveData('Service_Functions', array(
												'id'			=> $existing->id,
												'Description'	=> $function['Desc'],
												'Ordering'		=> $function['Ordering']
											));

							//delete officer
							$current_officers     = $this->mgovdb->getRecords('Service_FunctionOfficers', array('ServiceID' => $serviceID, 'FunctionID' => $existing->id));
							$current_officers_id  = array();
							foreach ($current_officers as $current_officer) {
								$current_officers_id[] = $current_officer['AccountID'];
								if (!in_array($current_officer['AccountID'], $function['Officer'])) {
									$this->mgovdb->deleteData('Service_FunctionOfficers', $current_officer['id']);
								}
							}
							// add not existing officer
							foreach ($function['Officer'] as $officerID) {
								if (!in_array($officerID, $current_officers_id)) {
									$this->mgovdb->saveData('Service_FunctionOfficers', array(
										'ServiceID'		=> $serviceID,
										'AccountID'		=> $officerID,
										'FunctionID'	=> $existing->id
									));
								}
							}

						} else {
							$function_id = $this->mgovdb->saveData('Service_Functions', array(
												'ServiceID'		=> $serviceID,
												'FuntionFor'	=> 'Requirement',
												'RequirementID'	=> $existingreq->id,
												'FunctionTypeID'=> $function['Type'],
												'Description'	=> $function['Desc'],
												'Ordering'		=> $function['Ordering']
											));

							// assign function officer
							foreach ($function['Officer'] as $officerID) {
								$this->mgovdb->saveData('Service_FunctionOfficers', array(
									'ServiceID'		=> $serviceID,
									'AccountID'		=> $officerID,
									'FunctionID'	=> $function_id
								));
							}
						}
					}

					// delete not on new list
					// not existing FunctionTypeID
					foreach ($current_reqFunctions as $reqitem) {
						if (!array_key_exists($reqitem['FunctionTypeID'], $reqfunctiontypes)) {
							// remove item and officer records
							$this->db->where('FunctionID', $reqitem['id'])->delete('Service_FunctionOfficers');
							$this->db->where('id', $reqitem['id'])->delete('Service_Functions');
						}
					}

				} else {

					// NEW REQUIREMENT RECORD
					$requirement_id = $this->mgovdb->saveData('Service_Requirements', array(
											'ServiceID'		=> $serviceID,
											'DocumentID'	=> $item['DocID'],
											'Description'	=> $item['Desc'],
											'Ordering'		=> $item['Ordering']
										));
					// add function
					foreach ($item['Function'] as $function) {

						$function_id = $this->mgovdb->saveData('Service_Functions', array(
											'ServiceID'		=> $serviceID,
											'FuntionFor'	=> 'Requirement',
											'RequirementID'	=> $requirement_id,
											'FunctionTypeID'=> $function['Type'],
											'Description'	=> $function['Desc'],
											'Ordering'		=> $function['Ordering']
										));

						// assign function officer
						foreach ($function['Officer'] as $officerID) {
							$this->mgovdb->saveData('Service_FunctionOfficers', array(
								'ServiceID'		=> $serviceID,
								'RequirementID'	=> $requirement_id,
								'AccountID'		=> $officerID,
								'FunctionID'	=> $function_id
							));
						}
					}
				}
			}

			// delete not on new list
			// not existing document
			foreach ($current_requirements as $reqitem) {
				if (!in_array($reqitem['DocumentID'], $documents_req)) {
					// remove requiremnt and each function and officer records
					$reqFunctions = $this->mgovdb->getRecords('Service_Functions', array('ServiceID' => $data['ServiceID'], 'RequirementID'	 => $reqitem['id']));
					foreach ($reqFunctions as $fncItem) {
						$this->db->where('FunctionID', $fncItem['id'])->delete('Service_FunctionOfficers');
						$this->db->where('id', $fncItem['id'])->delete('Service_Functions');
					}
					$this->db->where('id', $reqitem['id'])->delete('Service_Requirements');
				}
			}
			// END REQUIREMENTS


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