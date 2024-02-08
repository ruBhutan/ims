<?php

namespace UniversityResearch\Model;

class AurgGrant
{
	protected $id;
	protected $grant_applied_for;
	protected $research_title;
	protected $research_year;
	protected $problem_statement;
	protected $research_questions;
	protected $review_key_literature;
	protected $approach_paradigm_theory;
	protected $data_collection_procedures;
	protected $data_analysis_procedures;
	protected $data_presentation;
	protected $ethical_considerations;
	protected $significance_of_study;
	protected $research_dissemination;
	protected $references;
	protected $ethical_committee_approval_no;
	protected $application_status;
	protected $amount_approved;
	protected $remarks;
	protected $application_step_status;
	protected $employee_details_id;
	protected $aurg_action_plan_budget;
	protected $aurg_researchers;
	
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getGrant_Applied_For()
	 {
		return $this->grant_applied_for; 
	 }
	 	 
	 public function setGrant_Applied_For($grant_applied_for)
	 {
		$this->grant_applied_for = $grant_applied_for;
	 }
	 
	 public function getResearch_Title()
	 {
		return $this->research_title;
	 }
	 
	 public function setResearch_Title($research_title)
	 {
		$this->research_title = $research_title;
	 }
	 	 
	 public function getResearch_Year()
	 {
		return $this->research_year; 
	 }
	 	 
	 public function setResearch_Year($research_year)
	 {
		$this->research_year=$research_year;
	 }
	 
	 public function getProblem_Statement()
	 {
		return $this->problem_statement; 
	 }
	 	 
	 public function setProblem_Statement($problem_statement)
	 {
		$this->problem_statement=$problem_statement;
	 }
	 
	 public function getResearch_Questions()
	 {
		return $this->research_questions; 
	 }
	 	 
	 public function setResearch_Questions($research_questions)
	 {
		$this->research_questions=$research_questions;
	 }
	 
	 public function getReview_Key_Literature()
	 {
		return $this->review_key_literature; 
	 }
	 	 
	 public function setReview_Key_Literature($review_key_literature)
	 {
		$this->review_key_literature=$review_key_literature;
	 }
	 
	 public function getApproach_Paradigm_Theory()
	 {
		return $this->approach_paradigm_theory; 
	 }
	 	 
	 public function setApproach_Paradigm_Theory($approach_paradigm_theory)
	 {
		$this->approach_paradigm_theory=$approach_paradigm_theory;
	 }
	 
	 public function getData_Collection_Procedures()
	 {
		return $this->data_collection_procedures; 
	 }
	 	 
	 public function setData_Collection_Procedures($data_collection_procedures)
	 {
		$this->data_collection_procedures=$data_collection_procedures;
	 }
	 
	 public function getData_Analysis_Procedures()
	 {
		return $this->data_analysis_procedures; 
	 }
	 	 
	 public function setData_Analysis_Procedures($data_analysis_procedures)
	 {
		$this->data_analysis_procedures=$data_analysis_procedures;
	 }
	 
	 public function getData_Presentation()
	 {
		return $this->data_presentation; 
	 }
	 	 
	 public function setData_Presentation($data_presentation)
	 {
		$this->data_presentation=$data_presentation;
	 }
	 
	 public function getEthical_Considerations()
	 {
		return $this->ethical_considerations; 
	 }
	 	 
	 public function setEthical_Considerations($ethical_considerations)
	 {
		$this->ethical_considerations=$ethical_considerations;
	 }
	 
	 public function getSignificance_Of_Study()
	 {
		return $this->significance_of_study; 
	 }
	 	 
	 public function setSignificance_Of_Study($significance_of_study)
	 {
		$this->significance_of_study=$significance_of_study;
	 }
	 
	 public function getResearch_Dissemination()
	 {
		return $this->research_dissemination; 
	 }
	 	 
	 public function setResearch_Dissemination($research_dissemination)
	 {
		$this->research_dissemination=$research_dissemination;
	 }
	 
	 public function getReferences()
	 {
		return $this->references; 
	 }
	 	 
	 public function setReferences($references)
	 {
		$this->references=$references;
	 }
	 
	 public function getEthical_Committee_Approval_No()
	 {
		return $this->ethical_committee_approval_no; 
	 }
	 	 
	 public function setEthical_Committee_Approval_No($ethical_committee_approval_no)
	 {
		$this->ethical_committee_approval_no=$ethical_committee_approval_no;
	 }
	 
	 public function getApplication_Status()
	 {
		return $this->application_status; 
	 }
	 	 
	 public function setApplication_Status($application_status)
	 {
		$this->application_status=$application_status;
	 }
	 
	 public function getAmount_Approved()
	 {
		return $this->amount_approved; 
	 }
	 	 
	 public function setAmount_Approved($amount_approved)
	 {
		$this->amount_approved=$amount_approved;
	 }
	 
	 public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	 public function setRemarks($remarks)
	 {
		$this->remarks=$remarks;
	 }
	
	  public function getApplication_Step_Status()
	 {
		return $this->application_step_status; 
	 }
	 	 
	 public function setApplication_Step_Status($application_step_status)
	 {
		$this->application_step_status=$application_step_status;
	 }
	 
	 public function getEmployee_Details_Id()
	 {
		return $this->employee_details_id; 
	 }
	 	 
	 public function setEmployee_Details_Id($employee_details_id)
	 {
		$this->employee_details_id = $employee_details_id;
	 }
	 
	 public function getAurg_Action_Plan_Budget()
	 {
		 return $this->aurg_action_plan_budget;
	 }
	 
	 public function setAurg_Action_Plan_Budget($aurgactionplanbudget)
	 {
		 $this->aurg_action_plan_budget = $aurg_action_plan_budget;
	 }
	 
	 public function getAurg_Researchers()
	 {
		 return $this->aurg_researchers;
	 }
	 
	 public function setAurg_Researchers($aurg_researchers)
	 {
		 $this->aurg_researchers = $aurg_researchers;
	 }
}