<?php

namespace Hostel\Mapper;

use Hostel\Model\Hostel;
use Hostel\Model\HostelAllocation;
use Hostel\Model\HostelApplication;
use Hostel\Model\HostelRoom;
use Hostel\Model\HostelInventory;
use Hostel\Model\AllocateHostelRoom;

interface HostelMapperInterface
{
	/**
	 * @param int/string $id
	 * @return Hostel
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findStudent($id);
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username, $tableName);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName);

	public function getUserDetails($username, $usertype);
	  
	public function getUserImage($username, $usertype);

	/**
	 * 
	 * @return array/ Hostel[]
	 */
	 
	public function findAll($tableName, $organisation_id);
        
	/**
	 * 
	 * @param type $HostelInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveHostel(Hostel $HostelInterface);

	public function saveAdditionalRoom(Hostel $hostelObject);
	
	/*
	 * Save hostel room
	 */
	 
	public function saveHostelRoom(HostelRoom $hostelObject);
	
	
	/*
	 * Save Hostel Application for change/new 
	 */
	 
	public function saveHostelApplication(HostelApplication $hostelObject);
	
	/*
	 * Save Hostel Inventory
	 */
	 
	public function saveHostelInventory(HostelInventory $hostelObject);
	
	/*
	 * Get the details of the hostel
	 */
	 
	public function findHostel($id);
	
	/*
	 * Get the details of the hostel room details
	 */
	 
	public function findHostelRoom($id);
	 
	/*
	 * To allocate the rooms to the various students
	 */
	 
	 public function allocateHostel(HostelAllocation $hostelObject, $organisation_id);

	 public function allocateHostelRoom(AllocateHostelRoom $hostelObject);
	 
	 /*
	 * Get the details of the hostel allocation
	 */
	 
	 public function getHostelAllocationDetails($id);

	 public function getUnallocatedHostelRoom($id, $organisation_id, $type);

	 public function getHostelUnallocatedStudent($id, $organisation_id, $type);
	 
	 /*
	 * Get the details of the hostel room inventory
	 */
	 public function getHostelInventoryDetails($id);


	 public function getSelectedHostelDetails($hostelName);
	 
	  /*
	 * Get the list of the rooms for all hostels in a college 
	 */
	 
	 public function getHostelRoomList($hostelName, $roomNo, $organisation_id);
	 
	 /*
	 * Get Hostel Application
	 */
	 
	 public function getHostelApplication($student_id, $organisation_id);
	 
	 /*
	 * Get Hostel Inventory
	 */
	 
	 public function getHostelInventory($organisation_id);
	 
	 /*
	 * Get student numbers by year
	 */
	 
	 public function getStudentNoByYear($organisation_id);

	 public function crossCheckAssignedHostelRoom($id);

	 public function getHostelId($id);

	 public function deleteAddededHostelRoom($id);

	 public function removeHostelAllocatedStudent($id);
	
	/**
	 * 
	 * @return array/ Hostel[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}