<?php

namespace EmpTransfer\Model;

class EmpTransfer
{
	protected $id;
	protected $transfer_request_to;
	protected $reason_for_transfer;
	protected $spouse_new_organisation;
	protected $document_proof;
	protected $date_of_request;
	protected $from_org_transfer_status;
	protected $to_org_transfer_status;
	protected $from_org_transfer_remarks;
	protected $to_org_transfer_remarks;
	protected $employee_details_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getTransfer_Request_To()
	{
		return $this->transfer_request_to;
	}
	
	public function setTransfer_Request_To($transfer_request_to)
	{
		$this->transfer_request_to = $transfer_request_to;
	}
	
	public function getReason_For_Transfer()
	{
		return $this->reason_for_transfer;
	}
	
	public function setReason_For_Transfer($reason_for_transfer)
	{
		$this->reason_for_transfer = $reason_for_transfer;
	}
	
	public function getSpouse_New_Organisation()
	{
		return $this->spouse_new_organisation;
	}
	
	public function setSpouse_New_Organisation($spouse_new_organisation)
	{
		$this->spouse_new_organisation = $spouse_new_organisation;
	}
	
	public function getDocument_Proof()
	{
		return $this->document_proof;
	}
	
	public function setDocument_Proof($document_proof)
	{
		$this->document_proof = $document_proof;
	}
	
	public function getDate_Of_Request()
	{
		return $this->date_of_request;
	}
	
	public function setDate_Of_Request($date_of_request)
	{
		$this->date_of_request = $date_of_request;
	}
	
	public function getFrom_Org_Transfer_Status()
	{
		return $this->from_org_transfer_status;
	}
	
	public function setFrom_Org_Transfer_Status($from_org_transfer_status)
	{
		$this->from_org_transfer_status = $from_org_transfer_status;
	}
	
	public function getTo_Org_Transfer_Status()
	{
		return $this->to_org_transfer_status;
	}
	
	public function setTo_Org_Transfer_Status($to_org_transfer_status)
	{
		$this->to_org_transfer_status = $to_org_transfer_status;
	}
	
	public function getFrom_Org_Transfer_Remarks()
	{
		return $this->from_org_transfer_remarks;
	}
	
	public function setFrom_Org_Transfer_Remarks($from_org_transfer_remarks)
	{
		$this->from_org_transfer_remarks = $from_org_transfer_remarks;
	}
	
	public function getTo_Org_Transfer_Remarks()
	{
		return $this->to_org_transfer_remarks;
	}
	
	public function setTo_Org_Transfer_Remarks($to_org_transfer_remarks)
	{
		$this->to_org_transfer_remarks = $to_org_transfer_remarks;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}
	
	
}