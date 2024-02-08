<?php

namespace Planning\Model;

class KeyAspiration
{
	protected $id;
	protected $financial_year;
	protected $key_performance_indicator;
	protected $unit;
	protected $outstanding;
	protected $very_good;
    protected $good;
    protected $need_improvement;
    protected $activity_status;
	protected $employee_details_id;
	
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }

	 public function getFinancial_Year()
	 {
		 return $this->financial_year;
	 }
	 
	 public function setFinancial_Year($financial_year)
	 {
		 $this->financial_year = $financial_year;
	 }
	 
	 
	 public function getKey_Performance_Indicator()
	 {
		return $this->key_performance_indicator; 
	 }
	 	 
	 public function setKey_Performance_Indicator($key_performance_indicator)
	 {
		$this->key_performance_indicator = $key_performance_indicator;
	 }
     
     public function getUnit()
	 {
		 return $this->unit;
	 }
	 
	 public function setUnit($unit)
	 {
		$this->unit = $unit; 
     }
     
     public function getOutstanding()
	 {
		 return $this->outstanding;
	 }
	 
	 public function setOutstanding($outstanding)
	 {
		$this->outstanding = $outstanding; 
     }
     
     public function getVery_Good()
	 {
		 return $this->very_good;
	 }
	 
	 public function setVery_Good($very_good)
	 {
		$this->very_good = $very_good; 
     }
     
     public function getGood()
	 {
		 return $this->good;
	 }
	 
	 public function setGood($good)
	 {
		$this->good = $good; 
     }
     
     public function getNeed_Improvement()
	 {
		 return $this->need_improvement;
	 }
	 
	 public function setNeed_Improvement($need_improvement)
	 {
		$this->need_improvement = $need_improvement; 
     }
     
     public function getActivity_Status()
	 {
		 return $this->activity_status;
	 }
	 
	 public function setActivity_Status($activity_status)
	 {
		$this->activity_status = $activity_status; 
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
