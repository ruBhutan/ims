<?php

namespace GoodsRequisition\Model;

class GoodsTransfer
{
	protected $id;
	protected $department_head_from;
	protected $department_head_to;
	protected $transfer_date;
	protected $item_name_id;
	protected $item_quantity;
	protected $rate;
	protected $amount;
	protected $remarks;
	
	
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getDepartment_Head_From()
	 {
		return $this->department_head_from; 
	 }
	 	 
	 public function setDepartment_Head_From($department_head_from)
	 {
		 $this->department_head_from = $department_head_from;
	 }
	 
	 public function getDepartment_Head_To()
	 {
		 return $this->department_head_to;
	 }
	 
	 public function setDepartment_Head_To($department_head_to)
	 {
		 $this->department_head_to = $department_head_to;
	 }

	 public function getTransfer_Date()
	 {
		 return $this->transfer_date;
	 }
	 
	 public function setTransfer_Date($transfer_date)
	 {
		 $this->transfer_date = $transfer_date;
	 }

	  public function getItem_Name_Id()
	 {
		 return $this->item_name_id;
	 }
	 
	 public function setItem_Name_Id($item_name_id)
	 {
		 $this->item_name_id = $item_name_id;
	 }

	 public function getItem_Quantity()
	 {
		 return $this->item_quantity;
	 }
	 
	 public function setItem_Quantity($item_quantity)
	 {
		 $this->item_quantity = $item_quantity;
	 }

	 public function getRate()
	 {
		 return $this->rate;
	 }
	 
	 public function setRate($rate)
	 {
		 $this->rate = $rate;
	 }

	  public function getAmount()
	 {
		 return $this->amount;
	 }
	 
	 public function setAmount($amount)
	 {
		 $this->amount = $amount;
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