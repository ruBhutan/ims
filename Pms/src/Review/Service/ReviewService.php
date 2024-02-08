<?php

namespace Review\Service;

use Review\Mapper\ReviewMapperInterface;
use Review\Model\Review;
use Review\Model\AcademicReview;
use Review\Model\AcademicWeight;
use Review\Model\IwpObjectives;
use Review\Model\NatureActivity;

class ReviewService implements ReviewServiceInterface
{
	/**
	 * @var \Blog\Mapper\ReviewMapperInterface
	*/
	
	protected $reviewMapper;
	
	public function __construct(ReviewMapperInterface $reviewMapper) {
		$this->reviewMapper = $reviewMapper;
	}
	
	public function getOrganisationId($username)
	{
		return $this->reviewMapper->getOrganisationId($username);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->reviewMapper->getUserDetailsId($username);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->reviewMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->reviewMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName)
	{
		return $this->reviewMapper->findAll($tableName);
	}
	
	public function listEmployeeReview($tableName, $employee_id)
	{
		return $this->reviewMapper->findEmployeeReview($tableName, $employee_id);
	}
	
	public function listActivityDetail($tableName, $columnName, $activity_id)
	{
		return $this->reviewMapper->findActivityDetail($tableName, $columnName, $activity_id);
	}
	
	public function saveAcademicReview(AcademicReview $reviewObject)
	{
		return $this->reviewMapper->saveAcademicReview($reviewObject);
	}
		
	public function saveSelfEvaluation($data, $review_data, $evaluation_type, $employee_details_id)
	{
		return $this->reviewMapper->saveSelfEvaluation($data, $review_data, $evaluation_type, $employee_details_id);
	}
		
	public function saveSupervisorEvaluation($rating_data, $evaluation_type, $employee_details_id)
	{
		return $this->reviewMapper->saveSupervisorEvaluation($rating_data, $evaluation_type, $employee_details_id);
	}
		
	public function saveFeedbackEvaluation($feedback_for, $nomination_id, $data, $employee_id, $appraisal_period, $employee_details_id)
	{
		return $this->reviewMapper->saveFeedbackEvaluation($feedback_for, $nomination_id, $data, $employee_id, $appraisal_period, $employee_details_id);
	}
        
	public function saveStudentFeedback($rating_data, $academic_module, $module_tutor, $appraisal_period, $student_id) 
	{
			return $this->reviewMapper->saveStudentFeedback($rating_data, $academic_module, $module_tutor, $appraisal_period, $student_id);
	}
	 
	public function findEmployeeId($emp_id)
	{
		return $this->reviewMapper->findEmployeeId($emp_id);
	}
	
	public function listAdministrativeAppraisal($tableName, $employee_id, $status, $appraisal_year)
	{
		return $this->reviewMapper->listAdministrativeAppraisal($tableName, $employee_id, $status, $appraisal_year);
	}
	
	public function listEmployeeAppraisal($tableName, $employee_id, $status, $appraisal_year)
	{
		return $this->reviewMapper->listEmployeeAppraisal($tableName, $employee_id, $status, $appraisal_year);
	}
	
	public function getNominationList($tableName, $employee_details_id)
	{
		return $this->reviewMapper->getNominationList($tableName, $employee_details_id);
	}
	
	public function getNominatedEmployee($employee_details_id)
	{
		return $this->reviewMapper->getNominatedEmployee($employee_details_id);
	}
		
	public function listAppraisalForEmployee($tableName, $employee_id)
	{
		return $this->reviewMapper->listAppraisalForEmployee($tableName, $employee_id);
	}
		
	public function getEmployeeDetails($id)
	{
		return $this->reviewMapper->getEmployeeDetails($id);
	}

	public function getIwpDeadline($iwp_type)
	{
		return $this->reviewMapper->getIwpDeadline($iwp_type);
	}
		
	public function getRevieweeDetails($id, $tableName)
	{
		return $this->reviewMapper->getRevieweeDetails($id, $tableName);
	}
	
	public function getAppraisalList($type, $employee_details_id, $role, $organisation_id)
	{
		return $this->reviewMapper->getAppraisalList($type, $employee_details_id, $role, $organisation_id);
	}
		
	public function getPerformanceScore($evaluation_type, $employee_details_id)
	{
		return $this->reviewMapper->getPerformanceScore($evaluation_type, $employee_details_id);
	}
		
	public function getFeedbackScore($evaluation_type, $employee_details_id)
	{
		return $this->reviewMapper->getFeedbackScore($evaluation_type, $employee_details_id);
	}
	
	public function getEmployeeList($empName, $empId, $department, $organisation_id)
	{
			return $this->reviewMapper->getEmployeeList($empName, $empId, $department, $organisation_id);
	}
	
	public function listSelectData($tableName, $columnName, $empIds)
	{
		return $this->reviewMapper->listSelectData($tableName, $columnName, $empIds);
	}
	
}