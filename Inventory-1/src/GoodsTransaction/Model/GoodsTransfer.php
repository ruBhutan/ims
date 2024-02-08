<?php

namespace GoodsTransaction\Model;

class GoodsTransfer
{
	protected $id;
	//protected $department_from_id;
	protected $department_from_id;
	protected $department_to_id;
	protected $employee_details_from_id;
	protected $employee_details_to_id;
    protected $department_goods_id;
    protected $goods_transfer_date;
    protected $transfer_update_date;
    protected $goods_transfer_status;
    protected $transfer_quantity;
    protected $transfer_applied_remarks;
    protected $transfer_approved_remarks;

	//From different table 
	/*protected $item_category_type;
	protected $sub_category_type; */
	protected $item_name;
	protected $item_quantity_type;
	protected $unit_name;
	protected $item_specification;
	
	
	
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	  public function getDepartment_From_Id()
	{
		return $this->department_from_id;
	}	 
	 public function setDepartment_From_Id($department_from_id)
	{
		$this->department_from_id = $department_from_id;
	}
	 
	 public function getDepartment_To_Id()
	{
		return $this->department_to_id;
	}	 
	 public function setDepartment_To_Id($department_to_id)
	{
		$this->department_to_id = $department_to_id;
	}
	
	 public function getEmployee_Details_From_Id()
	{
		return $this->employee_details_from_id;
	}	 
	 public function setEmployee_Details_From_Id($employee_details_from_id)
	{
		$this->employee_details_from_id = $employee_details_from_id;
	}

	 public function getEmployee_Details_To_Id()
	{
		return $this->employee_details_to_id;
	}	 
	 public function setEmployee_Details_To_Id($employee_details_to_id)
	{
		$this->employee_details_to_id = $employee_details_to_id;
	}

	 public function getDepartment_Goods_Id()
	{
		return $this->department_goods_id;
	}	 
	 public function setDepartment_Goods_Id($department_goods_id)
	{
		$this->department_goods_id = $department_goods_id;
	}

	 public function getGoods_Transfer_Date()
	{
		return $this->goods_transfer_date;
	}	 
	 public function setGoods_Transfer_Date($goods_transfer_date)
	{
		$this->goods_transfer_date = $goods_transfer_date;
	}

	public function getTransfer_Update_Date()
	{
		return $this->transfer_update_date;
	}	 
	 public function setTransfer_Update_Date($transfer_update_date)
	{
		$this->transfer_update_date = $transfer_update_date;
	}

	public function getGoods_Transfer_Status()
	{
		return $this->goods_transfer_status;
	}	 
	 public function setGoods_Transfer_Status($goods_transfer_status)
	{
		$this->goods_transfer_status = $goods_transfer_status;
	}

    public function getTransfer_Quantity()
	{
		return $this->transfer_quantity;
	}	 
	 public function setTransfer_Quantity($transfer_quantity)
	{
		$this->transfer_quantity = $transfer_quantity;
	}

	public function getTransfer_Applied_Remarks()
	{
		return $this->transfer_applied_remarks;
	}	 
	 public function setTransfer_Applied_Remarks($transfer_applied_remarks)
	{
		$this->transfer_applied_remarks = $transfer_applied_remarks;
	}

	public function getTransfer_Approved_Remarks()
	{
		return $this->transfer_approved_remarks;
	}	 
	 public function setTransfer_Approved_Remarks($transfer_approved_remarks)
	{
		$this->transfer_approved_remarks = $transfer_approved_remarks;
	}

	public function getItem_Name()
	{
		return $this->item_name;
	}	 
	 public function setItem_Name($item_name)
	{
		$this->item_name = $item_name;
	}

	public function getUnit_Name()
	{
		return $this->unit_name;
	}	 
	 public function setUnit_Name($unit_name)
	{
		$this->unit_name = $unit_name;
	}

	public function getItem_Specification()
	{
		return $this->item_specification;
	}	 
	 public function setItem_Specification($item_specification)
	{
		$this->item_specification = $item_specification;
	}
}