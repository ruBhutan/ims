<?php

namespace UniversityResearch\Model;

class AurgTitle
{
	protected $id;
	protected $grant_type;
	protected $grant_applied_for;
	protected $research_title;
	protected $research_year;
	protected $employee_details_id;
	protected $aurgresearchers;

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
	
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getGrant_Type()
	 {
		 return $this->grant_type;
	 }
	 
	 public function setGrant_Type($grant_type)
	 {
		 $this->grant_type = $grant_type;
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
	 
	 public function getEmployee_Details_Id()
	 {
		return $this->employee_details_id; 
	 }
	 	 
	 public function setEmployee_Details_Id($employee_details_id)
	 {
		$this->employee_details_id = $employee_details_id;
	 }
	 
	 public function getAurgresearchers()
	 {
		 return $this->aurgresearchers;
	 }
	 
	 public function setAurgresearchers($aurgresearchers)
	 {
		 $this->aurgresearchers = $aurgresearchers;
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
}


