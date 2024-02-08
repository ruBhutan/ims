<?php

namespace RepeatModules\Model;

class RepeatModules
{
	protected $id;
	protected $student_id;
	protected $module_code;
	protected $academic_year;
	protected $backpaper_academic_year;
	protected $backpaper_semester;
	protected $programmes_id;
	protected $backpaper_in;
	protected $registration_status;
	protected $registration_date;
	protected $academic_modules_allocation_id;
	 
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

	public function getModule_Code()
	{
		return $this->module_code;
	}
	 
	public function setModule_Code($module_code)
	{
		$this->module_code = $module_code;
	}

	public function getAcademic_Year()
	{
		return $this->academic_year;
	}
	 
	public function setAcademic_Year($academic_year)
	{
		$this->academic_year = $academic_year;
	}

	public function getBackpaper_Academic_Year()
	{
		return $this->backpaper_academic_year;
	}
	 
	public function setBackpaper_Academic_Year($backpaper_academic_year)
	{
		$this->backpaper_academic_year = $backpaper_academic_year;
	}

	public function getBackpaper_Semester()
	{
		return $this->backpaper_semester;
	}
	 
	public function setBackpaper_Semester($backpaper_semester)
	{
		$this->backpaper_semester = $backpaper_semester;
	}

	public function getProgrammes_Id()
	{
		return $this->programmes_id;
	}
	 
	public function setProgrammes_Id($programmes_id)
	{
		$this->programmes_id = $programmes_id;
	}

	public function getBackpaper_In()
	{
		return $this->backpaper_in;
	}
	 
	public function setBackpaper_In($backpaper_in)
	{
		$this->backpaper_in = $backpaper_in;
	}

	public function getRegistration_Status()
	{
		return $this->registration_status;
	}
	 
	public function setRegistration_Status($registration_status)
	{
		$this->registration_status = $registration_status;
	}

	public function getRegistration_Date()
	{
		return $this->registration_date;
	}
	 
	public function setRegistration_Date($registration_date)
	{
		$this->registration_date = $registration_date;
	}


	public function getAcademic_Modules_Allocation_Id()
	{
		return $this->academic_modules_allocation_id;
	}
	 
	public function setAcademic_Modules_Allocation_Id($academic_modules_allocation_id)
	{
		$this->academic_modules_allocation_id = $academic_modules_allocation_id;
	}
	
	 
}