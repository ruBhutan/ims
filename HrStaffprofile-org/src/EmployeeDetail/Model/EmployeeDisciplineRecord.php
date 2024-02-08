<?php

namespace EmployeeDetail\Model;

class EmployeeDisciplineRecord
{
	protected $id;
	protected $record_date;
        protected $disciplinary_details;
        protected $evidence_file;
	protected $remarks;
	protected $employee_details_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 	
	public function getRecord_Date()
	{
		return $this->record_date;
	}
	
	public function setRecord_Date($record_date)
	{
		$this->record_date = $record_date;
	}
	
	public function getDisciplinary_Details()
	{
		return $this->disciplinary_details;
	}
	
	public function setDisciplinary_Details($disciplinary_details)
	{
		$this->disciplinary_details = $disciplinary_details;
	}
        
        public function getEvidence_File()
        {
                return $this->evidence_file;
        }
        
        public function setEvidence_File($evidence_file)
        {
                $this->evidence_file = $evidence_file;
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
}