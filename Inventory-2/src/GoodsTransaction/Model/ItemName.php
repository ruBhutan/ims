<?php

namespace GoodsTransaction\Model;

class ItemName
{
	protected $id;
	protected $item_name;
	protected $description;
	protected $category_type;
	protected $item_category_id;
	protected $item_sub_category_id;
	protected $item_quantity_type_id;

	protected $organisation_id;
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getItem_Name()
	 {
		return $this->item_name; 
	 }
	 	 
	 public function setItem_Name($item_name)
	 {
		 $this->item_name = $item_name;
	 }
	 
	 public function getDescription()
	 {
		 return $this->description;
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
	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }

	 public function getItem_Sub_Category_Id()
	 {
	 	return $this->item_sub_category_id;
	 }

	 public function setItem_Sub_Category_Id($item_sub_category_id)
	 {
	 	$this->item_sub_category_id = $item_sub_category_id;
	 }

	 public function getItem_Quantity_Type_Id()
	 {
	 	return $this->item_quantity_type_id;
	 }

	 public function setItem_Quantity_Type_Id($item_quantity_type_id)
	 {
	 	$this->item_quantity_type_id = $item_quantity_type_id;
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