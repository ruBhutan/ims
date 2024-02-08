<?php

namespace EmpPromotion\Model;

class RejectPromotion
{
	protected $id;
	protected $promotion_status;
	protected $promotion_remarks;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getPromotion_Status()
	{
		return $this->promotion_status;
	}
	
	public function setPromotion_Status($promotion_status)
	{
		$this->promotion_status = $promotion_status;
	}
	 
	public function getPromotion_Remarks()
	{
		return $this->promotion_remarks;
	}
	
	public function setPromotion_Remarks($promotion_remarks)
	{
		$this->promotion_remarks = $promotion_remarks;
	}
	
	
}