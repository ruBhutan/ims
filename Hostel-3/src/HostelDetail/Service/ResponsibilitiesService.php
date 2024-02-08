<?php

namespace Responsibilities\Service;

use Responsibilities\Mapper\ResponsibilitiesMapperInterface;
use Responsibilities\Model\Responsibilities;
use Responsibilities\Model\ResponsibilityCategory;

class ResponsibilitiesService implements ResponsibilitiesServiceInterface
{
	/**
	 * @var \Blog\Mapper\ResponsibilitiesMapperInterface
	*/
	
	protected $responsibilityMapper;
	
	public function __construct(ResponsibilitiesMapperInterface $responsibilityMapper) {
		$this->responsibilityMapper = $responsibilityMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->responsibilityMapper->findAll($tableName);
	}
	 
	public function findStudent($id)
	{
		return $this->responsibilityMapper->findStudent($id);
	}
        
	public function findObjectives($id) 
	{
		return $this->responsibilityMapper->findObjectives($id);;
	}
	
	public function save(ResponsibilityCategory $responsibilityObject) 
	{
		return $this->responsibilityMapper->saveResponsibility($responsibilityObject);
	}
	
	public function saveObjectives(Objectives $responsibilityObject) 
	{
		return $this->responsibilityMapper->saveObjectives($responsibilityObject);
	}
	
	public function saveActivities(AwpaObjectives $responsibilityObject) 
	{
		return $this->responsibilityMapper->saveActivities($responsibilityObject);
	}
	
	public function saveKpi(AwpaActivities $responsibilityObject) 
	{
		return $this->responsibilityMapper->saveKpi($responsibilityObject);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->responsibilityMapper->listSelectData($tableName, $columnName);
	}
	
}