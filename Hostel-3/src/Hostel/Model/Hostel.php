<?php

namespace Hostel\Model;

class Hostel
{
	protected $id;
	protected $hostel_name;
	protected $hostel_type;
	protected $hostel_category;
	protected $hostel_room_no;
	protected $hostel_floor_no;
	protected $room_capacity;
	protected $provost_name;
	protected $remarks;
	protected $organisation_id;
	protected $additional_hostel_room_no;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getHostel_Name()
	{
		return $this->hostel_name;
	}
	
	public function setHostel_Name($hostel_name)
	{
		$this->hostel_name = $hostel_name;
	}
	
	public function getHostel_Type()
	{
		return $this->hostel_type;
	}
	
	public function setHostel_Type($hostel_type)
	{
		$this->hostel_type = $hostel_type;
	}
	
	public function getHostel_Category()
	{
		return $this->hostel_category;
	}
	
	public function setHostel_Category($hostel_category)
	{
		$this->hostel_category = $hostel_category;
	}
	
	public function getHostel_Room_No()
	{
		return $this->hostel_room_no;
	}
	
	public function setHostel_Room_No($hostel_room_no)
	{
		$this->hostel_room_no = $hostel_room_no;
	}
	
	public function getHostel_Floor_No()
	{
		return $this->hostel_floor_no;
	}
	
	public function setHostel_Floor_No($hostel_floor_no)
	{
		$this->hostel_floor_no = $hostel_floor_no;
	}
	
	public function getRoom_Capacity()
	{
		return $this->room_capacity;
	}
	
	public function setRoom_Capacity($room_capacity)
	{
		$this->room_capacity = $room_capacity;
	}
	
	public function getProvost_Name()
	{
		return $this->provost_name;
	}
	
	public function setProvost_Name($provost_name)
	{
		$this->provost_name = $provost_name;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}


	public function getAdditional_Hostel_Room_No()
	{
		return $this->additional_hostel_room_no;
	}
	
	public function setAdditional_Hostel_Room_No($additional_hostel_room_no)
	{
		$this->additional_hostel_room_no = $additional_hostel_room_no;
	}
}