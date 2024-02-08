<?php

namespace StudentAdmission\Model;

class StudentRelationDetails
{
	protected $id;
	protected $student_id;
	protected $programme_name;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
    protected $student_type;
	protected $parent_name;
	protected $parent_cid;
	protected $parent_nationality;
	protected $parent_dzongkhag;
	protected $parent_occupation;
    protected $parent_address;
    protected $parent_contact_no;
    protected $relation_type;

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

	public function getParent_Name()
	{
		return $this->parent_name;
	}

	public function setParent_Name($parent_name)
	{
		$this->parent_name = $parent_name;
	}

	public function getParent_Cid()
	{
		return $this->parent_cid;
	}

	public function setParent_Cid($parent_cid)
	{
		$this->parent_cid = $parent_cid;
	}

	public function getParent_Nationality()
	{
		return $this->parent_nationality;
	}

	public function setParent_Nationality($parent_nationality)
	{
		$this->parent_nationality = $parent_nationality;
	}

	public function getParent_Dzongkhag()
	{
		return $this->parent_dzongkhag;
	}

	public function setParent_Dzongkhag($parent_dzongkhag)
	{
		$this->parent_dzongkhag = $parent_dzongkhag;
	}

	public function getParent_Occupation()
	{
		return $this->parent_occupation;
	}

	public function setParent_Occupation($parent_occupation)
	{
		$this->parent_occupation = $parent_occupation;
	}

	public function getParent_Address()
	{
		return $this->parent_address;
	}

	public function setParent_Address($parent_address)
	{
		$this->parent_address = $parent_address;
	}

	public function getParent_Contact_No()
	{
		return $this->parent_contact_no; 	
	}

	public function setParent_Contact_No($parent_contact_no)
	{
		$this->parent_contact_no = $parent_contact_no;
	}

	public function getRelation_Type()
	{
		return $this->relation_type;
	}

	public function setRelation_Type($relation_type)
	{
		$this->relation_type = $relation_type;
	}

}