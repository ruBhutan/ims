<?php

namespace Examinations\Model;

class ExaminationCode
{
	protected $id;
	protected $student_id;
	protected $examination_code;
	protected $code_date;
	protected $academic_modules_id;
	protected $academic_module_code;
	protected $programmes_id;
	protected $programme_code;
	protected $organisation_id;
		 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	
	public function getExamination_Code()
	{
		return $this->examination_code;
	}
	
	public function setExamination_Code($examination_code)
	{
		$this->examination_code = $examination_code;
	}
	
	public function getCode_Date()
	{
		return $this->code_date;
	}
	
	public function setCode_Date($code_date)
	{
		$this->code_date = $code_date;
	}
	
	public function getAcademic_Modules_Id()
	{
		return $this->academic_modules_id;
	}
	
	public function setAcademic_Modules_Id($academic_modules_id)
	{
		$this->academic_modules_id = $academic_modules_id;
	}
	
	public function getAcademic_Module_Code()
	{
		return $this->academic_module_code;
	}
	
	public function setAcademic_Module_Code($academic_module_code)
	{
		$this->academic_module_code = $academic_module_code;
	}
	
	public function getProgrammes_Id()
	{
		return $this->programmes_id;
	}
	
	public function setProgramms_Id($programmes_id)
	{
		$this->programmes_id = $programmes_id;
	}
	
	public function getProgramme_Code()
	{
		return $this->programme_code;
	}
	
	public function setProgramme_Code($programme_code)
	{
		$this->programme_code = $programme_code;
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