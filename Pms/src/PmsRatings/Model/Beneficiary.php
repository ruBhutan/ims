<?php

namespace PmsRatings\Model;

class Beneficiary
{
	protected $id;
	protected $nominee;
	protected $employee_details_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getNominee()
	{
		return $this->nominee;
	}
	
	public function setNominee($nominee)
	{
		$this->nominee = $nominee;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}
	
}