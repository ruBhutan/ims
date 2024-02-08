<?php

namespace Reassessment\Service;

use Reassessment\Mapper\ReassessmentMapperInterface;
use Reassessment\Model\Reassessment;

class ReassessmentService implements ReassessmentServiceInterface
{
	/**
	 * @var \Blog\Mapper\ReassessmentMapperInterface
	*/
	
	protected $reassessmentMapper;
	
	public function __construct(ReassessmentMapperInterface $reassessmentMapper) {
		$this->reassessmentMapper = $reassessmentMapper;
	}
	
	public function listAll($tableName, $applicant_id)
	{
		return $this->reassessmentMapper->findAll($tableName, $applicant_id);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->reassessmentMapper->getUserDetailsId($username);
	}
		
	public function getStudentDetailsId($username)
	{
		return $this->reassessmentMapper->getStudentDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->reassessmentMapper->getOrganisationId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->reassessmentMapper->getUserDetails($username, $usertype);
	}

    public function getUserImage($username, $usertype)
    {
    	return $this->reassessmentMapper->getUserImage($username, $usertype);
    }
		
	public function savePersonalDetails(PersonalDetails $reassessmentObject)
	{
		return $this->reassessmentMapper->savePersonalDetails($reassessmentObject);
	}
		
	public function getStudentDetails($student_id, $type)
	{
		return $this->reassessmentMapper->getStudentDetails($student_id, $type);
	}

	public function getReassessmentList($student_id)
	{
		return $this->reassessmentMapper->getReassessmentList($student_id);
	}

	public function crossCheckModuleReassessmentApplication($academic_modules_allocation_id, $student_id)
	{
		return $this->reassessmentMapper->crossCheckModuleReassessmentApplication($academic_modules_allocation_id, $student_id);
	}

	public function listReassessmentApplicants($organisation_id)
	{
		return $this->reassessmentMapper->listReassessmentApplicants($organisation_id);
	}

	public function getReassessmentApplicationDetails($id)
	{
		return $this->reassessmentMapper->getReassessmentApplicationDetails($id);
	}

	public function getReassessmentAnnouncementPeriod($organisation_id)
	{
		return $this->reassessmentMapper->getReassessmentAnnouncementPeriod($organisation_id);
	}

	
	public function getAcademicModules($student_id)
	{
		return $this->reassessmentMapper->getAcademicModules($student_id);
	}


	public function getTotalModuleList($student_id)
	{
		return $this->reassessmentMapper->getTotalModuleList($student_id);
	}

	public function saveReassessmentApplication(Reassessment $reassessmentObject)
	{
		return $this->reassessmentMapper->saveReassessmentApplication($reassessmentObject);
	}

	public function updateReassessmentModuleStatus($data_to_insert, $organisation_id, $employee_details_id)
	{
		return $this->reassessmentMapper->updateReassessmentModuleStatus($data_to_insert, $organisation_id, $employee_details_id);
	}

	public function updateApprovedReassessmentModuleStatus($data_to_insert, $organisation_id, $employee_details_id)
	{
		return $this->reassessmentMapper->updateApprovedReassessmentModuleStatus($data_to_insert, $organisation_id, $employee_details_id);
	}
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->reassessmentMapper->listSelectData($tableName, $columnName, $condition);
	}
	
}