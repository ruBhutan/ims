<?php

namespace EmployeeTask\Model;

class EmployeeTask
{
	protected $id;
	protected $from_date;
	protected $to_date;
	protected $recorded_by;
   	protected $evidence_file;
	protected $staff_id;
	protected $from_time;
	protected $to_time;
	protected $employeetask_category_id;
	protected $employeetask_type;
	protected $status;
	protected $employeetask_details;
	
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
	
	public function getRecorded_By()
	{
		return $this->recorded_by;
	}
	
	public function setRecorded_By($recorded_by)
	{
		$this->recorded_by = $recorded_by;
	}
	
	public function getEmployeeTask_Details()
	{
		return $this->employeetask_details;
	}
	
	public function setEmployeeTask_Details($employeetask_details)
	{
		$this->employeetask_details = $employeetask_details;
	}

	public function getFrom_Time()
	{
		return $this->from_time;
	}
	
	public function setFrom_Time($from_time)
	{
		$this->from_time = $from_time;
	}

	public function getTo_Time()
	{
		return $this->to_time;
	}
	
	public function setTo_Time($to_time)
	{
		$this->to_time = $to_time;
	}
        
    public function getEvidence_File()
    {
            return $this->evidence_file;
    }
    
    public function setEvidence_File($evidence_file)
    {
            $this->evidence_file = $evidence_file;
    }
	
	public function getStaff_Id()
	{
		return $this->staff_id;
	}
	
	public function setStaff_Id($staff_id)
	{
		$this->staff_id = $staff_id;
	}

	public function getEmployeeTask_Type()
	{
		return $this->employeetask_type;
	}
	
	public function setEmployeeTask_Type($employeetask_type)
	{
		$this->employeetask_type = $employeetask_type;
	}
	
	public function getEmployeeTask_Category_Id()
	{
		return $this->employeetask_category_id;
	}

	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function setEmployeeTask_Category_Id($employeetask_category_id)
	{
		$this->employeetask_category_id = $employeetask_category_id;
	}

}