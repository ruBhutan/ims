<?php

namespace PlanningReports\Service;

use PlanningReports\Mapper\PlanningReportsMapperInterface;
//use PlanningReports\Model\PlanningReports;
use PlanningReports\Model\PlanningReportsCategory;

class PlanningReportsService implements PlanningReportsServiceInterface
{
	/**
	 * @var \Blog\Mapper\EmployeeTaskMapperInterface
	*/
	
	protected $planningreportsMapper;
	
	public function __construct(PlanningReportsMapperInterface $planningreportsMapper) {
		$this->planningreportsMapper = $planningreportsMapper;
	}
	
	public function getOrganisationId($username)
	{
		return $this->planningreportsMapper->getOrganisationId($username);
	}
	 	
	public function getUserDetailsId($username)
	{
		return $this->planningreportsMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->planningreportsMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->planningreportsMapper->getUserImage($username, $usertype);
	}

	public function getFiveYearPlan()
	{
		return $this->planningreportsMapper->getFiveYearPlan();
	}

	public function findFiveYearPlan($id)
	{
		return $this->planningreportsMapper->findFiveYearPlan($id);
	}

	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->planningreportsMapper->listSelectData($tableName, $columnName, $organisation_id);
	}

	public function getstaffDetail($report_details, $organisation_id)
	{
		return $this->planningreportsMapper->getstaffDetail($report_details, $organisation_id);
	}

	public function getobjectiveWeight($report_details, $organisation_id)
	{
		return $this->planningreportsMapper->getobjectiveWeight($report_details, $organisation_id);
	}

	public function getkeyAspiration($report_details, $organisation_id)
	{
		return $this->planningreportsMapper->getkeyAspiration($report_details, $organisation_id);
	}

	public function getsuccessIndicator($report_details, $organisation_id)
	{
		return $this->planningreportsMapper->getsuccessIndicator($report_details, $organisation_id);
	}

	public function gettrendsuccessIndicator($report_details, $organisation_id)
	{
		return $this->planningreportsMapper->gettrendsuccessIndicator($report_details, $organisation_id);
	}

	public function getdefinitionsuccessIndicator($report_details, $organisation_id)
	{
		return $this->planningreportsMapper->getdefinitionsuccessIndicator($report_details, $organisation_id);
	}

	public function getrequirementssuccessindicator($report_details, $organisation_id)
	{
		return $this->planningreportsMapper->getrequirementssuccessindicator($report_details, $organisation_id);
	}

	
	
	/*public function listAll($tableName, $organisation_id, $employee_details_id)
	{
		return $this->planningreportsMapper->findAll($tableName, $organisation_id, $employee_details_id);
	}

	public function listAll1($employee_details_id)
	{
		return $this->planningreportsMapper->findAll1($employee_details_id);
	}
	 
	public function findStaff($id)
	{
		return $this->planningreportsMapper->findStaff($id);
	}
        	
	public function saveCategory(PlanningReportsCategory $employeetaskObject) 
	{
		return $this->planningreportsMapper->saveDetails($employeetaskObject);
	}
		
	public function saveEmployeeTaskRecord(PlanningReports $employeetaskObject)
	{
		return $this->planningreportsMapper->saveEmployeeTaskRecord($employeetaskObject);
	}
	
	public function getStaffList($staffName, $staffId, $organisation_id)
	{
		return $this->planningreportsMapper->getStaffList($staffName, $staffId, $organisation_id);
	}
		
	public function getStaffEmployeeTaskList($staffName, $staffId, $organisation_id)
	{
		return $this->planningreportsMapper->getStaffEmployeeTaskList($staffName, $staffId, $organisation_id);
	}
	
	public function getStaffDetails($id)
	{
		return $this->planningreportsMapper->getStaffDetails($id);
	}
	
	public function getEmployeeTaskCategoryDetails($id)
	{
		return $this->planningreportsMapper->getEmployeeTaskCategoryDetails($id);
	}
	public function getEmployeeTaskRecordDetails($id)
	{
		return $this->planningreportsMapper->getEmployeeTaskRecordDetails($id);
	}
		
	public function getStaffEmployeeTaskRecords($staff_id)
	{
		return $this->planningreportsMapper->getStaffEmployeeTaskRecords($staff_id);
	}
	
	public function getEmployeeTaskRecord($organisation_id)
	{
		return $this->planningreportsMapper->getEmployeeTaskRecord($organisation_id);
	}
		
	

	public function listSelectData1($tableName, $id)
	{
		return $this->planningreportsMapper->listSelectData1($tableName, $id);
	}

	public function getFileName($file_id)
	{
		return $this->planningreportsMapper->getFileName($file_id);
	}

	public function getstafftaskRecord($staff_id,$from_date, $to_date)
	{
		return $this->planningreportsMapper->getstafftaskRecord($staff_id,$from_date, $to_date);
	}*/
	
}