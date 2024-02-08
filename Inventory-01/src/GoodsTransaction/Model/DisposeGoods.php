<?php

namespace GoodsTransaction\Model;

class DisposeGoods
{
	protected $id;
	protected $remarks;
    protected $item_quantity_disposed;
    protected $item_name_id;
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	 public function getRemarks()
	 {
	 	return $this->remarks;
	 }

	 public function setRemarks($remarks)
	 {
	 	$this->remarks = $remarks;
	 } 

	public function getItem_Quantity_Disposed()
	{
		return $this->item_quantity_disposed;
	}	 
	 public function setItem_Quantity_Disposed($item_quantity_disposed)
	{
		$this->item_quantity_disposed = $item_quantity_disposed;
	}

}