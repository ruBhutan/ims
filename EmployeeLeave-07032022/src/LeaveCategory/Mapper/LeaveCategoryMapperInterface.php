<?php

namespace LeaveCategory\Mapper;

use LeaveCategory\Model\LeaveCategory;

interface LeaveCategoryMapperInterface
{
	/**
	 * @param int/string $id
	 * @return LeaveCategory
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findLeaveCategory($id);

	/**
	 * 
	 * @return array/ LeaveCategory[]
	 */
	 
	public function findAll($tableName);
        	
	/**
	 * 
	 * @param type $LeaveCategoryInterface
	 * 
	 * to save budgetings
	 */
	
	public function save(LeaveCategory $LeaveCategoryInterface);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
		
	/**
	 * 
	 * @return array/ LeaveCategory[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}