<?php

namespace EmpResignation\Model;

class Separation
{
	protected $id;
	protected $remarks;
	protected $issuing_authority;
	protected $emp_resignation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getIssuing_Authority()
	{
		return $this->issuing_authority;
	}
	
	public function setIssuing_Authority($issuing_authority)
	{
		$this->issuing_authority = $issuing_authority;
	}
	
	public function getEmp_Resignation_Id()
	{
		return $this->emp_resignation_id;
	}
	
	public function setEmp_Resignation_Id($emp_resignation_id)
	{
		$this->emp_resignation_id = $emp_resignation_id;
	}
}