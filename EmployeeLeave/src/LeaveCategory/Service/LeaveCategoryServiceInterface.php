<?php

namespace LeaveCategory\Service;

use LeaveCategory\Model\LeaveCategory;

//need to add more models

interface LeaveCategoryServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|LeaveCategoryInterface[]
	*/
	
	public function listAll($tableName);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return LeaveCategoryInterface
	 */
	 
	public function findLeaveCategory($id);
        
	 /**
	 * @param LeaveCategoryInterface $leaveObject
	 *
	 * @param LeaveCategoryInterface $leaveObject
	 * @return LeaveCategoryInterface
	 * @throws \Exception
	 */
	 
	 public function save(LeaveCategory $leaveObject);
	 
	 /*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|LeaveCategoryInterface[]
	*/
	
	public function listSelectData($tableName, $columnName);
		
}