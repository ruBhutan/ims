<?php

namespace GoodsTransaction\Model;

class GoodsSurrender
{
	protected $id;
	protected $employee_details_id;
	protected $emp_goods_id;
	protected $goods_surrender_date;
	protected $goods_surrender_status;
	protected $surrender_quantity;
	protected $remarks;

	//From different table 
	protected $item_category_type;
	protected $sub_category_type;
	protected $item_name;
	protected $item_quantity_type;
	protected $goods_issued_remarks;

			 
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

	public function getEmp_Goods_Id()
	{
		return $this->emp_goods_id;
	}
	 
	 public function setEmp_Goods_Id($emp_goods_id)
	{
		$this->emp_goods_id = $emp_goods_id;
	}

	public function getGoods_Surrender_Date()
	{
		return $this->goods_surrender_date;
	}
	 
	 public function setGoods_Surrender_Date($goods_surrender_date)
	{
		$this->goods_surrender_date = $goods_surrender_date;
	}

	public function getGoods_Surrender_Status()
	{
		return $this->goods_surrender_status;
	}
	 
	 public function setGoods_Surrender_Status($goods_surrender_status)
	{
		$this->goods_surrender_status = $goods_surrender_status;
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
	 
}	