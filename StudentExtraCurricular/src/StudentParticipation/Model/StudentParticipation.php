<?php

namespace StudentParticipation\Model;

class StudentParticipation
{
	protected $id;
	protected $participation_date;
	protected $participation_type;
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
	
	public function getParticipation_Date()
	{
		return $this->participation_date;
	}
	
	public function setParticipation_Date($participation_date)
	{
		$this->participation_date = $participation_date;
	}
	
	public function getParticipation_Type()
	{
		return $this->participation_type;
	}
	
	public function setParticipation_Type($participation_type)
	{
		$this->participation_type = $participation_type;
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