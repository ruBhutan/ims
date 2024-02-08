<?php

namespace Programme\Model;

class AcademicYearModule
{
	protected $id;
	protected $academic_year;
	protected $semester;
	protected $year;
	protected $programmes_id;
	protected $academic_modules_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getAcademic_Year()
	{
		return $this->academic_year;
	}
	
	public function setAcademic_Year($academic_year)
	{
		$this->academic_year = $academic_year;
	}
	
	public function getSemester()
	{
		return $this->semester;
	}
	
	public function setSemester($semester)
	{
		$this->semester = $semester;
	}
	
	public function getYear()
	{
		return $this->year;
	}
	
	public function setYear($year)
	{
		$this->year = $year;
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
	
}