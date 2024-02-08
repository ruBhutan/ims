<?php

namespace StudentAdmission\Model;

/*Model for student registration update done from College  */
class UpdateChangeProgramme
{
	protected $id;
	protected $applied_date;
	protected $updated_date;
	protected $student_id;
	protected $previous_programme;
	protected $applied_programme;
	protected $status;
	protected $reason;
	protected $remarks;
	protected $updated_by;

	protected $programme_name;
	protected $programmes_id;

	protected $pprogramme_name;
	protected $aprogramme_name;

    public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	 public function getApplied_Date()
	 {
		 return $this->applied_date;
	 }
	 
	 public function setApplied_Date($applied_date)
	 {
		 $this->applied_date = $applied_date;
	 }


	 public function getUpdated_Date()
	 {
		 return $this->updated_date;
	 }
	 
	 public function setUpdated_Date($updated_date)
	 {
		 $this->updated_date = $updated_date;
	 }

	 
	 public function getStudent_Id()
	 {
		return $this->student_id; 
	 }
	 	 
	 public function setStudent_Id($student_id)
	 {
		 $this->student_id = $student_id;
	 }

	 public function getPrevious_Programme()
	 {
		return $this->previous_programme; 
	 }
	 	 
	 public function setPrevious_Programme($previous_programme)
	 {
		 $this->previous_programme = $previous_programme;
	 }

	 public function getApplied_Programme()
	 {
		return $this->applied_programme; 
	 }
	 	 
	 public function setApplied_Programme($applied_programme)
	 {
		 $this->applied_programme = $applied_programme;
	 }

	  public function getStatus()
	 {
		return $this->status; 
	 }
	 	 
	 public function setStatus($status)
	 {
		 $this->status = $status;
	 }

	 public function getReason()
	 {
		return $this->reason; 
	 }
	 	 
	 public function setReason($reason)
	 {
		 $this->reason = $reason;
	 }

	 public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }

	 public function getUpdated_By()
	 {
		return $this->updated_by; 
	 }
	 	 
	 public function setUpdated_By($updated_by)
	 {
		 $this->updated_by = $updated_by;
	 }

	 public function getProgramme_Name()
	 {
		return $this->programme_name; 
	 }
	 	 
	 public function setProgramme_Name($programme_name)
	 {
		 $this->programme_name = $programme_name;
	 }

	 public function getProgrammes_Id()
	 {
		return $this->programmes_id; 
	 }
	 	 
	 public function setProgrammes_Id($programmes_id)
	 {
		 $this->programmes_id = $programmes_id;
	 }


	 public function getPprogramme_Name()
	 {
		return $this->pprogramme_name; 
	 }
	 	 
	 public function setPprogramme_Name($pprogramme_name)
	 {
		 $this->pprogramme_name = $pprogramme_name;
	 }


	 public function getAprogramme_Name()
	 {
		return $this->aprogramme_name; 
	 }
	 	 
	 public function setAprogramme_Name($aprogramme_name)
	 {
		 $this->aprogramme_name = $aprogramme_name;
	 }
}


