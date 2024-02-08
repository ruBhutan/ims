<?php

namespace GoodsTransaction\Model;

class GoodsTransaction
{
	//ItemCategory, ItemSubCategory, ItemQuantityType and ItemName
	protected $id;
	protected $category_type;
	protected $sub_category_type;
	protected $description;
	protected $item_category_id;
	protected $item_quantity_type;
	protected $remarks;
	protected $item_name;
	protected $item_sub_category_id;
	protected $major_class_id;
	protected $major_class;
	protected $category_code;
	protected $sub_category_code;

    //ItemSupplier
	protected $supplier_name;
	protected $supplier_license_no;
	protected $supplier_tpn_no;
	protected $supplier_bank_acc_no;
	protected $supplier_contact_no;
	protected $supplier_address;
	protected $supplier_status;
	protected $organisation_id;
	protected $organisation_name;

	protected $from_date;
	protected $to_date;
	protected $supporting_documents;

    //ItemDonor
	protected $donor_name;

	//GoodsReceived
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
	protected $item_verified_by;
	protected $item_status;
	protected $received_type_foreign_key;
	protected $item_name_id;
	protected $item_quantity_type_id;
	protected $item_received_purchased_id;
	protected $item_received_donation_id;
	protected $item_received_transfered_id;
	protected $reference_no;
	protected $reference_date;
	protected $supplier_order_no;
	protected $supplier_details_id;
	protected $item_donor_details_id;
	protected $receipt_voucher_no;
	protected $created_at;


	//Issue of Goods
	protected $employee_details_id;
	protected $goods_received_id;
	protected $date_of_issue;
	protected $emp_quantity;
	protected $dept_quantity;
	protected $emp_id;
	protected $issue_goods_status;
	protected $goods_issued_remarks;
	protected $goods_code;

	//Goods Surrender
	protected $emp_goods_id;
	protected $goods_surrender_date;
	protected $goods_surrender_status;
	protected $surrender_quantity;
	protected $goods_surrendered_remarks;

	protected $surrender_date;
	protected $approved_date;
	protected $surrender_status;
	protected $surrender_by;
	protected $approve_by;
	protected $surrender_remarks;

	//Goods Transfer
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
    protected $department_id;
    protected $department_name;


	//Employee Details
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $departments_id;
	protected $departments_units_id;
	protected $unit_name;


	//Sub store nominee status
	protected $status;

	protected $item_quantity_disposed;


	// Organisation Goods Transfer
	protected $organisation_from_id;
	protected $organisation_to_id;
	protected $organisation_goods_id;
	protected $transfer_date;
	protected $approve_date;
	protected $transfer_status;
	protected $transfer_remarks;
	protected $approve_remarks;

	protected $approved_balance_quantity;

	
	 
	 //ItemCategory, ItemSubCategory and ItemName	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getCategory_Type()
	 {
		return $this->category_type;
	 }
	 	 
	 public function setCategory_Type($category_type)
	 {
		 $this->category_type = $category_type;
	 }

	 public function getSub_Category_Type()
	 {
		return $this->sub_category_type; 
	 }
	 	 
	 public function setSub_Category_Type($sub_category_type)
	 {
		 $this->sub_category_type = $sub_category_type;
	 }
	 
