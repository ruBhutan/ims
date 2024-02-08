<?php

namespace StudentLeave\Model;

class StudentLeave
{
	protected $id;
	protected $from_date;
	protected $to_date;
	protected $outing_category;
	protected $remarks;
	protected $evidence_file;
	protected $reasons;
	protected $leave_status;
	protected $approved_by;
	protected $student_id;
	protected $student_leave_category_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 	
	public function getFrom_Date()
	{
		return $this->from_date;
	}
	
	public function setFrom_Date($from_date)
	{
		$this->from_date = $from_date;
	}
	
	public function getTo_Date()
	{
		return $this->to_date;
	}
	
	public function setTo_Date($to_date)
	{
		$this->to_date = $to_date;
	}

	public function getOuting_Category()
	{
		return $this->outing_category;
	}
	
	public function setOuting_Category($outing_category)
	{
		$this->outing_category = $outing_category;
	}
	
	public function getReasons()
	{
		return $this->reasons;
	}
	
	public function setReasons($reasons)
	{
		$this->reasons = $reasons;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getEvidence_File()
	{
		return $this->evidence_file;
	}
	
	public function setEvidence_File($evidence_file)
	{
		$this->evidence_file = $evidence_file;
	}
	
	public function getLeave_Status()
	{
		return $this->leave_status;
	}
	
	public function setLeave_Status($leave_status)
	{
		$this->leave_status = $leave_status;
	}
	
	public function getApproved_By()
	{
		return $this->approved_by;
	}
	
	public function setApproved_By($approved_by)
	{
		$this->approved_by = $approved_by;
	}
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	
	public function getStudent_Leave_Category_Id()
	{
		return $this->student_leave_category_id;
	}
	
	public function setStudent_Leave_Category_Id($student_leave_category_id)
	{
		$this->student_leave_category_id = $student_leave_category_id;
	}
}