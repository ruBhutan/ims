<?php

namespace GoodsTransaction\Model;

class OrgGoodsTransfer
{
	protected $id;
	protected $organisation_from_id;
	protected $organisation_to_id;
	protected $organisation_goods_id;
	protected $transfer_date;
	protected $approve_date;
	protected $transfer_status;
	protected $transfer_remarks;
	protected $approve_remarks;
	protected $employee_details_from_id;
	protected $employee_details_to_id;
  
    protected $transfer_quantity;
    protected $item_received_purchased_id;
    protected $item_received_donation_id;

    protected $item_name_id;
    protected $item_received_type;
    protected $item_purchasing_rate;
    protected $item_received_transfered_id;
  
  	
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	   public function getOrganisation_From_Id()
	 {
	 	return $this->organisation_from_id;
	 }

	 public function setOrganisation_From_Id($organisation_from_id)
	 {
	 	$this->organisation_from_id = $organisation_from_id;
	 }

	 public function getOrganisation_To_Id()
	 {
	 	return $this->organisation_to_id;
	 }

	 public function setOrganisation_To_Id($organisation_to_id)
	 {
	 	$this->organisation_to_id = $organisation_to_id;
	 } 

	 public function getOrganisation_Goods_Id()
	 {
	 	return $this->organisation_goods_id;
	 }

	 public function setOrganisation_Goods_Id($organisation_goods_id)
	 {
	 	$this->organisation_goods_id = $organisation_goods_id;
	 }

	 public function getTransfer_Date()
	 {
	 	return $this->transfer_date;
	 }

	 public function setTransfer_Date($transfer_date)
	 {
	 	$this->transfer_date = $transfer_date;
	 }

	 public function getApprove_Date()
	 {
	 	return $this->approve_date;
	 }

	 public function setApprove_Date($approve_date)
	 {
	 	$this->approve_date = $approve_date;
	 }

	 public function getTransfer_Status()
	 {
	 	return $this->transfer_status;
	 }

	 public function setTransfer_Status($transfer_status)
	 {
	 	$this->transfer_status = $transfer_status;
	 }

	 public function getTransfer_Remarks()
	 {
	 	return $this->transfer_remarks;
	 }

	 public function setTransfer_Remarks($transfer_remarks)
	 {
	 	$this->transfer_remarks = $transfer_remarks;
	 } 

	 public function getApprove_Remarks()
	 {
	 	return $this->approve_remarks;
	 }

	 public function setApprove_Remarks($approve_remarks)
	 {
	 	$this->approve_remarks = $approve_remarks;
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

	public function getTransfer_Quantity()
	{
		return $this->transfer_quantity;
	}	 
	 public function setTransfer_Quantity($transfer_quantity)
	{
		$this->transfer_quantity = $transfer_quantity;
	}


	public function getItem_Received_Purchased_Id()
	 {
	 	return $this->item_received_purchased_id;
	 }

	 public function setItem_Received_Purchased_Id($item_received_purchased_id)
	 {
	 	$this->item_received_purchased_id = $item_received_purchased_id;
	 }

	 public function getItem_Received_Donation_Id()
	 {
	 	return $this->item_received_donation_id;
	 }

	 public function setItem_Received_Donation_Id($item_received_donation_id)
	 {
	 	$this->item_received_donation_id = $item_received_donation_id;
	 }

	 public function getItem_Name_Id()
	 {
	 	return $this->item_name_id;
	 }

	 public function setItem_Name_Id($item_name_id)
	 {
	 	$this->item_name_id = $item_name_id;
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


	  public function getItem_Received_Transfered_Id()
	 {
	 	return $this->item_received_transfered_id;
	 }

	 public function setItem_Received_Transfered_Id($item_received_transfered_id)
	 {
	 	$this->item_received_transfered_id = $item_received_transfered_id;
	 }

}