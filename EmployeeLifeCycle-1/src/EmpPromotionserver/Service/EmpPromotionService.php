<?php

namespace EmpPromotion\Service;

use EmpPromotion\Mapper\EmpPromotionMapperInterface;
use EmpPromotion\Model\EmpPromotion;
use EmpPromotion\Model\RejectPromotion;

class EmpPromotionService implements EmpPromotionServiceInterface
{
	/**
	 * @var \Blog\Mapper\EmpPromotionMapperInterface
	*/
	
	protected $promotionMapper;
	
	public function __construct(EmpPromotionMapperInterface $promotionMapper) {
		$this->promotionMapper = $promotionMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->promotionMapper->findAll($tableName);
	}
        
	public function listMeritoriousPromotion($organisation_id)
	{
			return $this->promotionMapper->listMeritoriousPromotion($organisation_id);
	}
		
	public function getEmployeeDetailsId($emp_id)
	{
		return $this->promotionMapper->getEmployeeDetailsId($emp_id);
	}
		 
	public function getOrganisationId($username)
	{
		return $this->promotionMapper->getOrganisationId($username);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->promotionMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->promotionMapper->getUserImage($username, $usertype);
	}
        
	public function findPromotionDetails($id) 
	{
		return $this->promotionMapper->findPromotionDetails($id);;
	}
	
	public function save(EmpPromotion $promotionObject) 
	{
		return $this->promotionMapper->save($promotionObject);
	}

	public function crossCheckAppliedPromotion($promotion_type, $employee_details_id)
	{
		return $this->promotionMapper->crossCheckAppliedPromotion($promotion_type, $employee_details_id);
	}
		
	public function savePromotionApprovalDetails($data)
	{
		return $this->promotionMapper->savePromotionApprovalDetails($data);
	}
	
	public function saveOpenCompetitionPromotion($data)
	{
		return $this->promotionMapper->saveOpenCompetitionPromotion($data);
	}
		
	public function rejectPromotion(RejectPromotion $promotionObject)
	{
		return $this->promotionMapper->rejectPromotion($promotionObject);
	}
		
	public function getPersonalDetails($employee_id)
	{
		return $this->promotionMapper->getPersonalDetails($employee_id);
	}
		
	public function getEducationDetails($employee_id)
	{
		return $this->promotionMapper->getEducationDetails($employee_id);
	}
		
	public function getEmploymentDetails($employee_id)
	{
		return $this->promotionMapper->getEmploymentDetails($employee_id);
	}

	public function getEmployeeLastPromotion($last_promotion, $employee_id)
	{
		return $this->promotionMapper->getEmployeeLastPromotion($last_promotion, $employee_id);
	}
		
	public function getTrainingDetails($employee_id)
	{
		return $this->promotionMapper->getTrainingDetails($employee_id);
	}
		
	public function getResearchDetails($employee_id)
	{
		return $this->promotionMapper->getResearchDetails($employee_id);
	}
		
	public function getStudyLeaveDetails($employee_id)
	{
		return $this->promotionMapper->getStudyLeaveDetails($employee_id);
	}
		
	public function getEolLeaveDetails($employee_id)
	{
		return $this->promotionMapper->getEolLeaveDetails($employee_id);
	}
		
	public function getPmsDetails($employee_id, $userrole)
	{
		return $this->promotionMapper->getPmsDetails($employee_id, $userrole);
	}
	
	public function getPayDetails($position_level)
	{
		return $this->promotionMapper->getPayDetails($position_level);
	}
	
	public function getPositionDetails($position_title)
	{
		return $this->promotionMapper->getPositionDetails($position_title);
	}
		
	public function getPromotionApplicantList($organisation_id, $userrole, $employee_details_id, $departments_id, $status)
	{
		return $this->promotionMapper->getPromotionApplicantList($organisation_id, $userrole, $employee_details_id, $departments_id, $status);
	}
		
	public function getPromotionApplicantDetail($id)
	{
		return $this->promotionMapper->getPromotionApplicantDetail($id);
	}
	
	public function getNotificationDetails($organisation_id)
	{
		return $this->promotionMapper->getNotificationDetails($organisation_id);
	}
	
	public function getEmployeeList($empName, $empId, $department, $organisation_id)
	{
		return $this->promotionMapper->getEmployeeList($empName, $empId, $department, $organisation_id);
	}
		
	public function getFileName($promotion_id, $document_type)
	{
		return $this->promotionMapper->getFileName($promotion_id, $document_type);
	}


	public function getSupervisorEmailId($userrole, $departments_units_id)
	{
		return $this->promotionMapper->getSupervisorEmailId($userrole, $departments_units_id);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->promotionMapper->listSelectData($tableName, $columnName);
	}
	
}