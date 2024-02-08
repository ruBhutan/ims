<?php

namespace GoodsTransaction\Model;

class IssueGoods
{
	protected $id;
	protected $employee_details_id;
	protected $goods_received_id;
	protected $date_of_issue;
	protected $emp_quantity;

	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $emp_id;

	protected $goods_issued_by;

	protected $issue_goods_status;
    protected $goods_issued_remarks;
    protected $goods_code;
    protected $remarks;


			 
	public function getId()
	{
     	return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	 
	 public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}

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

	public function getEmp_Quantity()
	{
		return $this->emp_quantity;
	}
	 
	 public function setEmp_Quantity($emp_quantity)
	{
		$this->emp_quantity = $emp_quantity;
	}

	public function getFirst_Name()
	{
		return $this->first_name;
	}
	 
	 public function setFirst_Name($first_name)
	{
		$this->first_name = $first_name;
	}

	public function getMiddle_Name()
	{
		return $this->middle_name;
	}
	 
	 public function setMiddle_Name($middle_name)
	{
		$this->middle_name = $middle_name;
	}

	public function getLast_Name()
	{
		return $this->last_name;
	}
	 
	 public function setLast_Name($last_name)
	{
		$this->last_name = $last_name;
	}

	public function getEmp_Id()
	{
		return $this->emp_id;
	}
	 
	 public function setEmp_Id($emp_id)
	{
		$this->emp_id = $emp_id;
	}

	public function getGoods_Issued_By()
	{
		return $this->goods_issued_by;
	}
	 
	 public function setGoods_Issued_By($goods_issued_by)
	{
		$this->goods_issued_by = $goods_issued_by;
	}

	public function getIssue_Goods_Status()
	{
		return $this->issue_goods_status;
	}
	 
	 public function setIssue_Goods_Status($issue_goods_status)
	{
		$this->issue_goods_status = $issue_goods_status;
	}

	public function getGoods_Issued_Remarks()
	{
		return $this->goods_issued_remarks;
	}
	 
	 public function setGoods_Issued_Remarks($goods_issued_remarks)
	{
		$this->goods_issued_remarks = $goods_issued_remarks;
	}


	public function getGoods_Code()
	{
		return $this->goods_code;
	}
	 
	 public function setGoods_Code($goods_code)
	{
		$this->goods_code = $goods_code;
	}


	public function getRemarks()
	{
		return $this->remarks;
	}
	 
	 public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}         
	 
}	