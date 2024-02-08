<?php

namespace GoodsTransaction\Model;

class DeptGoodsSurrender
{
	protected $id;
	protected $department_goods_id;
	protected $surrender_by;
	protected $approved_by;
	protected $surrender_date;
	protected $approved_date;
	protected $surrender_status;
	protected $surrender_quantity;
	protected $surrender_remarks;
	protected $remarks;

	//From different table 
	protected $item_category_type;
	protected $sub_category_type;
	protected $item_name;
	protected $item_quantity_type;
	protected $goods_issued_remarks;

	protected $employee_details_id;
	protected $departments_id;
	protected $unit_name;
	protected $department_name;

			 
	public function getId()
	{
     	return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getDepartment_Goods_Id()
	{
		return $this->department_goods_id;
	}
	 
	 public function setDepartment_Goods_Id($department_goods_id)
	{
		$this->department_goods_id = $department_goods_id;
	}

	public function getSurrender_By()
	{
		return $this->surrender_by;
	}
	 
	 public function setSurrender_By($surrender_by)
	{
		$this->surrender_by = $surrender_by;
	}

	public function getApproved_By()
	{
		return $this->approved_by;
	}
	 
	 public function setApproved_By($approved_by)
	{
		$this->approved_by = $approved_by;
	}

	public function getSurrender_Date()
	{
		return $this->surrender_date;
	}
	 
	 public function setSurrender_Date($surrender_date)
	{
		$this->surrender_date = $surrender_date;
	}

	public function getApproved_Date()
	{
		return $this->approved_date;
	}
	 
	 public function setApproved_Date($approved_date)
	{
		$this->approved_date = $approved_date;
	}

	public function getSurrender_Status()
	{
		return $this->surrender_status;
	}
	 
	 public function setSurrender_Status($surrender_status)
	{
		$this->surrender_status = $surrender_status;
	}

		public function getSurrender_Quantity()
	{
		return $this->surrender_quantity;
	}
	 
	 public function setSurrender_Quantity($surrender_quantity)
	{
		$this->surrender_quantity = $surrender_quantity;
	}

	public function getRemarks()
	{
		return $this->remarks;
	}
	 
	 public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	} 

	public function getSurrender_Remarks()
	{
		return $this->surrender_remarks;
	}
	 
	 public function setSurrender_Remarks($surrender_remarks)
	{
		$this->surrender_remarks = $surrender_remarks;
	}


	public function getItem_Category_Type()
	{
		return $this->item_category_type;
	}
	 
	 public function setItem_Category_Type($item_category_type)
	{
		$this->item_category_type = $item_category_type;
	} 

    public function getSub_Category_Type()
	{
		return $this->sub_category_type;
	}
	 
	 public function setSub_Category_Type($sub_category_type)
	{
		$this->sub_category_type = $sub_category_type;
	}

	public function getItem_Name()
	{
		return $this->item_name;
	}
	 
	 public function setItem_Name($item_name)
	{
		$this->item_name = $item_name;
	}

	public function getItem_Quantity_Type()
	{
		return $this->item_quantity_type;
	}
	 
	 public function setItem_Quantity_Type($item_quantity_type)
	{
		$this->item_quantity_type = $item_quantity_type;
	}

	public function getGoods_Issued_Remarks()
	{
		return $this->goods_issued_remarks;
	}
	 
	 public function setGoods_Issued_Remarks($goods_issued_remarks)
	{
		$this->goods_issued_remarks = $goods_issued_remarks;
	}

	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	 
	 public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}

	public function getDepartments_Id()
	{
		return $this->departments_id;
	}
	 
	 public function setDepartments_Id($departments_id)
	{
		$this->departments_id = $departments_id;
	}

	public function getUnit_Name()
	{
		return $this->unit_name;
	}
	 
	 public function setUnit_Name($unit_name)
	{
		$this->unit_name = $unit_name;
	}


	public function getDepartment_Name()
	{
		return $this->department_name;
	}
	 
	 public function setDepartment_Name($department_name)
	{
		$this->department_name = $department_name;
	}
	 
}	