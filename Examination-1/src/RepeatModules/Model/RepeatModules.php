<?php

namespace RepeatModules\Model;

class RepeatModules
{
	protected $id;
	protected $previous_semester_marks;
	protected $previous_ca_marks;
	protected $payment_status;
	protected $backlog_semester;
	protected $application_date;
	protected $academic_modules_allocation_id;
	protected $student_id;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getPrevious_Semester_Marks()
	{
		return $this->previous_semester_marks;
	}
	
	public function setPrevious_Semester_Marks($previous_semester_marks)
	{
		$this->previous_semester_marks = $previous_semester_marks;
	}
	
	public function getPrevious_Ca_Marks()
	{
		return $this->previous_ca_marks;
	}
	
	public function setPrevious_Ca_Marks($previous_ca_marks)
	{
		$this->previous_ca_marks = $previous_ca_marks;
	}
	
	public function getPayment_Status()
	{
		return $this->payment_status;
	}
	
	public function setPayment_Status($payment_status)
	{
		$this->payment_status = $payment_status;
	}
	
	public function getBacklog_Semester()
	{
		return $this->backlog_semester;
	}
	
	public function setBacklog_Semester($backlog_semester)
	{
		$this->backlog_semester = $backlog_semester;
	}
	
	public function getApplication_Date()
	{
		return $this->application_date;
	}
	
	
	public function setApplication_Date($application_date)
	{
		$this->application_date = $application_date;
	}
	
	public function getAcademic_Modules_Allocation_Id()
	{
		return $this->academic_modules_allocation_id;
	}
	
	public function setAcademic_Modules_Allocation_Id($academic_modules_allocation_id)
	{
		$this->academic_modules_allocation_id = $academic_modules_allocation_id;
	}
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	 
}