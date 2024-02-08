<?php

namespace Alumni\Model;

class Alumni
{
	protected $id;
    protected $alumni_status;

    protected $fk_student_id;
	protected $alumni_programmes_id;	 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	  public function getAlumni_Status()
	 {
		return $this->alumni_status; 
	 }
	 	 
	 public function setAlumni_Status($alumni_status)
	 {
		 $this->alumni_status = $alumni_status;
	 }

	  public function getStudent_Id()
	 {
		return $this->student_id; 
	 }
	 	 
	 public function setStudent_Id($student_id)
	 {
		 $this->student_id = $fk_student_id;
	 }

	 public function getAlumni_Programmes_Id()
	 {
		return $this->alumni_programmes_id; 
	 }
	 	 
	 public function setAlumni_Programmes_Id($alumni_programmes_id)
	 {
		 $this->alumni_programmes_id = $alumni_programmes_id;
	 }
}
