<?php

namespace PayrollManagement\Model;

class PayrollManagement
{
	protected $id;
	protected $institute_code;
	protected $institute_name;
	protected $institute_address;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getInstitute_Code()
	{
		return $this->institute_code;
	}
	
	public function setInstitute_Code($institute_code)
	{
		$this->institute_code = $institute_code;
	}
	
	public function getInstitute_Name()
	{
		return $this->institute_name;
	}
	
	public function setInstitute_Name($institute_name)
	{
		$this->institute_name = $institute_name;
	}
	
	public function getInstitute_Address()
	{
		return $this->institute_address;
	}
	
	public function setInstitute_Address($institute_address)
	{
		$this->institute_address = $institute_address;
	}
	 
}