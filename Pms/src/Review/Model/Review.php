<?php

namespace Review\Model;

class Review
{
	protected $id;
	protected $api;
	protected $indicators;
	protected $maximum_score;
	protected $self_rating;
	protected $performance_rating;
	protected $remarks;
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
	 
	public function getApi()
	{
		return $this->api;
	}
	
	public function setApi($api)
	{
		$this->api = $api;
	}
	
	public function getIndicators()
	{
		return $this->indicators;
	}
	
	public function setIndicators($indicators)
	{
		$this->indicators = $indicators;
	}
	
	public function getMaximum_Score()
	{
		return $this->maximum_score;
	}
	
	public function setMaximum_Score($maximum_score)
	{
		$this->maximum_score = $maximum_score;
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
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
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