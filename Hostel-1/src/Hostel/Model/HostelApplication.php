<?php

namespace Hostel\Model;

class HostelApplication
{
	protected $id;
	protected $hostel_name;
	protected $hostel_to_name;
	protected $hostel_type;
	protected $hostel_category;
	protected $hostel_room_no;
	protected $remarks;
	protected $status;
	protected $student_id;
	protected $organisation_id;
	
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
	
	public function getHostel_To_Name()
	{
		return $this->hostel_to_name;
	}
	
	public function setHostel_To_Name($hostel_to_name)
	{
		$this->hostel_to_name = $hostel_to_name;
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
			
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
}