<?php

namespace GoodsRequisition\Model;

class GoodsRequisitionForwardApproval
{
	// Goods Requisition and Goods Requisition Details
	protected $id;
	protected $requisition_date;
	protected $item_specification;
	protected $requisition_item_quantity;
	protected $approved_item_quantity;
	protected $purpose;
	protected $employee_details_id;
	protected $item_name_id;
	protected $item_sub_category_id;
	protected $approval_date;
    protected $requisition_remarks;
    protected $goods_requisition_id;

    //Goods Requisition Forward Details
    protected $goods_requisition_details_id;
    protected $requisition_forward_quantity;
    protected $requisition_forwarded_by;
    protected $requisition_forward_status;
    protected $requisition_forward_date;
    protected $requisition_forward_remarks;
    protected $forward_approved_quantity;
    protected $approval_no;
    protected $supply_order_no;
    protected $approval_remarks;
    protected $forward_approval_date;
    protected $supply_order_date;
    protected $supply_order_remarks;
    protected $updated_by;


    //Item name and Item sub Category
    protected $item_category_type;
    protected $sub_category_type;
    protected $item_name;
    protected $item_quantity_type;

    //Employee Details
    protected $emp_id;
    protected $first_name;
    protected $middle_name;
    protected $last_name;
    protected $department_name;
    protected $unit_name;

		 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getRequisition_Date()
	 {
		return $this->requisition_date; 
	 }
	 	 
	 public function setRequisition_Date($requisition_date)
	 {
		 $this->requisition_date = $requisition_date;
	 }
	 
	 	 
	 public function getItem_Specification()
	 {
		return $this->item_specification; 
	 }
	 	 
	 public function setItem_Specification($item_specification)
	 {
		 $this->item_specification=$item_specification;
	 }
	 
	 public function getRequisition_Item_Quantity()
	 {
		return $this->requisition_item_quantity; 
	 }
	 	 
	 public function setRequisition_Item_Quantity($requisition_item_quantity)
	 {
		 $this->requisition_item_quantity = $requisition_item_quantity;
	 }

	 public function getApproved_Item_Quantity()
	 {
		return $this->approved_item_quantity; 
	 }
	 	 
	 public function setApproved_Item_Quantity($approved_item_quantity)
	 {
		 $this->approved_item_quantity = $approved_item_quantity;
	 }
	 
	 public function getPurpose()
	 {
		return $this->purpose; 
	 }
	 	 
	 public function setPurpose($purpose)
	 {
		 $this->purpose = $purpose;
	 }
	 
	 public function getEmployee_Details_Id()
	 {
		 return $this->employee_details_id;
	 }
	 
	 public function setEmployee_Details_Id($employee_details_id)
	 {
		 $this->employee_details_id = $employee_details_id;
	 }

	  public function getItem_Name_Id()
	 {
		 return $this->item_name_id;
	 }
	 
	 public function setItem_Name_Id($item_name_id)
	 {
		 $this->item_name_id = $item_name_id;
	 }

	  public function getItem_Sub_Category_Id()
	 {
		 return $this->item_sub_category_id;
	 }
	 
	 public function setItem_Sub_Category_Id($item_sub_category_id)
	 {
		 $this->item_sub_category_id = $item_sub_category_id;
	 }

	  public function getApproval_Date()
	 {
		return $this->approval_date; 
	 }
	 	 
	 public function setApproval_Date($approval_date)
	 {
		 $this->approval_date = $approval_date;
	 }

	 public function getRequisition_Remarks()
	 {
		return $this->requisition_remarks; 
	 }
	 	 
	 public function setRequisition_Remarks($requisition_remarks)
	 {
		 $this->requisition_remarks = $requisition_remarks;
	 }

	 public function getGoods_Requisition_Id()
	 {
		return $this->goods_requisition_id; 
	 }
	 	 
	 public function setGoods_Requisition_Id($goods_requisition_id)
	 {
		 $this->goods_requisition_id = $goods_requisition_id;
	 }


	 //Goods Requisition Forward Details
	 public function getGoods_Requisition_Details_Id()
	 {
		return $this->goods_requisition_details_id; 
	 }
	 	 
