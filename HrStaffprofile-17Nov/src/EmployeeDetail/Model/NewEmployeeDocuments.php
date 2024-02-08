<?php

namespace EmployeeDetail\Model;

class NewEmployeeDocuments
{
	protected $id;
	protected $passport_photo;
	protected $identity_proof;
	protected $security_clearance_file;
	protected $medical_clearance_file;
	protected $other_certificate_file;
	protected $employee_details_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getPassport_Photo()
	{
		return $this->passport_photo;
	}
	
	public function setPassport_Photo($passport_photo)
	{
		$this->passport_photo = $passport_photo;
	}
	
	public function getIdentity_Proof()
	{
		return $this->identity_proof;
	}
	
	public function setIdentity_Proof($identity_proof)
	{
		$this->identity_proof = $identity_proof;
	}
		
	public function getSecurity_Clearance_File()
	{
		return $this->security_clearance_file;
	}
	
	public function setSecurity_Clearance_File($security_clearance_file)
	{
		$this->security_clearance_file = $security_clearance_file;
	}
		
	public function getMedical_Clearance_File()
	{
		return $this->medical_clearance_file;
	}
	
	public function setMedical_Clearance_File($medical_clearance_file)
	{
		$this->medical_clearance_file = $medical_clearance_file;
	}
		
	public function getOther_Certificate_File()
	{
		return $this->other_certificate_file;
	}
	
	public function setOther_Certificate_File($other_certificate_file)
	{
		$this->other_certificate_file = $other_certificate_file;
	}
		
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}
		
}