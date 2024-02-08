<?php

namespace Discipline\Model;

class Discipline
{
	protected $id;
	protected $record_date;
	protected $recorded_by;
        protected $evidence_file;
	protected $student_id;
	protected $discipline_category_id;
	protected $disciplinary_details;
	
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
	
	public function getRecorded_By()
	{
		return $this->recorded_by;
	}
	
	public function setRecorded_by($recorded_by)
	{
		$this->recorded_by = $recorded_by;
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
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	
	public function getDiscipline_Category_Id()
	{
		return $this->discipline_category_id;
	}
	
	public function setDiscipline_Category_Id($discipline_category_id)
	{
		$this->discipline_category_id = $discipline_category_id;
	}
}