<?php
/*
* Separate form and model for editing as the "Edit Functionality" contains more fields
*/

namespace Programme\Model;

class EditAssessmentComponent
{
	protected $id;
	protected $assessment;
	protected $weightage;
	protected $academic_modules_id;
	protected $assessment_component_types_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getAssessment()
	{
		return $this->assessment;
	}
	
	public function setAssessment($assessment)
	{
		$this->assessment = $assessment;
	}
	
	public function getWeightage()
	{
		return $this->weightage;
	}
	
	public function setWeightage($weightage)
	{
		$this->weightage = $weightage;
	}
	
	/*
	public function getProgrammes_Id()
	{
		return $this->programmes_id;
	}
	
	public function setProgrammes_Id($programmes_id)
	{
		$this->programmes_id = $programmes_id;
	}
	*/
	public function getAcademic_Modules_Id()
	{
		return $this->academic_modules_id;
	}
	
	public function setAcademic_Modules_Id($academic_modules_id)
	{
		$this->academic_modules_id = $academic_modules_id;
	}
	
	public function getAssessment_Component_Types_Id()
	{
		return $this->assessment_component_types_id;
	}
	
	public function setAssessment_Component_Types_Id($assessment_component_types_id)
	{
		$this->assessment_component_types_id = $assessment_component_types_id;
	}
	
}