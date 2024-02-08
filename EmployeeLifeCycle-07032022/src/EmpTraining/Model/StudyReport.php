<?php

namespace EmpTraining\Model;

class StudyReport
{
	protected $id;
	protected $marks_obtained;
	protected $study_status;
	protected $award_name;
	protected $joining_report;
	protected $feedback_form;
	protected $certificates;
	protected $marksheets;
	protected $remarks;
	protected $reported_date;
	protected $employee_details_id;
	protected $training_details_id;
	 	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getMarks_Obtained()
	{
		return $this->marks_obtained;
	}
	
	public function setMarks_Obtained($marks_obtained)
	{
		$this->marks_obtained = $marks_obtained;
	}
	
	public function getStudy_Status()
	{
		return $this->study_status;
	}
	
	public function setStudy_Status($study_status)
	{
		$this->study_status = $study_status;
	}
	
	public function getAward_Name()
	{
		return $this->award_name;
	}
	
	public function setAward_Name($award_name)
	{
		$this->award_name = $award_name;
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
	
	public function getCertificates()
	{
		return $this->certificates;
	}
	
	public function setCertificates($certificates)
	{
		$this->certificates = $certificates;
	}
	
	public function getMarksheets()
	{
		return $this->marksheets;
	}
	
	public function setMarksheets($marksheets)
	{
		$this->marksheets = $marksheets;
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
	
	public function getTraining_Details_Id()
	{
		return $this->training_details_id;
	}
	
	public function setTraining_Details_Id($training_details_id)
	{
		$this->training_details_id = $training_details_id;
	}
	
}