	 public function getDescription()
	 {
		 return $this->description;
	 }
	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }

	public function getItem_Category_Id()
	{
		return $this->item_category_id;
	}

	public function setItem_Category_Id($item_category_id)
	{
		$this->item_category_id = $item_category_id;
	}

	public function getItem_Quantity_Type()
	{
		return $this->item_quantity_type;
	}

	public function setItem_Quantity_Type($item_quantity_type)
	{
		$this->item_quantity_type = $item_quantity_type;
	}

	public function getRemarks()
	{
		return $this->remarks;
	}

	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}

	public function getItem_Name()
	{
		return $this->item_name;
	}

	public function setItem_Name($item_name)
	{
		$this->item_name = $item_name;
	}

	public function getItem_Sub_Category_Id()
	{
		return $this->item_sub_category_id;
	}

	public function setItem_Sub_Category_Id($item_sub_category_id)
	{
		$this->item_sub_category_id = $item_sub_category_id;
	}

	public function getMajor_Class_Id()
	 {
		 return $this->major_class_id;
	 }
	 
	 public function setMajor_Class_Id($major_class_id)
	 {
		 $this->major_class_id = $major_class_id;
	 }


	 public function getCategory_Code()
	 {
		return $this->category_code; 
	 }
	 	 
	 public function setCategory_Code($category_code)
	 {
		 $this->category_code = $category_code;
	 }

	 public function getSub_Category_Code()
	{
		return $this->sub_category_code;
	}

	public function setSub_Category_Code($sub_category_code)
	{
		$this->sub_category_code = $sub_category_code;
	}


	 public function getMajor_Class()
	 {
		 return $this->major_class;
	 }
	 
	 public function setMajor_Class($major_class)
	 {
		 $this->major_class = $major_class;
	 }


    //ItemSupplier
	public function getSupplier_Name()
	 {
		return $this->supplier_name; 
	 }
	 	 
	 public function setSupplier_Name($supplier_name)
	 {
		 $this->supplier_name = $supplier_name;
	 }


	  public function getSupplier_License_No()
	 {
		return $this->supplier_license_no; 
	 }
	 	 
	 public function setSupplier_License_no($supplier_license_no)
	 {
		 $this->supplier_license_no = $supplier_license_no;
	 }

	 public function getSupplier_Tpn_No()
	 {
		return $this->supplier_tpn_no; 
	 }
	 	 
	 public function setSupplier_Tpn_no($supplier_tpn_no)
	 {
		 $this->supplier_tpn_no = $supplier_tpn_no;
	 }
	 
	 public function getSupplier_Bank_Acc_No()
	 {
		 return $this->supplier_bank_acc_no;
	 }
	 
	 public function setSupplier_Bank_Acc_No($supplier_bank_acc_no)
	 {
		 $this->supplier_bank_acc_no = $supplier_bank_acc_no;
	 }

	 public function getSupplier_Contact_No()
	 {
		 return $this->supplier_contact_no;
	 }
	 
	 public function setSupplier_Contact_No($supplier_contact_no)
	 {
		 $this->supplier_contact_no = $supplier_contact_no;
	 }

	public function getSupplier_Address()
	{
		return $this->supplier_address;
	}

	public function setSupplier_Address($supplier_address)
	{
		$this->supplier_address = $supplier_address;
	}

	public function getSupplier_Status()
	{
		return $this->supplier_status;
	}

	public function setSupplier_Status($supplier_status)
	{
		$this->supplier_status = $supplier_status;
	}

	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}

	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}

	public function getOrganisation_Name()
	{
		return $this->organisation_name;
	}

	public function setOrganisation_Name($organisation_name)
	{
		$this->organisation_name = $organisation_name;
	}


	public function getFrom_Date()
	{
		return $this->from_date;
	}

	public function setFrom_Date($from_date)
	{
		$this->from_date = $from_date;
	}


	public function getTo_Date()
	{
		return $this->to_date;
	}

	public function setTo_Date($to_date)
	{
		$this->to_date = $to_date;
	}

	public function getSupporting_Documents()
	{
		return $this->supporting_documents;
	}

	public function setSupporting_Documents($supporting_documents)
	{
		$this->supporting_documents = $supporting_documents;
	}



    //ItemDonor
	public function getDonor_Name()
	 {
		return $this->donor_name; 
	 }
	 	 
	 public function setDonor_Name($donor_name)
	 {
		 $this->donor_name = $donor_name;
	 }


	 //GoodsReceived	 
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

	 public function getItem_Verified_By()
	 {
		 return $this->item_verified_by;
	 }
	 
	 public function setItem_Verified_By($item_verified_by)
	 {
		 $this->item_verified_by = $item_verified_by;
	 }

	public function getItem_Status()
	{
		return $this->item_status;
	}

	public function setItem_Status($item_status)
	{
		$this->item_status = $item_status;
	}


	 public function getReceived_Type_Foreign_Key()
	 {
		return $this->received_type_foreign_key; 
	 }
	 	 
	 public function setReceived_Type_Foreign_Key($received_type_foreign_key)
	 {
		 $this->received_type_foreign_key = $received_type_foreign_key;
	 }

	 public function getItem_Name_Id()
	 {
	 	return $this->item_name_id;
	 }

	 public function setItem_Name_Id($item_name_id)
	 {
	 	$this->item_name_id = $item_name_id;
	 }

	 public function getItem_Quantity_Type_Id()
	 {
	 	return $this->item_quantity_type_id;
	 }

	 public function setItem_Quantity_Type_Id($item_quantity_type_id)
	 {
	 	$this->item_quantity_type_id = $item_quantity_type_id;
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

	 public function getItem_Received_Transfered_Id()
	 {
	 	return $this->item_received_transfered_id;
	 }

	 public function setItem_Received_Transfered_Id($item_received_transfered_id)
	 {
	 	$this->item_received_transfered_id = $item_received_transfered_id;
	 }

	 public function getReference_No()
	 {
	 	return $this->reference_no;
	 }

	 public function setReference_No($reference_no)
	 {
	 	$this->reference_no = $reference_no;
	 }

	 public function getReference_Date()
	 {
	 	return $this->reference_date;
	 }

	 public function setReference_Date($reference_date)
	 {
	 	$this->reference_date = $reference_date;
	 }

	 public function getSupplier_Order_No()
	 {
	 	return $this->supplier_order_no;
	 }

	 public function setSupplier_Order_No($supplier_order_no)
	 {
	 	$this->supplier_order_no = $supplier_order_no;
	 }

	 public function getSupplier_Details_Id()
	 {
	 	return $this->supplier_details_id;
	 }

	 public function setSupplier_Details_Id($supplier_details_id)
	 {
	 	$this->supplier_details_id = $supplier_details_id;
	 }

	 public function getItem_Donor_Details_Id()
	 {
	 	return $this->item_donor_details_id;
	 }

	 public function setItem_Donor_Details_Id($item_donor_details_id)
	 {
	 	$this->item_donor_details_id = $item_donor_details_id;
	 }

	 public function getReceipt_Voucher_No()
	 {
	 	return $this->receipt_voucher_no;
	 }

	 public function setReceipt_Voucher_No($receipt_voucher_no)
	 {
	 	$this->receipt_voucher_no = $receipt_voucher_no;
	 }

	 public function getCreated_At()
	 {
	 	return $this->created_at;
	 }

	 public function setCreated_At($created_at)
	 {
	 	$this->created_at = $created_at;
	 }


	 //Issue of Goods
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

	public function getDept_Quantity()
	{
		return $this->dept_quantity;
	}
	 
	 public function setDept_Quantity($dept_quantity)
	{
		$this->dept_quantity = $dept_quantity;
	} 

	public function getEmp_Id()
	{
		return $this->emp_id;
	}
	 
	 public function setEmp_Id($emp_id)
	{
		$this->emp_id = $emp_id;
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

	public function getGoods_Surrendered_Remarks()
	{
		return $this->goods_surrendered_remarks;
	}
	 
	 public function setGoods_Surrendered_Remarks($goods_surrendered_remarks)
	{
		$this->goods_surrendered_remarks = $goods_surrendered_remarks;
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


	public function getSurrender_By()
	{
		return $this->surrender_by;
	}
	 
	 public function setSurrender_By($surrender_by)
	{
		$this->surrender_by = $surrender_by;
	}

	public function getApprove_By()
	{
		return $this->approve_by;
	}
	 
	 public function setApprove_By($approve_by)
	{
		$this->approve_by = $approve_by;
	}

	public function getSurrender_Remarks()
	{
		return $this->surrender_remarks;
	}
	 
	 public function setSurrender_Remarks($surrender_remarks)
	{
		$this->surrender_remarks = $surrender_remarks;
	}



	// Goods Transfer
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

	public function getDepartment_Id()
	{
		return $this->department_id;
	}	 
	 public function setDepartment_Id($department_id)
	{
		$this->department_id = $department_id;
	}

	public function getDepartment_Name()
	{
		return $this->department_name;
	}	 
	 public function setDepartment_Name($department_name)
	{
		$this->department_name = $department_name;
	}   


	//Employee Details
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

	public function getDepartments_Id()
	{
		return $this->departments_id;
	}	 
	 public function setDepartments_Id($departments_id)
	{
		$this->departments_id = $departments_id;
	}

	public function getDepartments_Units_Id()
	{
		return $this->departments_units_id;
	}	 
	 public function setDepartments_Units_Id($departments_units_id)
	{
		$this->departments_units_id = $departments_units_id;
	}

	public function getUnit_Name()
	{
		return $this->unit_name;
	}	 
	 public function setUnit_Name($unit_name)
	{
		$this->unit_name = $unit_name;
	}

	public function getStatus()
	 {
	 	return $this->status;
	 }

	 public function setStatus($status)
	 {
	 	$this->status = $status;
	 }


	 public function getItem_Quantity_Disposed()
	 {
	 	return $this->item_quantity_disposed;
	 }

	 public function setItem_Quantity_Disposed($item_quantity_disposed)
	 {
	 	$this->item_quantity_disposed = $item_quantity_disposed;
	 }


	 // Organisation Goods Transfer
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

	 public function getApproved_Balance_Quantity()
	{
		return $this->approved_balance_quantity;
	}
	 
	 public function setApproved_Balance_Quantity($approved_balance_quantity)
	{
		$this->approved_balance_quantity = $approved_balance_quantity;
	}           	           
}	