<?php

namespace Nominations\Service;

use Nominations\Mapper\NominationsMapperInterface;
use Nominations\Model\Nominations;

class NominationsService implements NominationsServiceInterface
{
	/**
	 * @var \Blog\Mapper\NominationsMapperInterface
	*/
	
	protected $nominationMapper;
	
	public function __construct(NominationsMapperInterface $nominationMapper) {
		$this->nominationMapper = $nominationMapper;
	}
	
	public function getEmployeeDetailsId($emp_id)
	{
		return $this->nominationMapper->getEmployeeDetailsId($emp_id);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->nominationMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->nominationMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName)
	{
		return $this->nominationMapper->findAll($tableName);
	}
	
	public function saveNominations(Nominations $nominationObject)
	{
		return $this->nominationMapper->saveNominations($nominationObject);
	}
		
	public function getNominationList($tableName, $employee_details_id)
	{
		return $this->nominationMapper->getNominationList($tableName, $employee_details_id);
	}
		
	public function getNominatedEmployee($employee_details_id)
	{
		return $this->nominationMapper->getNominatedEmployee($employee_details_id);
	}
	
	public function getIwpDeadline()
	{
		return $this->nominationMapper->getIwpDeadline();
	}
	
	public function getEmployeeList($id, $empName, $position_title, $organisation_id)
	{
		return $this->nominationMapper->getEmployeeList($id, $empName, $position_title, $organisation_id);
	}
	
	public function deleteNomination($table_name, $id)
	{
		return $this->nominationMapper->deleteNomination($table_name, $id);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->nominationMapper->listSelectData($tableName, $columnName);
	}
	
}