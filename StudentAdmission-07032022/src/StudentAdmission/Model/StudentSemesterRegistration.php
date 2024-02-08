<?php

namespace StudentAdmission\Model;

/*Main Model for Student Admission Controller  */
class StudentSemesterRegistration
{
	protected $id;
	protected $student_id;
	protected $studentId;
	protected $first_name;
	protected $middle_name;
	protected $last_name;

	protected $semester;
	protected $academic_year;
	protected $semester_id;
	protected $section;
	protected $student_section_id;
	protected $student_status_type_id;
	protected $remarks;
	protected $file;
	protected $year;
	protected $year_id;

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

	 public function getStudentId()
	 {
		return $this->studentId; 
	 }
	 	 
	 public function setStudentId($studentId)
	 {
		 $this->studentId = $studentId;
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

	 public function getSemester()
	 {
		return $this->semester; 
	 }
	 	 
	 public function setSemester($semester)
	 {
		 $this->semester = $semester;
	 }

	 public function getAcademic_Year()
	 {
		return $this->academic_year; 
	 }
	 	 
	 public function setAcademic_Year($academic_year)
	 {
		 $this->academic_year = $academic_year;
	 }

	 public function getSemester_Id()
	 {
		return $this->semester_id; 
	 }
	 	 
	 public function setSemester_Id($semester_id)
	 {
		 $this->semester_id = $semester_id;
	 }

	 public function getSection()
	 {
		return $this->section; 
	 }
	 	 
	 public function setSection($section)
	 {
		 $this->section = $section;
	 }

	 public function getStudent_Section_Id()
	 {
		return $this->student_section_id; 
	 }
	 	 
	 public function setStudent_Section_Id($student_section_id)
	 {
		 $this->student_section_id = $student_section_id;
	 }

	 public function getStudent_Status_Type_Id()
	 {
		return $this->student_status_type_id; 
	 }
	 	 
	 public function setStudent_Status_Type_Id($student_status_type_id)
	 {
		 $this->student_status_type_id = $student_status_type_id;
	 }

	 public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }


	 public function getFile()
	 {
		return $this->file; 
	 }
	 	 
	 public function setFile($file)
	 {
		 $this->file = $file;
	 }

	 public function getYear()
	 {
		return $this->year; 
	 }
	 	 
	 public function setYear($year)
	 {
		 $this->year = $year;
	 }

	 public function getYear_Id()
	 {
		return $this->year_id; 
	 }
	 	 
	 public function setYear_Id($year_id)
	 {
		 $this->year_id = $year_id;
	 }
}