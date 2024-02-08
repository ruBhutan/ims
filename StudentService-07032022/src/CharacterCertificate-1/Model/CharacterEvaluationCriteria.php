<?php

namespace CharacterCertificate\Model;

class CharacterEvaluationCriteria
{
	protected $id;
	protected $evaluation_criteria;
	protected $remarks;
	protected $organisaion_id;
	
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
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}

	public function getOrganisation_Id()
	{
		return $this->organisaion_id;
	}
	
	public function setOrganisation_Id($organisaion_id)
	{
		$this->organisaion_id = $organisaion_id;
	}

}