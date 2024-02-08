<?php

namespace Hostel\Model;

class HostelAllocation
{
	protected $id;
	protected $hostel_name;
	protected $yearwise;
	protected $branchwise;
		
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
	
	public function getYearwise()
	{
		return $this->yearwise;
	}
	
	public function setYearwise($yearwise)
	{
		$this->yearwise = $yearwise;
	}
	
	public function getBranchwise()
	{
		return $this->branchwise;
	}
	
	public function setBranchwise($branchwise)
	{
		$this->branchwise = $branchwise;
	}
}