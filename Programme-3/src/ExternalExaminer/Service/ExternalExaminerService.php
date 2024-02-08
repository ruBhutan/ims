<?php

namespace ExternalExaminer\Service;

use ExternalExaminer\Mapper\ExternalExaminerMapperInterface;
use ExternalExaminer\Model\ExternalExaminer;
use ExternalExaminer\Model\ExternalExaminerApproval;

class ExternalExaminerService implements ExternalExaminerServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $externalExaminerDetailMapper;
	
	public function __construct(ExternalExaminerMapperInterface $externalExaminerDetailMapper) {
		$this->externalExaminerDetailMapper = $externalExaminerDetailMapper;
	}
	
	public function listExternalExaminers()
	{
		return $this->externalExaminerDetailMapper->findAll();
	}
	 
	public function findExternalExaminer($id)
	{
		return $this->externalExaminerDetailMapper->find($id);
	}
	
	public function save(ExternalExaminer $externalExaminerObject, $form_data) 
	{
		return $this->externalExaminerDetailMapper->saveDetails($externalExaminerObject, $form_data);
	}

	public function updateExternalExaminer(ExternalExaminer $externalExaminerObject, $form_data)
	{
		return $this->externalExaminerDetailMapper->updateExternalExaminer($externalExaminerObject, $form_data);
	}
			
	public function getUserDetailsId($username, $tableName)
	{
		return $this->externalExaminerDetailMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getOrganisationId($username)
	{
		return $this->externalExaminerDetailMapper->getOrganisationId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->externalExaminerDetailMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->externalExaminerDetailMapper->getUserImage($username, $usertype);
	}
		
	public function getExternalExaminersList($data)
	{
		return $this->externalExaminerDetailMapper->getExternalExaminersList($data);
	}

	public function getFileName($id)
	{
		return $this->externalExaminerDetailMapper->getFileName($id);
	}
		
	public function listSelectData($tableName, $columnName)
	{
		return $this->externalExaminerDetailMapper->listSelectData($tableName, $columnName);
	}
	
}