<?php

namespace Examinations\Model;

class Examinations
{
	protected $id;
	protected $programmes_id;
	protected $academic_modules_id;
	protected $examination_date;
	protected $organisation_id;
		 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getProgrammes_Id()
	{
		return $this->programmes_id;
	}
	
	public function setProgrammes_Id($programmes_id)
	{
		$this->programmes_id = $programmes_id;
	}
	
	public function getAcademic_Modules_Id()
	{
		return $this->academic_modules_id;
	}
	
	public function setAcademic_Modules_Id($academic_modules_id)
	{
		$this->academic_modules_id = $academic_modules_id;
	}
	
	public function getExamination_Date()
	{
		return $this->examination_date;
	}
	
	public function setExamination_Date($examination_date)
	{
		$this->examination_date = $examination_date;
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