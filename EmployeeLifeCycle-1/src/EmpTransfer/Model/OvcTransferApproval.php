<?php

namespace EmpTransfer\Model;

class OvcTransferApproval
{
	protected $id;
	protected $ovc_transfer_status;
	protected $rejection_order;
	protected $ovc_transfer_remarks;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getOvc_Transfer_Status()
	{
		return $this->ovc_transfer_status;
	}
	
	public function setOvc_Transfer_Status($ovc_transfer_status)
	{
		$this->ovc_transfer_status = $ovc_transfer_status;
	}
	 
	public function getRejection_Order()
	{
		return $this->rejection_order;
	}
	
	public function setRejection_Order($rejection_order)
	{
		$this->rejection_order = $rejection_order;
	}
	
	public function getOvc_Transfer_Remarks()
	{
		return $this->ovc_transfer_remarks;
	}
	
	public function setOvc_Transfer_Remarks($ovc_transfer_remarks)
	{
		$this->ovc_transfer_remarks = $ovc_transfer_remarks;
	}
}