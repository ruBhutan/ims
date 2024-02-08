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

	protected $emp_promotion_id;
	protected $performance_year;
	protected $performance_rating;
	protected $performance_category;
	protected $supporting_file;

	protected $performance_detail_file1;
	protected $performance_detail_file2;
	protected $performance_detail_file3;
	protected $performance_detail_file4;
	
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


	public function getEmp_Promotion_Id()
	{
		return $this->emp_promotion_id;
	}
	
	public function setEmp_Promotion_Id($emp_promotion_id)
	{
		$this->emp_promotion_id = $emp_promotion_id;
	}


	public function getPerformance_Year()
	{
		return $this->performance_year;
	}
	
	public function setPerformance_year($performance_year)
	{
		$this->performance_year = $performance_year;
	}


	public function getPerformance_Rating()
	{
		return $this->performance_rating;
	}
	
	public function setPerformance_Rating($performance_rating)
	{
		$this->performance_rating = $performance_rating;
	}


	public function getPerformance_Category()
	{
		return $this->performance_category;
	}
	
	public function setPerformance_Category($performance_category)
	{
		$this->performance_category = $performance_category;
	}


	public function getSupporting_File()
	{
		return $this->supporting_file;
	}
	
	public function setSupporting_File($supporting_file)
	{
		$this->supporting_file = $supporting_file;
	}


	public function getPerformance_Detail_File1()
	{
		return $this->performance_detail_file1;
	}
	
	public function setPerformance_Detail_File1($performance_detail_file1)
	{
		$this->performance_detail_file1 = $performance_detail_file1;
	}


	public function getPerformance_Detail_File2()
	{
		return $this->performance_detail_file2;
	}
	
	public function setPerformance_Detail_File2($performance_detail_file2)
	{
		$this->performance_detail_file2 = $performance_detail_file2;
	}


	public function getPerformance_Detail_File3()
	{
		return $this->performance_detail_file3;
	}
	
	public function setPerformance_Detail_File3($performance_detail_file3)
	{
		$this->performance_detail_file3 = $performance_detail_file3;
	}

	public function getPerformance_Detail_File4()
	{
		return $this->performance_detail_file4;
	}
	
	public function setPerformance_Detail_File4($performance_detail_file4)
	{
		$this->performance_detail_file4 = $performance_detail_file4;
	}
	
	
}