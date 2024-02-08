<?php

namespace EmpResignation\Model;

class EmpResignation
{
	protected $id;
	protected $resignation_type;
	protected $date_of_application;
	protected $reason_for_resignation;
	protected $remarks;
	protected $employee_details_id;
	protected $resignation_status;
	protected $date_of_issue;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getResignation_Type()
	{
		return $this->resignation_type;
	}
	
	public function setResignation_Type($resignation_type)
	{
		$this->resignation_type = $resignation_type;
	}
	
	public function getDate_Of_Application()
	{
		return $this->date_of_application;
	}
	
	public function setDate_Of_Application($date_of_application)
	{
		$this->date_of_application = $date_of_application;
	}
	
	public function getReason_For_Resignation()
	{
		return $this->reason_for_resignation;
	}
	
	public function setReason_For_Resignation($reason_for_resignation)
	{
		$this->reason_for_resignation = $reason_for_resignation;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}

	public function getResignation_Status()
	{
		return $this->resignation_status;
	}
	
	public function setResignation_Status($resignation_status)
	{
		$this->resignation_status = $resignation_status;
	}

	public function getDate_Of_Issue()
	{
		return $this->date_of_issue;
	}
	
	public function setDate_Of_Issue($date_of_issue)
	{
		$this->date_of_issue = $date_of_issue;
	}
	
}