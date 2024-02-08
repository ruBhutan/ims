<?php

namespace Programme\Model;

class Module
{
	protected $id;
	protected $module_title;
	protected $module_year;
	protected $module_semester;
	protected $module_code;
	protected $module_credit;
    protected $module_type;
    protected $contact_hours;
    protected $module_description;
	protected $status;
	protected $programmes_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getModule_Title()
	{
		return $this->module_title;
	}
	
	public function setModule_Title($module_title)
	{
		$this->module_title = $module_title;
	}
	
	public function getModule_Year()
	{
		return $this->module_year;
	}
	
	public function setModule_Year($module_year)
	{
		$this->module_year = $module_year;
	}
	
	public function getModule_Semester()
	{
		return $this->module_semester;
	}
	
	public function setModule_Semester($module_semester)
	{
		$this->module_semester = $module_semester;
	}
	
	public function getModule_Code()
	{
		return $this->module_code;
	}
	
	public function setModule_Code($module_code)
	{
		$this->module_code = $module_code;
	}
	
	public function getModule_Credit()
	{
		return $this->module_credit;
	}
	
	public function setModule_Credit($module_credit)
	{
		$this->module_credit = $module_credit;
	}
        
	public function getModule_Type()
	{
		return $this->module_type;
	}
	
	public function setModule_Type($module_type)
	{
		$this->module_type = $module_type;
	}
        
    public function getContact_Hours()
    {
        return $this->contact_hours;
    }
        
    public function setContact_Hours($contact_hours)
    {
        $this->contact_hours = $contact_hours;
    }
        
    public function getModule_Description()
	{
		return $this->module_description;
	}
	
	public function setModule_Description($module_description)
	{
		$this->module_description = $module_description;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getProgrammes_Id()
	{
		return $this->programmes_id;
	}
	
	public function setProgrammes_Id($programmes_id)
	{
		$this->programmes_id = $programmes_id;
	}
}