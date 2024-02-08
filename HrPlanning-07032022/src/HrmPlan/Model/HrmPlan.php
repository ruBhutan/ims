<?php

namespace HrmPlan\Model;

class HrmPlan
{
	protected $id;
	protected $five_year_plan;
	protected $working_agency;
	protected $department_name;
	protected $existing_no;
	protected $required_no;
	protected $proposal_submission_date;
	protected $proposal_status;
	protected $requirement_year_1;
	protected $requirement_year_2;
	protected $requirement_year_3;
	protected $requirement_year_4;
	protected $requirement_year_5;
	protected $approval_date;
	protected $position_category_id;
	protected $position_title_id;
	protected $position_level_id;
	protected $priority;
	protected $remarks;
	
	//the following are used for displaying results
	protected $organisation_name;
	protected $position_title;
	protected $category;
	protected $position_level;
	 	 
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
	 	 
	 public function getDepartment_Name()
	 {
		return $this->department_name; 
	 }
	 	 
	 public function setDepartment_Name($department_name)
	 {
		 $this->department_name=$department_name;
	 }
	 
	 public function getExisting_No()
	 {
		return $this->existing_no; 
	 }
	 	 
	 public function setExisting_No($existing_no)
	 {
		 $this->existing_no=$existing_no;
	 }
	 
	 public function getRequired_No()
	 {
		return $this->required_no; 
	 }
	 	 
	 public function setRequired_No($required_no)
	 {
		 $this->required_no=$required_no;
	 }
	 
	 public function getProposal_Submission_Date()
	 {
		return $this->proposal_submission_date; 
	 }
	 	 
	 public function setProposal_Submission_Date($proposal_submission_date)
	 {
		 $this->proposal_submission_date=$proposal_submission_date;
	 }
	 
	 public function getProposal_Status()
	 {
		 return $this->proposal_status;
	 }
	 
	 public function setProposal_Status($proposal_status)
	 {
		 $this->proposal_status = $proposal_status;
	 }
	 
	 public function getRequirement_Year_1()
	 {
		 return $this->requirement_year_1;
	 }
	 
	 public function setRequirement_Year_1($requirement_year_1)
	 {
		 $this->requirement_year_1 = $requirement_year_1;
	 }
	 
	 public function getRequirement_Year_2()
	 {
		 return $this->requirement_year_2;
	 }
	 
	 public function setRequirement_Year_2($requirement_year_2)
	 {
		 $this->requirement_year_2 = $requirement_year_2;
	 }
 
	 public function getRequirement_Year_3()
	 {
		 return $this->requirement_year_3;
	 }
	 
	 public function setRequirement_Year_3($requirement_year_3)
	 {
		 $this->requirement_year_3 = $requirement_year_3;
	 }
	 
	 public function getRequirement_Year_4()
	 {
		 return $this->requirement_year_4;
	 }
	 
	 public function setRequirement_Year_4($requirement_year_4)
	 {
		 $this->requirement_year_4 = $requirement_year_4;
	 }
	 
	 public function getRequirement_Year_5()
	 {
		 return $this->requirement_year_5;
	 }
	 
	 public function setRequirement_Year_5($requirement_year_5)
	 {
		 $this->requirement_year_5 = $requirement_year_5;
	 }
	 
	 public function getApproval_Date()
	 {
		return $this->approval_date; 
	 }
	 	 
	 public function setApproval_Date($approval_date)
	 {
		 $this->approval_date=$approval_date;
	 }
	 
	 public function getPosition_Title_Id()
	 {
		 return $this->position_title_id;
	 }
	 
	 public function setPosition_Title_Id($position_title_id)
	 {
		 $this->position_title_id = $position_title_id;
	 }
	 
	 public function getPosition_Category_Id()
	 {
		 return $this->position_category_id;
	 }
	 
	 public function setPosition_Category_Id($position_category_id)
	 {
		 $this->position_category_id = $position_category_id;
	 }
         
	 public function getPosition_Level_Id()
	 {
		 return $this->position_level_id;
	 }
	 
	 public function setPosition_Level_Id($position_level_id)
	 {
		 $this->position_level_id = $position_level_id;
	 }
	 
	 public function getRemarks()
	 {
		 return $this->remarks;
	 }
	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }
	 
	 //used for displaying in view
	 public function getOrganisation_Name()
	 {
		 return $this->organisation_name;
	 }
	 
	 public function setOrganisation_Name($organisation_name)
	 {
		 $this->organisation_name = $organisation_name;
	 }
	 
	 public function getPosition_Title()
	 {
		 return $this->position_title;
	 }
	 
	 public function setPosition_Title($position_title)
	 {
		 $this->position_title = $position_title;
	 }
	 
	 public function getCategory()
	 {
		 return $this->category;
	 }
	 
	 public function setCategory($category)
	 {
		 $this->category = $category;
	 }
	 
	 public function getPosition_Level()
	 {
		 return $this->position_level;
	 }
	 
	 public function setPosition_Level($position_level)
	 {
		 $this->position_level = $position_level;
	 }

}