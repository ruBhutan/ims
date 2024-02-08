<?php

namespace Nominations\Model;

class Nominations
{
	protected $id;
	protected $nominee;
	protected $nomination_type;
	protected $status;
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
	
	public function getNomination_Type()
	{
		return $this->nomination_type;
	}
	
	public function setNomination_Type($nomination_type)
	{
		$this->nomination_type = $nomination_type;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
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