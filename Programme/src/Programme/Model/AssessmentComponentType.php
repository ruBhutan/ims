<?php

namespace Programme\Model;

class AssessmentComponentType
{
	protected $id;
	protected $assessment_component_type;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getAssessment_Component_Type()
	{
		return $this->assessment_component_type;
	}
	
	public function setAssessment_Component_Type($assessment_component_type)
	{
		$this->assessment_component_type = $assessment_component_type;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
}