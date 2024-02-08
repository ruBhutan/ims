<?php

namespace GoodsTransaction\Model;

class DeptGoods
{
	protected $id;
	//protected $employee_details_id;
	protected $goods_received_id;
	protected $date_of_issue;
	protected $dept_quantity;
	//protected $emp_id;
	protected $departments_id;
	protected $goods_received_by;
	protected $issue_goods_status;
	//protected $organisation_id;
	protected $goods_issued_by;
	protected $goods_issued_remarks;

			 
	public function getId()
	{
     	return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	/*public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	 
	 public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}*/

	public function getGoods_Received_Id()
	{
		return $this->goods_received_id;
	}
	 
	 public function setGoods_Received_Id($goods_received_id)
	{
		$this->goods_received_id = $goods_received_id;
	}

	public function getDate_Of_Issue()
	{
		return $this->date_of_issue;
	}
	 
	 public function setDate_Of_Issue($date_of_issue)
	{
		$this->date_of_issue = $date_of_issue;
	}

	public function getDept_Quantity()
	{
		return $this->dept_quantity;
	}
	 
	 public function setDept_Quantity($dept_quantity)
	{
		$this->dept_quantity = $dept_quantity;
	}

	/*public function getEmp_Id()
	{
		return $this->emp_id;
	}
	 
	 public function setEmp_Id($emp_id)
	{
		$this->emp_id = $emp_id;
	}*/

	public function getDepartments_Id()
	{
		return $this->departments_id;
	}
	 
	 public function setDepartments_Id($departments_id)
	{
		$this->departments_id = $departments_id;
	}

	public function getGoods_Received_By()
	{
		return $this->goods_received_by;
	}
	 
	 public function setGoods_Received_By($goods_received_by)
	{
		$this->goods_received_by = $goods_received_by;
	}

	public function getIssue_Goods_Status()
	{
		return $this->issue_goods_status;
	}
	 
	 public function setIssue_Goods_Status($issue_goods_status)
	{
		$this->issue_goods_status = $issue_goods_status;
	}

	public function getGoods_Issued_By()
	{
		return $this->goods_issued_by;
	}
	 
	 public function setGoods_Issued_By($goods_issued_by)
	{
		$this->goods_issued_by = $goods_issued_by;
	}

	public function getGoods_Issued_Remarks()
	{
		return $this->goods_issued_remarks;
	}
	 
	 public function setGoods_Issued_Remarks($goods_issued_remarks)
	{
		$this->goods_issued_remarks = $goods_issued_remarks;
	}      
	 
}	