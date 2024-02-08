<?php

namespace LeaveCategory\Service;

use LeaveCategory\Mapper\LeaveCategoryMapperInterface;
use LeaveCategory\Model\LeaveCategory;

class LeaveCategoryService implements LeaveCategoryServiceInterface
{
	/**
	 * @var \Blog\Mapper\LeaveCategoryMapperInterface
	*/
	
	protected $leaveMapper;
	
	public function __construct(LeaveCategoryMapperInterface $leaveMapper) {
		$this->leaveMapper = $leaveMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->leaveMapper->findAll($tableName);
	}
	 
	public function findLeaveCategory($id)
	{
		return $this->leaveMapper->findLeaveCategory($id);
	}
        
	public function save(LeaveCategory $leaveObject) 
	{
		return $this->leaveMapper->save($leaveObject);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->leaveMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->leaveMapper->getUserImage($username, $usertype);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->leaveMapper->listSelectData($tableName, $columnName);
	}
	
}