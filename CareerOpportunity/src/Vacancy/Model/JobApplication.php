<?php

namespace Vacancy\Model;

class JobApplication
{
	protected $id;
	protected $agreement;
	protected $x_english;
	protected $x_sub1_mark;
	protected $x_sub2_mark;
	protected $x_sub3_mark;
	protected $x_sub4_mark;
	protected $xll_english;
	protected $xll_sub1_mark;
	protected $xll_sub2_mark;
	protected $xll_sub3_mark;
    protected $reference_name_1;
    protected $reference_title_1;
    protected $reference_position_1;
    protected $reference_organisation_1;
    protected $reference_relation_applicant_1;
    protected $reference_telephone_1;
    protected $reference_email_1;
    protected $reference_name_2;
    protected $reference_title_2;
    protected $reference_position_2;
    protected $reference_organisation_2;
    protected $reference_relation_applicant_2;
    protected $reference_telephone_2;
    protected $reference_email_2;
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
	protected $job_applicant_id;
	protected $application_date;
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

	
	public function getX_English()
	{
		return $this->x_english;
	}
	
	public function setX_English($x_english)
	{
		$this->x_english = $x_english;
	}

	public function getX_Sub1_Mark()
	{
		return $this->x_sub1_mark;
	}
	
	public function setX_Sub1_Mark($x_sub1_mark)
	{
		$this->x_sub1_mark = $x_sub1_mark;
	}

	public function getX_Sub2_Mark()
	{
		return $this->x_sub2_mark;
	}
	
	public function setX_Sub2_Mark($x_sub2_mark)
	{
		$this->x_sub2_mark = $x_sub2_mark;
	}
	
	public function getX_Sub3_Mark()
	{
		return $this->x_sub3_mark;
	}
	
	public function setX_Sub3_Mark($x_sub3_mark)
	{
		$this->x_sub3_mark = $x_sub3_mark;
	}

	public function getX_Sub4_Mark()
	{
		return $this->x_sub4_mark;
	}
	
	public function setX_Sub4_Mark($x_sub4_mark)
	{
		$this->x_sub4_mark = $x_sub4_mark;
	}

	public function getXll_English()
	{
		return $this->xll_english;
	}
	
	public function setXll_English($xll_english)
	{
		$this->xll_english = $xll_english;
	}
	
	public function getXll_Sub1_Mark()
	{
		return $this->xll_sub1_mark;
	}
	
	public function setXll_Sub1_Mark($xll_sub1_mark)
	{
		$this->xll_sub1_mark = $xll_sub1_mark;
	}

	public function getXll_Sub2_Mark()
	{
		return $this->xll_sub2_mark;
	}
	
	public function setXll_Sub2_Mark($xll_sub2_mark)
	{
		$this->xll_sub2_mark = $xll_sub2_mark;
	}

	public function getXll_Sub3_Mark()
	{
		return $this->xll_sub3_mark;
	}
	
	public function setXll_Sub3_Mark($xll_sub3_mark)
	{
		$this->xll_sub3_mark = $xll_sub3_mark;
	}

        
        public function getReference_Name_1()
        {
                return $this->reference_name_1;
        }
        
        public function setReference_Name_1($reference_name_1)
        {
                $this->reference_name_1 = $reference_name_1;
        }
        
        public function getReference_Title_1()
        {
                return $this->reference_title_1;
        }
        
        public function setReference_Title_1($reference_title_1)
        {
                $this->reference_title_1 = $reference_title_1;
        }
        
        public function getReference_Position_1()
        {
                return $this->reference_position_1;
        }
        
        public function setReference_Position_1($reference_position_1)
        {
                $this->reference_position_1 = $reference_position_1;
        }
        
        public function getReference_Organisation_1()
        {
                return $this->reference_organisation_1;
        }
        
        public function setReference_Organisation_1($reference_organisation_1)
        {
                $this->reference_organisation_1 = $reference_organisation_1;
        }
        
        public function getReference_Relation_Applicant_1()
        {
                return $this->reference_relation_applicant_1;
        }
        
        public function setReference_Relation_Applicant_1($reference_relation_applicant_1)
        {
                $this->reference_relation_applicant_1 = $reference_relation_applicant_1;
        }
        
        public function getReference_Telephone_1()
        {
                return $this->reference_telephone_1;
        }
        
        public function setReference_Telephone_1($reference_telephone_1)
        {
                $this->reference_telephone_1 = $reference_telephone_1;
        }
        
        public function getReference_Email_1()
        {
                return $this->reference_email_1;
        }
        
        public function setReference_Email_1($reference_email_1)
        {
                $this->reference_email_1 = $reference_email_1;
        }
        
        public function getReference_Name_2()
        {
                return $this->reference_name_2;
        }
        
        public function setReference_Name_2($reference_name_2)
        {
                $this->reference_name_2 = $reference_name_2;
        }
        
        public function getReference_Title_2()
        {
                return $this->reference_title_2;
        }
        
        public function setReference_Title_2($reference_title_2)
        {
                $this->reference_title_2 = $reference_title_2;
        }
        
        public function getReference_Position_2()
        {
                return $this->reference_position_2;
        }
        
        public function setReference_Position_2($reference_position_2)
        {
                $this->reference_position_2 = $reference_position_2;
        }
        
        public function getReference_Organisation_2()
        {
                return $this->reference_organisation_2;
        }
        
        public function setReference_Organisation_2($reference_organisation_2)
        {
                $this->reference_organisation_2 = $reference_organisation_2;
        }
        
        public function getReference_Relation_Applicant_2()
        {
                return $this->reference_relation_applicant_2;
        }
        
        public function setReference_Relation_Applicant_2($reference_relation_applicant_2)
        {
                $this->reference_relation_applicant_2 = $reference_relation_applicant_2;
        }
        
        public function getReference_Telephone_2()
        {
                return $this->reference_telephone_2;
        }
        
        public function setReference_Telephone_2($reference_telephone_2)
        {
                $this->reference_telephone_2 = $reference_telephone_2;
        }
        
        public function getReference_Email_2()
        {
                return $this->reference_email_2;
        }
        
        public function setReference_Email_2($reference_email_2)
        {
                $this->reference_email_2 = $reference_email_2;
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
	
	public function getJob_Applicant_Id()
	{
		return $this->job_applicant_id;
	}
	
	public function setJob_Applicant_Id($job_applicant_id)
	{
		$this->job_applicant_id = $job_applicant_id;
	}

	public function getApplication_Date()
	{
		return $this->application_date;
	}
	
	public function setApplication_Date($application_date)
	{
		$this->application_date = $application_date;
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