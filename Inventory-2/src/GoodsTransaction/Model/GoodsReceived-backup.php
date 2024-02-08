<?php

namespace GoodsTransaction\Model;

class GoodsReceived
{
	protected $id;
	protected $item_received_type;
	protected $item_purchasing_rate;
	protected $item_quantity;
	protected $item_specification;
	protected $item_amount;
	protected $item_entry_date;
	protected $item_in_stock;
	protected $item_stock_status;
	protected $item_received_by;
	protected $item_received_date;
	protected $item_updated_by;
	protected $item_status;
	protected $remarks;
	protected $item_name_id;
	protected $item_sub_category_id;
	protected $item_quantity_type_id;
	protected $item_received_purchased_id;
	protected $item_received_donation_id;
	
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getItem_Received_Type()
	 {
		return $this->item_received_type; 
	 }
	 	 
	 public function setItem_Received_Type($item_received_type)
	 {
		 $this->item_received_type = $item_received_type;
	 }

	 public function getItem_Purchasing_Rate()
	 {
		return $this->item_purchasing_rate; 
	 }
	 	 
	 public function setItem_Purchasing_Rate($item_purchasing_rate)
	 {
		 $this->item_purchasing_rate = $item_purchasing_rate;
	 }
	 
	 public function getItem_Quantity()
	 {
		 return $this->item_quantity;
	 }
	 
	 public function setItem_Quantity($item_quantity)
	 {
		 $this->item_quantity = $item_quantity;
	 }

	public function getItem_Specification()
	{
		return $this->item_specification;
	}

	public function setItem_Specification($item_specification)
	{
		$this->item_specification = $item_specification;
	}

	public function getItem_Amount()
	{
		return $this->item_amount;
	}

	public function setItem_Amount($item_amount)
	{
		$this->item_amount = $item_amount;
	}

	public function getItem_Entry_Date()
	{
		return $this->item_entry_date;
	}

	public function setItem_Entry_Date($item_entry_date)
	{
		$this->item_entry_date = $item_entry_date;
	}

	public function getItem_In_Stock()
	{
		return $this->item_in_stock;
	}

	public function setItem_In_Stock($item_in_stock)
	{
		$this->item_in_stock = $item_in_stock;
	}

	public function getItem_Stock_Status()
	{
		return $this->item_stock_status;
	}

	public function setItem_Stock_Status($item_stock_status)
	{
		$this->item_stock_status = $item_stock_status;
	}



	public function getItem_Received_By()
	 {
		return $this->item_received_by; 
	 }
	 	 
	 public function setItem_Received_By($item_received_by)
	 {
		 $this->item_received_by = $item_received_by;
	 }

	 public function getItem_Received_Date()
	 {
		return $this->item_received_date; 
	 }
	 	 
	 public function setItem_Received_Date($item_received_date)
	 {
		 $this->item_received_date = $item_received_date;
	 }
	 
	 public function getItem_Updated_By()
	 {
		 return $this->item_updated_by;
	 }
	 
	 public function setItem_Updated_By($item_updated_by)
	 {
		 $this->item_updated_by = $item_updated_by;
	 }

	public function getItem_Status()
	{
		return $this->item_status;
	}

	public function setItem_Status($item_status)
	{
		$this->item_status = $item_status;
	}


	public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }

	 public function getItem_Name_Id()
	 {
	 	return $this->item_name_id;
	 }

	 public function setItem_Name_Id()
	 {
	 	$this->item_name_id = $item_name_id;
	 }

	 public function getItem_Sub_Category_Id()
	 {
	 	return $this->item_sub_category_id;
	 }

	 public function setItem_Sub_Category_Id()
	 {
	 	$this->item_sub_category_id = $item_sub_category_id;
	 }

	 public function getItem_Quantity_Type_Id()
	 {
	 	return $this->item_quantity_type_id;
	 }

	 public function setItem_Quantity_Type_Id()
	 {
	 	$this->item_quantity_type_id = $item_quantity_type_id;
	 }

	 public function getItem_Received_Purchased_Id()
	 {
	 	return $this->item_received_purchased_id;
	 }

	 public function setItem_Received_Purchased_Id()
	 {
	 	$this->item_received_purchased_id = $item_received_purchased_id;
	 }

	 public function getItem_Received_Donation_Id()
	 {
	 	return $this->item_received_donation_id;
	 }

	 public function setItem_Received_Donation_Id()
	 {
	 	$this->item_received_donation_id = $item_received_donation_id;
	 }
}


class ItemReceivedPurchased
{
	protected $reference_no;
	protected $reference_date;
	protected $supplier_order_no;
	protected $supplier_details_id;


	public function getReference_No()
	 {
	 	return $this->reference_no;
	 }

	 public function setReference_No()
	 {
	 	$this->reference_no = $reference_no;
	 }

	 public function getReference_Date()
	 {
	 	return $this->reference_date;
	 }

	 public function setReference_Date()
	 {
	 	$this->reference_date = $reference_date;
	 }

	 public function getSupplier_Order_No()
	 {
	 	return $this->supplier_order_no;
	 }

	 public function setSupplier_Order_No()
	 {
	 	$this->supplier_order_no = $supplier_order_no;
	 }

	 public function getSupplier_Details_Id()
	 {
	 	return $this->supplier_details_id;
	 }

	 public function setSupplier_Details_Id()
	 {
	 	$this->supplier_details_id = $supplier_details_id;
	 }
}


class ItemReceivedDonation
{
	protected $donation_id;

	public function getDonation_Id()
	 {
	 	return $this->donation_id;
	 }

	 public function setDonation_Id()
	 {
	 	$this->donation_id = $donation_id;
	 }
}