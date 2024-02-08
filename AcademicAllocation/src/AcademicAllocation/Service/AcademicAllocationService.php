<?php

namespace AcademicAllocation\Service;

use AcademicAllocation\Mapper\AcademicAllocationMapperInterface;
use AcademicAllocation\Model\AcademicAllocation;


class AcademicAllocationService implements AcademicAllocationServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $academicAllocationMapper;
	
	public function __construct(AcademicAllocationMapperInterface $academicAllocationMapper) {
		$this->academicAllocationMapper = $academicAllocationMapper;
	}

	public function getUserDetailsId($tableName, $username)
	{
		return $this->academicAllocationMapper->getUserDetailsId($tableName, $username);
	}
	
	public function getOrganisationId($tableName, $username)
	{
		return $this->academicAllocationMapper->getOrganisationId($tableName, $username);
	}


	public function getUserDetails($username, $usertype)
	{
		return $this->academicAllocationMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->academicAllocationMapper->getUserImage($username, $usertype);
	}

	public function getAllocatedModuleAssessmentComponent($organisation_id)
	{
		return $this->academicAllocationMapper->getAllocatedModuleAssessmentComponent($organisation_id);
	}

	public function getAllocatedAssessmmentComponentDetail($id)
	{
		return $this->academicAllocationMapper->getAllocatedAssessmmentComponentDetail($id);
	}

	public function updateAllocatedAssessmentWeightage($id, $weightage)
	{
		return $this->academicAllocationMapper->updateAllocatedAssessmentWeightage($id, $weightage);
	}
}