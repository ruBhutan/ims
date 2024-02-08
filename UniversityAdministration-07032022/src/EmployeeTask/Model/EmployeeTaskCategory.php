<?php

namespace EmployeeTask\Model;

class EmployeeTaskCategory
{
	protected $id;
	protected $employee_task_category;
	protected $description;
	protected $status;
	protected $employee_details_id;
	protected $organisation_id;
	

	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 		
	public function getEmployee_Task_Category()
	{
		return $this->employee_task_category;
	}
	
	public function setEmployee_Task_Category($employeetask_category)
	{
		$this->employee_task_category = $employeetask_category;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
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
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}

}