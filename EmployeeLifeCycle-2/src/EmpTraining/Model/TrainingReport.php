<?php

namespace EmpTraining\Model;

class TrainingReport
{
	protected $id;
	protected $joining_report;
	protected $feedback_form;
    protected $remarks;
    protected $reported_date;
	protected $employee_details_id;
	protected $workshop_details_id;
	 	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getJoining_Report()
	{
		return $this->joining_report;
	}
	
	public function setJoining_Report($joining_report)
	{
		$this->joining_report = $joining_report;
	}
		
	public function getFeedback_Form()
	{
		return $this->feedback_form;
	}
	
	public function setFeedback_Form($feedback_form)
	{
		$this->feedback_form = $feedback_form;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}

	public function getReported_Date()
	{
		return $this->reported_date;
	}
	
	public function setReported_Date($reported_date)
	{
		$this->reported_date = $reported_date;
	}


	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}
	
	public function getWorkshop_Details_Id()
	{
		return $this->workshop_details_id;
	}
	
	public function setWorkshop_Details_Id($workshop_details_id)
	{
		$this->workshop_details_id = $workshop_details_id;
	}
}