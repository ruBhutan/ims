<?php

namespace GoodsTransaction\Model;

class ItemSubCategory
{
	protected $id;
	protected $sub_category_type;
	protected $sub_category_code;
	protected $description;
	protected $category_type;
	protected $item_category_id;
	protected $organisation_id;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getSub_Category_Type()
	{
		return $this->sub_category_type;
	}

	public function setSub_Category_Type($sub_category_type)
	{
		$this->sub_category_type = $sub_category_type;
	}

	public function getSub_Category_Code()
	{
		return $this->sub_category_code;
	}

	public function setSub_Category_Code($sub_category_code)
	{
		$this->sub_category_code = $sub_category_code;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function getCategory_Type()
	{
		return $this->category_type;
	}

	public function setCategory_Type($category_type)
	{
		$this->category_type = $category_type;
	}

	public function getItem_Category_Id()
	{
		return $this->item_category_id;
	}

	public function setItem_Category_Id($item_category_id)
	{
		$this->item_category_id = $item_category_id;
	}

	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}

	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
}