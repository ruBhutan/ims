<?php

namespace Appraisal\Model;

class AcademicAppraisal
{
	protected $id;
	protected $appraisal_period;
	protected $maximum_api;
        protected $maximum_api_description;
	protected $minimum_api;
        protected $minimum_api_description;
	protected $self_rating;
	protected $performance_rating;
	protected $remarks;
	protected $status;
	protected $rated_by;
	protected $awpa_objectives_activity_id;
	protected $pms_nature_activity_id;
	protected $employee_details_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getAppraisal_Period()
	{
		return $this->appraisal_period;
	}
	 
	public function setAppraisal_Period($appraisal_period)
	{
		$this->appraisal_period = $appraisal_period;
	}
	 
	public function getMaximum_Api()
	{
		return $this->maximum_api;
	}
	
	public function setMaximum_Api($maximum_api)
	{
		$this->maximum_api = $maximum_api;
	}
        
        public function getMaximum_Api_Description()
	{
		return $this->maximum_api_description;
	}
	
	public function setMaximum_Api_Description($maximum_api_description)
	{
		$this->maximum_api_description = $maximum_api_description;
	}
	
	public function getMinimum_Api()
	{
		return $this->minimum_api;
	}
	
	public function setMinimum_Api_Description($minimum_api_description)
	{
		$this->minimum_api_description = $minimum_api_description;
	}
        
        public function getMinimum_Api_Description()
	{
		return $this->minimum_api_description;
	}
	
	public function setMinimum_Api($minimum_api)
	{
		$this->minimum_api = $minimum_api;
	}
	
	public function getSelf_Rating()
	{
		return $this->self_rating;
	}
	
	public function setSelf_Rating($self_rating)
	{
		$this->self_rating = $self_rating;
	}
	
	public function getPerformance_Rating()
	{
		return $this->performance_rating;
	}
	
	public function setPerformance_Rating($performance_rating)
	{
		$this->performance_rating = $performance_rating;
	}
	
	public function getRated_By()
	 {
		 return $this->rated_by;
	 }
	 
	 public function setRated_By($rated_by)
	 {
		 $this->rated_by = $rated_by;
	 }
	 
	 public function getRemarks()
	 {
		 return $this->remarks;
	 }
	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }
	 
	 public function getStatus()
	 {
		 return $this->status;
	 }
	 
	 public function setStatus($status)
	 {
		 $this->status = $status;
	 }
	
	public function getAwpa_Objectives_Activity_id()
	{
		return $this->awpa_objectives_activity_id;
	}
	
	public function setAwpa_Objectives_Activity_id($awpa_objectives_activity_id)
	{
		$this->awpa_objectives_activity_id = $awpa_objectives_activity_id;
	}
	
	public function getPms_Nature_Activity_Id()
	{
		return $this->pms_nature_activity_id;
	}
	
	public function setPms_Nature_Activity_Id($pms_nature_activity_id)
	{
		$this->pms_nature_activity_id = $pms_nature_activity_id;
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