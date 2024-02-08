<?php

namespace Hostel\Model;

class HostelRoom
{
	protected $id;
	protected $room_no;
	protected $room_capacity;
	protected $room_available;
	protected $hostels_list_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getRoom_No()
	{
		return $this->room_no;
	}
	
	public function setRoom_No($room_no)
	{
		$this->room_no = $room_no;
	}
	
	public function getRoom_Capacity()
	{
		return $this->room_capacity;
	}
	
	public function setRoom_Capacity($room_capacity)
	{
		$this->room_capacity = $room_capacity;
	}
	
	public function getRoom_Available()
	{
		return $this->room_available;
	}
	
	public function setRoom_Available($room_available)
	{
		$this->room_available = $room_available;
	}
	
	public function getHostels_List_Id()
	{
		return $this->hostels_list_id;
	}
	
	public function setHostels_List_Id($hostels_list_id)
	{
		$this->hostels_list_id = $hostels_list_id;
	}
}