	 public function setGoods_Requisition_Details_Id($goods_requisition_details_id)
	 {
		 $this->goods_requisition_details_id = $goods_requisition_details_id;
	 }

	 public function getRequisition_Forward_Quantity()
	 {
		return $this->requisition_forward_quantity; 
	 }
	 	 
	 public function setRequisition_Forward_Quantity($requisition_forward_quantity)
	 {
		 $this->requisition_forward_quantity = $requisition_forward_quantity;
	 }

	 public function getRequisition_Forwarded_By()
	 {
		return $this->requisition_forwarded_by; 
	 }
	 	 
	 public function setRequisition_Forwarded_By($requisition_forwarded_by)
	 {
		 $this->requisition_forwarded_by = $requisition_forwarded_by;
	 }

	 public function getRequisition_Forward_Status()
	 {
		return $this->requisition_forward_status; 
	 }
	 	 
	 public function setRequisition_Forward_Status($requisition_forward_status)
	 {
		 $this->requisition_forward_status = $requisition_forward_status;
	 }

	 public function getRequisition_Forward_Date()
	 {
		return $this->requisition_forward_date; 
	 }
	 	 
	 public function setRequisition_Forward_Date($requisition_forward_date)
	 {
		 $this->requisition_forward_date = $requisition_forward_date;
	 }

	 public function getRequisition_Forward_Remarks()
	 {
		return $this->requisition_forward_remarks; 
	 }
	 	 
	 public function setRequisition_Forward_Remarks($requisition_forward_remarks)
	 {
		 $this->requisition_forward_remarks = $requisition_forward_remarks;
	 }

	 public function getForward_Approved_Quantity()
	 {
		return $this->forward_approved_quantity; 
	 }
	 	 
	 public function setForward_Approved_Quantity($forward_approved_quantity)
	 {
		 $this->forward_approved_quantity = $forward_approved_quantity;
	 }

	 public function getApproval_No()
	 {
		return $this->approval_no; 
	 }
	 	 
	 public function setApproval_No($approval_no)
	 {
		 $this->approval_no = $approval_no;
	 }

	 public function getSupply_Order_No()
	 {
		return $this->supply_order_no; 
	 }
	 	 
	 public function setSupply_Order_No($supply_order_no)
	 {
		 $this->supply_order_no = $supply_order_no;
	 }

	 public function getApproval_Remarks()
	 {
		return $this->approval_remarks; 
	 }
	 	 
	 public function setApproval_Remarks($approval_remarks)
	 {
		 $this->approval_remarks = $approval_remarks;
	 }

	 public function getForward_Approval_Date()
	 {
		return $this->forward_approval_date; 
	 }
	 	 
	 public function setForward_Approval_Date($forward_approval_date)
	 {
		 $this->forward_approval_date = $forward_approval_date;
	 }

	 public function getSupply_Order_Date()
	 {
		return $this->supply_order_date; 
	 }
	 	 
	 public function setSupply_Order_Date($supply_order_date)
	 {
		 $this->supply_order_date = $supply_order_date;
	 }

	 public function getSupply_Order_Remarks()
	 {
		return $this->supply_order_remarks; 
	 }
	 	 
	 public function setSupply_Order_Remarks($supply_order_remarks)
	 {
		 $this->supply_order_remarks = $supply_order_remarks;
	 }

	 public function getUpdated_By()
	 {
		return $this->updated_by; 
	 }
	 	 
	 public function setUpdated_By($updated_by)
	 {
		 $this->updated_by = $updated_by;
	 }


	 //For Item name, sub category type
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


	 //Employee Details
	 public function getEmp_Id()
	 {
		return $this->emp_id; 
	 }
	 	 
	 public function setEmp_Id($emp_id)
	 {
		 $this->emp_id = $emp_id;
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

	 public function getDepartment_Name()
	 {
		return $this->department_name; 
	 }
	 	 
	 public function setDepartment_Name($department_name)
	 {
		 $this->department_name = $department_name;
	 }

	 public function getUnit_Name()
	 {
		return $this->unit_name; 
	 }
	 	 
	 public function setUnit_Name($unit_name)
	 {
		 $this->unit_name = $unit_name;
	 }

}