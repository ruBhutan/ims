<?php

namespace EmpPromotion\Model;

class EmpPromotion
{
	protected $id;
	protected $promotion_type;
	protected $security_clearance_no;
	protected $security_clearance_file;
	protected $audit_clearance_no;
	protected $audit_clearance_file;
	protected $other_certificate_description;
	protected $other_certificate_file;
    protected $meritorious_promotion_file;
	protected $recommended_position;
	protected $proposed_position;
	protected $years_service_from_appointment;
	protected $years_service_from_promotion;
	protected $job_requirements_remarks;
	protected $promotion_status;
	protected $employee_details_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getPromotion_Type()
	{
		return $this->promotion_type;
	}
	
	public function setPromotion_Type($promotion_type)
	{
		$this->promotion_type = $promotion_type;
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
        
        public function getMeritorious_Promotion_File()
	{
		return $this->meritorious_promotion_file;
	}
	
	public function setMeritorious_Promotion_File($meritorious_promotion_file)
	{
		$this->meritorious_promotion_file = $meritorious_promotion_file;
	}
	
	public function getRecommended_Position()
	{
		return $this->recommended_position;
	}
	
	public function setRecommended_Position($recommended_position)
	{
		$this->recommended_position = $recommended_position;
	}
	
	public function getProposed_Position()
	{
		return $this->proposed_position;
	}
	
	public function setProposed_Position($proposed_position)
	{
		$this->proposed_position = $proposed_position;
	}
	
	public function getYears_Service_From_Appointment()
	{
		return $this->years_service_from_appointment;
	}
	
	public function setYears_Service_From_Appointment($years_service_from_appointment)
	{
		$this->years_service_from_appointment = $years_service_from_appointment;
	}
	
	public function getYears_Service_From_Promotion()
	{
		return $this->years_service_from_promotion;
	}
	
	public function setYears_Service_From_Promotion($years_service_from_promotion)
	{
		$this->years_service_from_promotion = $years_service_from_promotion;
	}
	
	public function getJob_Requirements_Remarks()
	{
		return $this->job_requirements_remarks;
	}
	
	public function setJob_Requirements_Remarks($job_requirements_remarks)
	{
		$this->job_requirements_remarks = $job_requirements_remarks;
	}
	
	public function getPromotion_Status()
	{
		return $this->promotion_status;
	}
	
	public function setPromotion_Status($promotion_status)
	{
		$this->promotion_status = $promotion_status;
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