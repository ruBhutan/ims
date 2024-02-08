<?php

namespace EmpTraining\Model;

class TrainingNomination
{
	protected $id;
	protected $training_detail;
	protected $workshop_details_id;
	protected $training_details_id;	
	protected $employee_details_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getTraining_Detail()
	{
		return $this->training_detail;
	}
	
	public function setTraining_Detail($training_detail)
	{
		$this->training_detail = $training_detail;
	}
	
	public function getWorkshop_Details_Id()
	{
		return $this->workshop_details_id;
	}
	
	public function setWorkshop_Details_Id($workshop_details_id)
	{
		$this->workshop_details_id = $workshop_details_id;
	}
	
	public function getTraining_Details_Id()
	{
		return $this->training_details_id;
	}
	
	public function setTraining_Details_Id($training_details_id)
	{
		$this->training_details_id = $training_details_id;
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