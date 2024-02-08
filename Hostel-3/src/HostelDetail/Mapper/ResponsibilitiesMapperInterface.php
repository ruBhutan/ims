<?php

namespace Responsibilities\Mapper;

use Responsibilities\Model\Responsibilities;
use Responsibilities\Model\ResponsibilityCategory;

interface ResponsibilitiesMapperInterface
{
	/**
	 * @param int/string $id
	 * @return Responsibilities
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findStudent($id);

	/**
	 * 
	 * @return array/ Responsibilities[]
	 */
	 
	public function findAll($tableName);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Obejctives
	 */
	
	public function findObjectives($id);
	
	/**
	 * 
	 * @param type $ResponsibilitiesInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveResponsibility(ResponsibilityCategory $ResponsibilitiesInterface);
	
	/**
	 * 
	 * @param type $ResponsibilitiesInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveObjectives(Objectives $ResponsibilitiesInterface);
	
	/**
	 * 
	 * @param type $ResponsibilitiesInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveActivities(AwpaObjectives $ResponsibilitiesInterface);
	
	/**
	 * 
	 * @param type $ResponsibilitiesInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveKpi(AwpaActivities $ResponsibilitiesInterface);
	
	/**
	 * 
	 * @return array/ Responsibilities[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}