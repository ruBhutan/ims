<?php

namespace Hostel\Service;

use Hostel\Mapper\HostelMapperInterface;
use Hostel\Model\Hostel;
use Hostel\Model\HostelAllocation;
use Hostel\Model\HostelApplication;
use Hostel\Model\HostelRoom;
use Hostel\Model\HostelInventory;
use Hostel\Model\AllocateHostelRoom;
use Zend\Http\Header\Host;

class HostelService implements HostelServiceInterface
{
	/**
	 * @var \Blog\Mapper\HostelMapperInterface
	*/
	
	protected $hostelMapper;
	
	public function __construct(HostelMapperInterface $hostelMapper) {
		$this->hostelMapper = $hostelMapper;
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->hostelMapper->findAll($tableName, $organisation_id);
	}
	 
	public function findStudent($id)
	{
		return $this->hostelMapper->findStudent($id);
	}
	
	public function getOrganisationId($username, $tableName)
	{
		return $this->hostelMapper->getOrganisationId($username, $tableName);
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->hostelMapper->getUserDetailsId($username, $tableName);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->hostelMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->hostelMapper->getUserImage($username, $usertype);
	}
        
	public function save(Hostel $hostelObject) 
	{
		return $this->hostelMapper->saveHostel($hostelObject);
	}

	public function saveAdditionalRoom(Hostel $hostelObject)
	{
		return $this->hostelMapper->saveAdditionalRoom($hostelObject);
	}

	public function saveHostelRoom(HostelRoom $hostelObject)
	{
		return $this->hostelMapper->saveHostelRoom($hostelObject);
	}
		 
	public function saveHostelApplication(HostelApplication $hostelObject)
	{
		return $this->hostelMapper->saveHostelApplication($hostelObject);
	}
		 
	public function saveHostelInventory(HostelInventory $hostelObject)
	{
		return $this->hostelMapper->saveHostelInventory($hostelObject);
	}
	
	public function allocateHostel(HostelAllocation $hostelObject, $organisation_id)
	{
		return $this->hostelMapper->allocateHostel($hostelObject, $organisation_id);
	}

	public function allocateHostelRoom(AllocateHostelRoom $hostelObject)
	{
		return $this->hostelMapper->allocateHostelRoom($hostelObject);
	}
	
	public function findHostel($id)
	{
		return $this->hostelMapper->findHostel($id);
	}
	
	public function findHostelRoom($id)
	{
		return $this->hostelMapper->findHostelRoom($id);
	}
	
	public function getHostelAllocationDetails($id)
	{
		return $this->hostelMapper->getHostelAllocationDetails($id); 
	}

	public function getUnallocatedHostelRoom($id, $organisation_id, $type)
	{
		return $this->hostelMapper->getUnallocatedHostelRoom($id, $organisation_id, $type);
	}

	public function getHostelUnallocatedStudent($id, $organisation_id, $type)
	{
		return $this->hostelMapper->getHostelUnallocatedStudent($id, $organisation_id, $type);
	}
	
	public function getHostelInventoryDetails($id)
	{
		return $this->hostelMapper->getHostelInventoryDetails($id);
	}

	public function getSelectedHostelDetails($hostelName)
	{
		return $this->hostelMapper->getSelectedHostelDetails($hostelName);
	}
	
	public function getHostelRoomList($hostelName, $roomNo, $organisation_id)
	{
		return $this->hostelMapper->getHostelRoomList($hostelName, $roomNo, $organisation_id);
	}
		 
	public function getHostelApplication($student_id, $organisation_id)
	{
		return $this->hostelMapper->getHostelApplication($student_id, $organisation_id);
	}
	
	public function getHostelInventory($organisation_id)
	{
		return $this->hostelMapper->getHostelInventory($organisation_id);
	}
	 
	public function getStudentNoByYear($organisation_id)
	{
		return $this->hostelMapper->getStudentNoByYear($organisation_id);
	}

	public function crossCheckAssignedHostelRoom($id)
	{
		return $this->hostelMapper->crossCheckAssignedHostelRoom($id);
	}

	public function getHostelId($id)
	{
		return $this->hostelMapper->getHostelId($id);
	}

	public function deleteAddededHostelRoom($id)
	{
		return $this->hostelMapper->deleteAddededHostelRoom($id);
	}

	public function removeHostelAllocatedStudent($id)
	{
		return $this->hostelMapper->removeHostelAllocatedStudent($id);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->hostelMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}