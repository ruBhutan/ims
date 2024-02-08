<?php

namespace StudentAdmission\Model;

/*Model for student registration update done from College  */
class UpdateStudentGuardianDetails
{
	protected $id;
	protected $student_id;
	protected $programme_name;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
    protected $student_type;
	protected $guardian_name;
	protected $guardian_cid;
	protected $guardian_occupation;
	protected $guardian_relation;
	protected $guardian_address;
    protected $guardian_contact_no;
    protected $remarks;

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

	public function getStudent_Type()
	 {
		 return $this->student_type;
	 }
	 
	 public function setStudent_Type($student_type)
	 {
		 $this->student_type = $student_type;
	 }

	public function getGuardian_Name()
	 {
		return $this->guardian_name; 
	 }
	 	 
	public function setGuardian_Name($guardian_name)
	 {
		 $this->guardian_name = $guardian_name;
	 }

	 public function getGuardian_Cid()
	 {
		return $this->guardian_cid; 
	 }
	 	 
	public function setGuardian_Cid($guardian_cid)
	 {
		 $this->guardian_cid = $guardian_cid;
	 }

	public function getGuardian_Occupation()
	 {
		return $this->guardian_occupation; 
	 }
	 	 
	public function setGuardian_Occupation($guardian_occupation)
	 {
		 $this->guardian_occupation = $guardian_occupation;
	 }

	public function getGuardian_Relation()
	 {
		return $this->guardian_relation; 
	 }
	 	 
	public function setGuardian_Relation($guardian_relation)
	 {
		 $this->guardian_relation = $guardian_relation;
	 }

	public function getGuardian_Address()
	 {
		return $this->guardian_address; 
	 }
	 	 
	public function setGuardian_Address($guardian_address)
	 {
		 $this->guardian_address = $guardian_address;
	 }

	public function getGuardian_Contact_No()
	 {
		return $this->guardian_contact_no; 
	 }
	 	 
	public function setGuardian_Contact_No($guardian_contact_no)
	 {
		 $this->guardian_contact_no = $guardian_contact_no;
	 }

	public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 } 
}