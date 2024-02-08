<?php
//This model is to handle new employees details. Will not be used for anything else.

namespace EmployeeDetail\Model;

class NewEmployeeAdditionalDetail
{
	protected $employee_details_id;
	protected $newrelationdetails;
	protected $newworkexperiencedetails;
	protected $newtrainingdetails;
	protected $newpublicationdetails;
	protected $neweducationdetails;
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}
	
	public function getNewrelationdetails()
	{
		return $this->newrelationdetails;
	}
	
	public function setNewrelationdetails($newrelationdetails)
	{
		$this->newrelationdetails = $newrelationdetails;
	}
	
	public function getNewworkexperiencedetails()
	{
		return $this->newworkexperiencedetails;
	}
	
	public function setNewworkexperiencedetails($newworkexperiencedetails)
	{
		$this->newworkexperiencedetails = $newworkexperiencedetails;
	}
	
	public function getNewtrainingdetails()
	{
		return $this->newtrainingdetails;
	}
	
	public function setNewtrainingdetails($newtrainingdetails)
	{
		$this->newtrainingdetails = $newtrainingdetails;
	}
	
	public function getNewpublicationdetails()
	{
		return $this->newpublicationdetails;
	}
	
	public function setNewpublicationdetails($newpublicationdetails)
	{
		$this->newpublicationdetails = $newpublicationdetails;
	}
	
	public function getNeweducationdetails()
	{
		return $this->neweducationdetails;
	}
	
	public function setNeweducationdetails($neweducationdetails)
	{
		$this->neweducationdetails = $neweducationdetails;
	} 
}