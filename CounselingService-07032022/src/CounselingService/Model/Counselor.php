<?php

namespace CounselingService\Model;

class Counselor
{
	protected $id;
	protected $employee_details_id;
	protected $organisation_id;
	protected $status;
	protected $remarks;
	protected $appointment_date;
	
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
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}

	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}

	public function getAppointment_Date()
	{
		return $this->appointment_date;
	}
	
	public function setAppointment_Date($appointment_date)
	{
		$this->appointment_date = $appointment_date;
	}
}