<?php

namespace Planning\Model;

class AwpaObjectives
{
	protected $id;
	protected $activity_name;
	protected $success_indicator_name;
	protected $unit;
	protected $weight;
	protected $excellent_rating;
	protected $very_good_rating;
	protected $good_rating;
	protected $fair_rating;
	protected $poor_rating;
	protected $kpi_success_year1;
	protected $kpi_success_year2;
	protected $kpi_success_year3;
	protected $kpi_success_year4;
	protected $kpi_success_year5;
	protected $rub_objectives_id;
	
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getActivity_Name()
	 {
		return $this->activity_name; 
	 }
	 	 
	 public function setActivity_Name($activity_name)
	 {
		$this->activity_name = $activity_name;
	 }
	 
	 public function getSuccess_Indicator_Name()
	 {
		return $this->success_indicator_name;
	 }
	 
	 public function setSuccess_Indicator_Name($success_indicator_name)
	 {
		$this->success_indicator_name = $success_indicator_name;
	 }
	 	 
	 public function getUnit()
	 {
		return $this->unit; 
	 }
	 	 
	 public function setUnit($unit)
	 {
		$this->unit = $unit;
	 }
	 
	 public function getWeight()
	 {
		return $this->weight; 
	 }
	 	 
	 public function setWeight($weight)
	 {
		$this->weight = $weight;
	 }
	 
	 public function getExcellent_Rating()
	 {
		return $this->excellent_rating; 
	 }
	 	 
	 public function setExcellent_Rating($excellent_rating)
	 {
		$this->excellent_rating = $excellent_rating;
	 }
	 
	 public function getVery_Good_Rating()
	 {
		return $this->very_good_rating; 
	 }
	 	 
	 public function setVery_Good_Rating($very_good_rating)
	 {
		$this->very_good_rating = $very_good_rating;
	 }
	 
	 public function getGood_Rating()
	 {
		return $this->good_rating; 
	 }
	 	 
	 public function setGood_Rating($good_rating)
	 {
		$this->good_rating = $good_rating;
	 }
	 
	 public function getFair_Rating()
	 {
		return $this->fair_rating; 
	 }
	 	 
	 public function setFair_Rating($fair_rating)
	 {
		$this->fair_rating = $fair_rating;
	 }
	 
	 public function getPoor_Rating()
	 {
		return $this->poor_rating; 
	 }
	 	 
	 public function setPoor_Rating($poor_rating)
	 {
		$this->poor_rating = $poor_rating;
	 }
	 
	 public function getKpi_Success_Year1()
	 {
		return $this->kpi_success_year1; 
	 }
	 	 
	 public function setKpi_Success_Year1($kpi_success_year1)
	 {
		$this->kpi_success_year1 = $kpi_success_year1;
	 }
	 
	 public function getKpi_Success_Year2()
	 {
		return $this->kpi_success_year2; 
	 }
	 	 
	 public function setKpi_Success_Year2($kpi_success_year2)
	 {
		$this->kpi_success_year2 = $kpi_success_year2;
	 }
	 
	 public function getKpi_Success_Year3()
	 {
		return $this->kpi_success_year3; 
	 }
	 	 
	 public function setKpi_Success_Year3($kpi_success_year3)
	 {
		$this->kpi_success_year3 = $kpi_success_year3;
	 }
	 
	 public function getKpi_Success_Year4()
	 {
		return $this->kpi_success_year4; 
	 }
	 	 
	 public function setKpi_Success_Year4($kpi_success_year4)
	 {
		$this->kpi_success_year4 = $kpi_success_year4;
	 }
	 
	 public function getKpi_Success_Year5()
	 {
		return $this->kpi_success_year5; 
	 }
	 	 
	 public function setKpi_Success_Year5($kpi_success_year5)
	 {
		$this->kpi_success_year5 = $kpi_success_year5;
	 }
	 
	 public function getRub_Objectives_Id()
	 {
		return $this->rub_objectives_id; 
	 }
	 	 
	 public function setRub_Objectives_Id($rub_objectives_id)
	 {
		$this->rub_objectives_id = $rub_objectives_id;
	 }
	
}

class IwpSubactivities
{
	protected $id;
	protected $subactivity_name;
	protected $outstanding_description;
	protected $very_good_description;
	protected $good_description;
	protected $needs_improvement_description;
	protected $emp_iwp_rating;
	protected $rated_by;
	protected $remarks;
	protected $awpa_objectives_activity_id;
	
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getSubactivity_Name()
	 {
		return $this->subactivity_name; 
	 }
	 	 
	 public function setSubactivity_Name($subactivity_name)
	 {
		$this->subactivity_name = $subactivity_name;
	 }
	 
	 public function getOutstanding_Description()
	 {
		return $this->outstanding_description;
	 }
	 
	 public function setOutstanding_Description($outstanding_description)
	 {
		$this->outstanding_description = $outstanding_description;
	 }
	 	 
	 public function getVery_Good_Description()
	 {
		return $this->very_good_description; 
	 }
	 	 
	 public function setVery_Good_Description($very_good_description)
	 {
		$this->very_good_description = $very_good_description;
	 }
	 
	 public function getGood_Description()
	 {
		return $this->good_description; 
	 }
	 	 
	 public function setGood_Description($good_description)
	 {
		$this->good_description = $good_description;
	 }
	 
	 public function getNeeds_Improvement_Description()
	 {
		return $this->needs_improvement_description; 
	 }
	 	 
	 public function setNeeds_Improvement_Description($needs_improvement_description)
	 {
		$this->needs_improvement_description = $needs_improvement_description;
	 }
	 
	 public function getEmp_Iwp_Rating()
	 {
		return $this->emp_iwp_rating; 
	 }
	 	 
	 public function setEmp_Iwp_Rating($emp_iwp_rating)
	 {
		$this->emp_iwp_rating = $emp_iwp_rating;
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
	 
	 public function getAwpa_Objectives_Activity_Id()
	 {
		return $this->awpa_objectives_activity_id; 
	 }
	 	 
	 public function setAwpa_Objectives_Activity_Id($awpa_objectives_activity_id)
	 {
		$this->awpa_objectives_activity_id = $awpa_objectives_activity_id;
	 }
	
}