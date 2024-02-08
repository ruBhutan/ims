<?php

namespace EmpResignation\Model;

class SeparationRecord
{
	protected $id;
	protected $separation_order_no;
	protected $separation_order_date;
	protected $separation_type;
	protected $remarks;
	protected $relieving_order_file;
	protected $employee_details_id;
	protected $emp_resignation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getSeparation_Order_No()
	{
		return $this->separation_order_no;
	}
	
	public function setSeparation_Order_No($separation_order_no)
	{
		$this->separation_order_no = $separation_order_no;
	}
	
	public function getSeparation_Order_Date()
	{
		return $this->separation_order_date;
	}
	
	public function setSeparation_Order_Date($separation_order_date)
	{
		$this->separation_order_date = $separation_order_date;
	}
	
	public function getSeparation_Type()
	{
		return $this->separation_type;
	}
	
	public function setSeparation_Type($separation_type)
	{
		$this->separation_type = $separation_type;
	}
	 
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getRelieving_Order_File()
	{
		return $this->relieving_order_file;
	}
	
	public function setRelieving_Order_File($relieving_order_file)
	{
		$this->relieving_order_file = $relieving_order_file;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
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