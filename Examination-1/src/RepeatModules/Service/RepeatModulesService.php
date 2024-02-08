<?php

namespace RepeatModules\Service;

use RepeatModules\Mapper\RepeatModulesMapperInterface;
use RepeatModules\Model\RepeatModules;

class RepeatModulesService implements RepeatModulesServiceInterface
{
	/**
	 * @var \Blog\Mapper\RepeatModulesMapperInterface
	*/
	
	protected $repeatModulesMapper;
	
	public function __construct(RepeatModulesMapperInterface $repeatModulesMapper) {
		$this->repeatModulesMapper = $repeatModulesMapper;
	}
	
	public function listAll($tableName, $applicant_id)
	{
		return $this->repeatModulesMapper->findAll($tableName, $applicant_id);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->repeatModulesMapper->getUserDetailsId($username);
	}
		
	public function getStudentId($username)
	{
		return $this->repeatModulesMapper->getStudentId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->repeatModulesMapper->getOrganisationId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->repeatModulesMapper->getUserDetails($username, $usertype);
	}

    public function getUserImage($username, $usertype)
    {
    	return $this->repeatModulesMapper->getUserImage($username, $usertype);
    }
		
	public function save(RepeatModules $repeatModulesModel)
	{
		return $this->repeatModulesMapper->save($repeatModulesObject);
	}
		
	public function getStudentDetails($student_id)
	{
		return $this->repeatModulesMapper->getStudentDetails($student_id);
	}
		
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->repeatModulesMapper->listSelectData($tableName, $columnName, $condition);
	}
	
}