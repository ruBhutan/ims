<?php

namespace UniversityResearch\Model;

class AurgProjectDescription
{
	protected $id;
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
	protected $application_step_status;
	
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
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
	
	  public function getApplication_Step_Status()
	 {
		return $this->application_step_status; 
	 }
	 	 
	 public function setApplication_Step_Status($application_step_status)
	 {
		$this->application_step_status=$application_step_status;
	 }
	 
}