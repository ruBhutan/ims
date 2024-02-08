<?php

namespace Hostel\Service;

use Hostel\Model\Hostel;
use Hostel\Model\HostelAllocation;
use Hostel\Model\HostelApplication;
use Hostel\Model\HostelRoom;
use Hostel\Model\HostelInventory;

//need to add more models

interface HostelServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|HostelInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return HostelInterface
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
	 * @param HostelInterface $hostelObject
	 *
	 * @param HostelInterface $hostelObject
	 * @return HostelInterface
	 * @throws \Exception
	 */
	 
	 public function save(Hostel $hostelObject);
	 
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
	 * To allocate the rooms to the various students
	 */
	 
	 public function allocateHostel(HostelAllocation $hostelObject, $organisation_id);
	 
	 /*
	 * Get the details of the hostel
	 */
	 
	 public function findHostel($id);
	 
	 /*
	 * Get the details of the hostel room details
	 */
	 
	public function findHostelRoom($id);
	
	 /*
	 * Get the details of the hostel allocation
	 */
	 
	 public function getHostelAllocationDetails($id);
	 
	 /*
	 * Get the details of the hostel room inventory
	 */
	 public function getHostelInventoryDetails($id);
	 
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
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|HostelInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}