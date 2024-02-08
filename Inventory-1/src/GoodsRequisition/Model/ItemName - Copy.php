<?php

namespace GoodsRequisition\Model;

class ItemName
{
	protected $id;
	protected $item_name;
	protected $description;
	protected $item_sub_category_id;
 
	 	 
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
}