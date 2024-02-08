<?php

namespace StudentContribution\Model;

class StudentContribution
{
	protected $id;
	protected $contribution_date;
	protected $contribution_type;
	protected $remarks;
        protected $evidence_file;
	protected $student_id;
	
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getContribution_Date()
	{
		return $this->contribution_date;
	}
	
	public function setContribution_Date($contribution_date)
	{
		$this->contribution_date = $contribution_date;
	}
	
	public function getContribution_Type()
	{
		return $this->contribution_type;
	}
	
	public function setContribution_Type($contribution_type)
	{
		$this->contribution_type = $contribution_type;
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
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	
}