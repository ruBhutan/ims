<?php

namespace GoodsTransaction\Model;

class ItemQuantityType
{
	protected $id;
	protected $item_quantity_type;
	protected $organisation_id;
	protected $remarks;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getItem_Quantity_Type()
	{
		return $this->item_quantity_type;
	}

	public function setItem_Quantity_Type($item_quantity_type)
	{
		$this->item_quantity_type = $item_quantity_type;
	}

	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}

	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
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
