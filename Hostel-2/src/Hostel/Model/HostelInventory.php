<?php

namespace Hostel\Model;

class HostelInventory
{
	protected $id;
	protected $hostels_list_id;
	protected $hostel_room_no;
	protected $no_beds;
	protected $no_chairs;
	protected $no_tables;
	protected $sockets;
	protected $lights;
	protected $remarks;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getHostels_List_Id()
	{
		return $this->hostels_list_id;
	}
	
	public function setHostels_List_Id($hostels_list_id)
	{
		$this->hostels_list_id = $hostels_list_id;
	}
		
	public function getHostel_Room_No()
	{
		return $this->hostel_room_no;
	}
	
	public function setHostel_Room_No($hostel_room_no)
	{
		$this->hostel_room_no = $hostel_room_no;
	}
	
	public function getNo_Beds()
	{
		return $this->no_beds;
	}
	
	public function setNo_Beds($no_beds)
	{
		$this->no_beds = $no_beds;
	}
	
	public function getNo_Chairs()
	{
		return $this->no_chairs;
	}
	
	public function setNo_Chairs($no_chairs)
	{
		$this->no_chairs = $no_chairs;
	}
	
	public function getNo_Tables()
	{
		return $this->no_tables;
	}
	
	public function setNo_Tables($no_tables)
	{
		$this->no_tables = $no_tables;
	}
	
	public function getSockets()
	{
		return $this->sockets;
	}
	
	public function setSockets($sockets)
	{
		$this->sockets = $sockets;
	}
	
	public function getLights()
	{
		return $this->lights;
	}
	
	public function setLights($lights)
	{
		$this->lights = $lights;
	}
			
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
		
}