<?php

namespace GoodsTransaction\Model;

class NominateSubStore
{
	protected $id;
	protected $employee_details_id;
	protected $departments_id;
	protected $nomination_date;
	protected $status;

			 
	public function getId()
	{
     	return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	 
	 public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}

	/*public function getEmp_Id()
	{
		return $this->emp_id;
	}
	 
	 public function setEmp_Id($emp_id)
	{
		$this->emp_id = $emp_id;
	}*/

	public function getDepartments_Id()
	{
		return $this->departments_id;
	}
	 
	 public function setDepartments_Id($departments_id)
	{
		$this->departments_id = $departments_id;
	}

	public function getNomination_Date()
	{
		return $this->nomination_date;
	}

	public function setNomination_Date($nomination_date)
	{
		$this->nomination_date = $nomination_date;
	}

	public function getStatus()
	 {
	 	return $this->status;
	 }

	 public function setStatus($status)
	 {
	 	$this->status = $status;
	 }     
	 
}	