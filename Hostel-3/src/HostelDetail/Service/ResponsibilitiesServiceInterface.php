<?php

namespace Responsibilities\Service;

use Responsibilities\Model\Responsibilities;
use Responsibilities\Model\ResponsibilityCategory;

//need to add more models

interface ResponsibilitiesServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|ResponsibilitiesInterface[]
	*/
	
	public function listAll($tableName);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return ResponsibilitiesInterface
	 */
	 
	public function findStudent($id);
        
        
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the Proposal that should be returned
	 * @return ResponsibilitiesInterface
	 */
        
     public function findObjectives($id);
	 
	 /**
	 * @param ResponsibilitiesInterface $responsibilityObject
	 *
	 * @param ResponsibilitiesInterface $responsibilityObject
	 * @return ResponsibilitiesInterface
	 * @throws \Exception
	 */
	 
	 public function save(ResponsibilityCategory $responsibilityObject);
	 
	 /**
	 * @param ResponsibilitiesInterface $responsibilityObject
	 *
	 * @param ResponsibilitiesInterface $responsibilityObject
	 * @return ResponsibilitiesInterface
	 * @throws \Exception
	 */
	 
	 public function saveObjectives(Objectives $responsibilityObject);
	 
	 /**
	 * @param ResponsibilitiesInterface $responsibilityObject
	 *
	 * @param ResponsibilitiesInterface $responsibilityObject
	 * @return ResponsibilitiesInterface
	 * @throws \Exception
	 */
	 
	 public function saveActivities(AwpaObjectives $responsibilityObject);
	 
	 /**
	 * @param ResponsibilitiesInterface $responsibilityObject
	 *
	 * @param ResponsibilitiesInterface $responsibilityObject
	 * @return ResponsibilitiesInterface
	 * @throws \Exception
	 */
	 
	 public function saveKpi(AwpaActivities $responsibilityObject);
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|ResponsibilitiesInterface[]
	*/
	
	public function listSelectData($tableName, $columnName);
		
		
}