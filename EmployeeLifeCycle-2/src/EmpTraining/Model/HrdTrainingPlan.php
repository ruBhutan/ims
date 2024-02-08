<?php

namespace EmpTraining\Model;

class HrdTrainingPlan
{
	protected $id;
	protected $five_year_plan;
	protected $working_agency;
	protected $course_title;
	protected $total_no_slots;
	protected $duration;
	protected $duration_unit;
	protected $training_type;
	protected $source_of_funding;
	protected $target_group;
	protected $tuition_fees;
	protected $dsa_tada;
	protected $air_fare;
	protected $total_amount;
	protected $priority;
	protected $location_of_training;
	protected $amount_year_1;
	protected $amount_year_2;
	protected $amount_year_3;
	protected $amount_year_4;
	protected $amount_year_5;
	protected $approval_status;
	protected $approval_date;
	protected $remarks;	

	protected $training_category;

	protected $funding_type; 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getFive_Year_Plan()
	 {
		return $this->five_year_plan; 
	 }
	 	 
	 public function setFive_Year_Plan($five_year_plan)
	 {
		 $this->five_year_plan = $five_year_plan;
	 }
	 
	 public function getWorking_Agency()
	 {
		 return $this->working_agency;
	 }
	 
	 public function setWorking_Agency($working_agency)
	 {
		 $this->working_agency = $working_agency;
	 }
	 	 
	 public function getCourse_Title()
	 {
		return $this->course_title; 
	 }
	 	 
	 public function setCourse_Title($course_title)
	 {
		 $this->course_title=$course_title;
	 }
	 
	 public function getTotal_No_Slots()
	 {
		return $this->total_no_slots; 
	 }
	 	 
	 public function setTotal_No_Slots($total_no_slots)
	 {
		 $this->total_no_slots=$total_no_slots;
	 }
	 
	 public function getDuration()
	 {
		return $this->duration; 
	 }
	 	 
	 public function setDuration($duration)
	 {
		 $this->duration=$duration;
	 }

	 public function getDuration_Unit()
	 {
		return $this->duration_unit; 
	 }
	 	 
	 public function setDuration_Unit($duration_unit)
	 {
		 $this->duration_unit=$duration_unit;
	 }
	 
	 public function getTraining_Type()
	 {
		return $this->training_type; 
	 }
	 	 
	 public function setTraining_Type($training_type)
	 {
		 $this->training_type=$training_type;
	 }
	 
	 public function getSource_Of_Funding()
	 {
		 return $this->source_of_funding;
	 }
	 
	 public function setSource_Of_Funding($source_of_funding)
	 {
		 $this->source_of_funding = $source_of_funding;
	 }
	 
	 public function getTarget_Group()
	 {
		return $this->target_group; 
	 }
	 	 
	 public function setTarget_Group($target_group)
	 {
		 $this->target_group=$target_group;
	 }
	 
	 public function getTuition_Fees()
	 {
		 return $this->tuition_fees;
	 }
	 
	 public function setTuition_Fees($tuition_fees)
	 {
		 $this->tuition_fees = $tuition_fees;
	 }
	 
	 public function getDsa_Tada()
	 {
		 return $this->dsa_tada;
	 }
	 
	 public function setDsa_Tada($dsa_tada)
	 {
		 $this->dsa_tada = $dsa_tada;
	 }
         
	 public function getAir_Fare()
	 {
		 return $this->air_fare;
	 }
	 
	 public function setAir_Fare($air_fare)
	 {
		 $this->air_fare = $air_fare;
	 }
	 
	 public function getTotal_Amount()
	 {
		 return $this->total_amount;
	 }
	 
	 public function setTotal_Amount($total_amount)
	 {
		 $this->total_amount = $total_amount;
	 }
	 
	 public function getPriority()
	 {
		 return $this->priority;
	 }
	 
	 public function setPriority($priority)
	 {
		 $this->priority = $priority;
	 }
	 
	 public function getLocation_Of_Training()
	 {
		 return $this->location_of_training;
	 }
	 
	 public function setLocation_Of_Training($location_of_training)
	 {
		 $this->location_of_training = $location_of_training;
	 }
	 
	 public function getAmount_Year_1()
	 {
		 return $this->amount_year_1;
	 }
	 
	 public function setAmount_Year_1($amount_year_1)
	 {
		 $this->amount_year_1 = $amount_year_1;
	 }
	 
	 public function getAmount_Year_2()
	 {
		 return $this->amount_year_2;
	 }
	 
	 public function setAmount_Year_2($amount_year_2)
	 {
		 $this->amount_year_2 = $amount_year_2;
	 }
 
	 public function getAmount_Year_3()
	 {
		 return $this->amount_year_3;
	 }
	 
	 public function setAmount_Year_3($amount_year_3)
	 {
		 $this->amount_year_3 = $amount_year_3;
	 }
	 
	 public function getAmount_Year_4()
	 {
		 return $this->amount_year_4;
	 }
	 
	 public function setAmount_Year_4($amount_year_4)
	 {
		 $this->amount_year_4 = $amount_year_4;
	 }
	 
	 public function getAmount_Year_5()
	 {
		 return $this->amount_year_5;
	 }
	 
	 public function setAmount_Year_5($amount_year_5)
	 {
		 $this->amount_year_5 = $amount_year_5;
	 }
	 
	 public function getApproval_Status()
	 {
		 return $this->approval_status;
	 }
	 
	 public function setApproval_Status($approval_status)
	 {
		 $this->approval_status = $approval_status;
	 }
	 
	 public function getApproval_Date()
	 {
		 return $this->approval_date;
	 }
	 
	 public function setApproval_Date($approval_date)
	 {
		 $this->approval_date = $approval_date;
	 }
	 
	 public function getRemarks()
	 {
		 return $this->remarks;
	 }
	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }

	 public function getFunding_Type()
	 {
		 return $this->funding_type;
	 }
	 
	 public function setFunding_Type($funding_type)
	 {
		 $this->funding_type = $funding_type;
	 }

	 public function getTraining_Category()
	{
		return $this->training_category;
	}
	
	public function setTraining_Category($training_category)
	{
		$this->training_category = $training_category;
	}

}