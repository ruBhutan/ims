<?php

namespace JobApplicant\Model;

class JobApplication
{
	protected $id;
	protected $agreement;
    protected $name;
    protected $title;
    protected $position;
    protected $organisation;
    protected $relation_applicant;
    protected $telephone;
    protected $mobile;
    protected $email;
	protected $identity_proof;
	protected $security_clearance_no;
	protected $security_clearance_file;
	protected $medical_clearance_no;
	protected $medical_clearance_file;
	protected $audit_clearance_no;
	protected $audit_clearance_file;
	protected $tax_clearance_no;
	protected $tax_clearance_file;
	protected $other_certificate_description;
	protected $other_certificate_file;
	protected $employee_details_id;
	protected $vacancy_announcements_id;
	protected $application_date;
	protected $job_applicant_id;
	protected $status;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	protected function getAgreement()
	{
		return $this->agreement;
	}
	
	public function setAgreement($agreement)
	{
		$this->agreement = $agreement;
	}
        
        public function getName()
        {
                return $this->name;
        }
        
        public function setName($name)
        {
                $this->name = $name;
        }
        
        public function getTitle()
        {
                return $this->title;
        }
        
        public function setTitle($title)
        {
                $this->title = $title;
        }
        
        public function getPosition()
        {
                return $this->position;
        }
        
        public function setPosition($position)
        {
                $this->position = $position;
        }
        
        public function getOrganisation()
        {
                return $this->organisation;
        }
        
        public function setOrganisation($organisation)
        {
                $this->organisation = $organisation;
        }
        
        public function getRelation_Applicant()
        {
                return $this->relation_applicant;
        }
        
        public function setRelation_Applicant($relation_applicant)
        {
                $this->relation_applicant = $relation_applicant;
        }
        
        public function getTelephone()
        {
                return $this->telephone;
        }
        
        public function setTelephone($telephone)
        {
                $this->telephone = $telephone;
        }

        public function getMobile()
        {
                return $this->mobile;
        }
        
        public function setMobile($mobile)
        {
                $this->mobile = $mobile;
        }
        
        
        public function getEmail()
        {
                return $this->email;
        }
        
        public function setEmail($email)
        {
                $this->email = $email;
        }
        
	
	public function getIdentity_Proof()
	{
		return $this->identity_proof;
	}
	
	public function setIdentity_Proof($identity_proof)
	{
		$this->identity_proof = $identity_proof;
	}
	
	public function getSecurity_Clearance_No()
	{
		return $this->security_clearance_no;
	}
	
	public function setSecurity_Clearance_No($security_clearance_no)
	{
		$this->security_clearance_no = $security_clearance_no;
	}
	
	public function getSecurity_Clearance_File()
	{
		return $this->security_clearance_file;
	}
	
	public function setSecurity_Clearance_File($security_clearance_file)
	{
		$this->security_clearance_file = $security_clearance_file;
	}
	
	public function getMedical_Clearance_No()
	{
		return $this->medical_clearance_no;
	}
	
	public function setMedical_Clearance_No($medical_clearance_no)
	{
		$this->medical_clearance_no = $medical_clearance_no;
	}
	
	public function getMedical_Clearance_File()
	{
		return $this->medical_clearance_file;
	}
	
	public function setMedical_Clearance_File($medical_clearance_file)
	{
		$this->medical_clearance_file = $medical_clearance_file;
	}
	
	public function getAudit_Clearance_No()
	{
		return $this->audit_clearance_no;
	}
	
	public function setAudit_Clearance_No($audit_clearance_no)
	{
		$this->audit_clearance_no = $audit_clearance_no;
	}
	
	public function getAudit_Clearance_File()
	{
		return $this->audit_clearance_file;
	}
	
	public function setAudit_Clearance_File($audit_clearance_file)
	{
		$this->audit_clearance_file = $audit_clearance_file;
	}
	
	public function getTax_Clearance_No()
	{
		return $this->tax_clearance_no;
	}
	
	public function setTax_Clearance_No($tax_clearance_no)
	{
		$this->tax_clearance_no = $tax_clearance_no;
	}
	
	public function getTax_Clearance_File()
	{
		return $this->tax_clearance_file;
	}
	
	public function setTax_Clearance_File($tax_clearance_file)
	{
		$this->tax_clearance_file = $tax_clearance_file;
	}
	
	public function getOther_Certificate_Description()
	{
		return $this->other_certificate_description;
	}
	
	public function setOther_Certificate_Description($other_certificate_description)
	{
		$this->other_certificate_description = $other_certificate_description;
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
	
	public function getVacancy_Announcements_Id()
	{
		return $this->vacancy_announcements_id;
	}
	
	public function setVacancy_Announcements_Id($vacancy_announcements_id)
	{
		$this->vacancy_announcements_id = $vacancy_announcements_id;
	}

	public function getApplication_Date()
	{
		return $this->application_date;
	}
	
	public function setApplication_Date($application_date)
	{
		$this->application_date = $application_date;
	}
	
	public function getJob_Applicant_Id()
	{
		return $this->job_applicant_id;
	}
	
	public function setJob_Applicant_Id($job_applicant_id)
	{
		$this->job_applicant_id = $job_applicant_id;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
}