<?php

namespace CharacterCertificate\Model;

class CharacterCertificate
{
	protected $id;
	protected $evaluation_criteria;
	protected $evaluation;
	protected $evaluation_date;
	protected $character_evaluator_id;
	protected $character_evaluation_criteria_id;
	protected $batch;
	protected $programmes_id;
	protected $employee_details_id;
	protected $remarks;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getEvaluation_Criteria()
	{
		return $this->evaluation_criteria;
	}
	
	public function setEvaluation_Criteria($evaluation_criteria)
	{
		$this->evaluation_criteria = $evaluation_criteria;
	}
	
	public function getEvaluation_Date()
	{
		return $this->evaluation_date;
	}
	
	public function setEvaluation_Date($evaluation_date)
	{
		$this->evaluation_date = $evaluation_date;
	}
	
	public function getCharacter_Evaluator_Id()
	{
		return $this->character_evaluator_id;
	}
	
	public function setCharacter_Evaluator_Id($character_evaluator_id)
	{
		$this->character_evaluator_id = $character_evaluator_id;
	}
	
	public function getCharacter_Evaluation_Criteria_Id()
	{
		return $this->character_evaluation_criteria_id;
	}
	
	public function setCharacter_Evaluation_Criteria_Id($character_evaluation_criteria_id)
	{
		$this->character_evaluation_criteria_id = $character_evaluation_criteria_id;
	}
	
	public function getBatch()
	{
		return $this->batch;
	}
	
	public function setBatch($batch)
	{
		$this->batch = $batch;
	}
	
	public function getProgrammes_Id()
	{
		return $this->programmes_id;
	}
	
	public function setProgrammes_Id($programmes_id)
	{
		$this->programmes_id = $programmes_id;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}

}