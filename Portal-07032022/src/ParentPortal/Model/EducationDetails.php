<?php

namespace JobPortal\Model;

class EducationDetails
{
	protected $id;
	protected $college_name;
	protected $college_location;
	protected $college_country;
	protected $field_study;
	protected $subject_studied;
	protected $completion_year;
	protected $result_obtained;
	protected $certificate_obtained;
	protected $remarks;
	protected $job_applicant_id;
	 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getCollege_Name()
	 {
		 return $this->college_name;
	 }
	 
	 public function setCollege_Name($college_name)
	 {
		 $this->college_name = $college_name;
	 }
	 
	 public function getCollege_Location()
	 {
		 return $this->college_location;
	 }
	 
	 public function setCollege_Location($college_location)
	 {
		 $this->college_location = $college_location;
	 }
	 
	 public function getField_Study()
	 {
		 return $this->field_study;
	 }
	 
	 public function setField_Study($field_study)
	 {
		 $this->field_study = $field_study;
	 }
	 
	 public function getSubject_Studied()
	 {
		 return $this->subject_studied;
	 }
	 
	 public function setSubject_Studied($subject_studied)
	 {
		 $this->subject_studied = $subject_studied;
	 }
	 
	 public function getCompletion_Year()
	 {
		 return $this->completion_year;
	 }
	 
	 public function setCompletion_Year($completion_year)
	 {
		 $this->completion_year = $completion_year;
	 }
	 
	 public function getResult_Obtained()
	 {
		 return $this->result_obtained;
	 }
	 
	 public function setResult_Obtained($result_obtained)
	 {
		 $this->result_obtained = $result_obtained;
	 }
	 
	 public function getCertificate_Obtained()
	 {
		 return $this->certificate_obtained;
	 }
	 
	 public function setCertificate_Obtained($certificate_obtained)
	 {
		 $this->certificate_obtained = $certificate_obtained;
	 }
	 
	 public function getRemarks()
	 {
		return $this->remarks;
	 }
	
	 public function setRemarks($remarks)
	 {
		$this->remarks = $remarks;
	 }
	 
	 public function getJob_Applicant_Id()
	 {
		return $this->job_applicant_id;
	 }
	
	 public function setJob_Applicant_Id($job_applicant_id)
	 {
		$this->job_applicant_id = $job_applicant_id;
	 }

}