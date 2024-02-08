<?php

namespace StudentAdmission\Model;

/*Model for student registration update done from College  */
class StudentPreviousSchool
{
	protected $id;
	protected $student_id;
	protected $enrollment_year;
	protected $programme_name;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
    protected $student_type;
    protected $previous_institution;
    protected $aggregate_marks_obtained;
    protected $from_date;
    protected $to_date;
    protected $previous_education;

    public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getStudent_Id()
	 {
		return $this->student_id; 
	 }
	 	 
	 public function setStudent_Id($student_id)
	 {
		 $this->student_id = $student_id;
	 }

	 public function getEnrollment_Year()
	 {
		return $this->enrollment_year; 
	 }
	 	 
	 public function setEnrollment_Year($enrollment_year)
	 {
		 $this->enrollment_year = $enrollment_year;
	 }

	 public function getProgramme_Name()
	 {
		return $this->programme_name; 
	 }
	 	 
	 public function setProgramme_Name($programme_name)
	 {
		 $this->programme_name = $programme_name;
	 }

	  public function getFirst_Name()
	 {
		return $this->first_name; 
	 }
	 	 
	 public function setFirst_Name($first_name)
	 {
		 $this->first_name = $first_name;
	 }

	 public function getMiddle_Name()
	 {
		return $this->middle_name; 
	 }
	 	 
	 public function setMiddle_Name($middle_name)
	 {
		 $this->middle_name = $middle_name;
	 }
	 
	 public function getLast_Name()
	 {
		return $this->last_name; 
	 }
	 	 
	 public function setLast_Name($last_name)
	 {
		 $this->last_name = $last_name;
	 }

	 public function getPrevious_Institution()
	 {
		return $this->previous_institution; 
	 }
	 	 
	public function setPrevious_Institution($previous_institution)
	 {
		 $this->previous_institution = $previous_institution;
	 }

	public function getAggregate_Marks_Obtained()
	 {
		return $this->aggregate_marks_obtained; 
	 }
	 	 
	public function setAggregate_Marks_Obtained($aggregate_marks_obtained)
	 {
		 $this->aggregate_marks_obtained = $aggregate_marks_obtained;
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

	 public function getPrevious_Education()
	 {
		return $this->previous_education; 
	 }

	public function setPrevious_Education($previous_education)
	 {
		 $this->previous_education = $previous_education;
	 }
}
