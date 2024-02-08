<?php

namespace RecheckMarks\Model;

class RecheckMarks
{
	protected $id;
	protected $payment_status;
	protected $recheck_status;
	protected $payment_remarks;
	protected $recheck_remarks;
	protected $type;
	protected $application_date;
	protected $academic_modules_allocation_id;
	protected $student_id;
	protected $payment_status_updated_by;
	protected $recheck_status_updated_by;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getPayment_Status()
	{
		return $this->payment_status;
	}
	
	public function setPayment_Status($payment_status)
	{
		$this->payment_status = $payment_status;
	}

	public function getRecheck_Status()
	{
		return $this->recheck_status;
	}

	public function setRecheck_Status($recheck_status)
	{
		$this->recheck_status = $recheck_status;
	}

	public function getPayment_Remarks()
	{
		return $this->payment_remarks;
	}

	public function setPayment_Remarks($payment_remarks)
	{
		$this->payment_remarks = $payment_remarks;
	}

	public function getRecheck_Remarks()
	{
		return $this->recheck_remarks;
	}

	public function setRecheck_Remarks($recheck_remarks)
	{
		$this->recheck_remarks = $recheck_remarks;
	}

	public function getType()
	{
		return $this->type;
	}
	
	public function setType($type)
	{
		$this->type = $type;
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

	public function getPayment_Status_Updated_By()
	{
		return $this->payment_status_updated_by;
	}

	public function setPayment_Status_Updated_By($payment_status_updated_by)
	{
		$this->payment_status_updated_by = $payment_status_updated_by;
	}

	public function getRecheck_Status_Updated_By()
	{
		return $this->recheck_status_updated_by;
	}

	public function setRecheck_Status_Updated_By($recheck_status_updated_by)
	{
		$this->recheck_status_updated_by = $recheck_status_updated_by;
	}
	 
}