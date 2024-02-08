<?php

namespace GoodsDepreciation\Model;

class FixedAsset
{
	//ItemCategory, ItemSubCategory, ItemQuantityType and ItemName
	protected $id;
	protected $category_type;
	protected $sub_category_type;
	protected $item_name;
	protected $item_quantity_type;

	protected $item_name_id;
	protected $depreciation_rate;
	protected $goods_life;
	protected $scrap_value;
	protected $depreciation_method;

	protected $entered_date;
	protected $remarks;

	
	 
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

	 public function getItem_Name_Id()
	 {
		return $this->item_name_id; 
	 }
	 	 
	 public function setItem_Name_Id($item_name_id)
	 {
		 $this->item_name_id = $item_name_id;
	 }


	 public function getDepreciation_Rate()
	 {
		return $this->depreciation_rate; 
	 }
	 	 
	 public function setDepreciation_Rate($depreciation_rate)
	 {
		 $this->depreciation_rate = $depreciation_rate;
	 }

	 public function getGoods_Life()
	 {
		return $this->goods_life; 
	 }
	 	 
	 public function setGoods_Life($goods_life)
	 {
		 $this->goods_life = $goods_life;
	 }
	 
	 public function getScrap_Value()
	 {
		return $this->scrap_value; 
	 }
	 	 
	 public function setScrap_Value($scrap_value)
	 {
		 $this->scrap_value = $scrap_value;
	 }

	 public function getDepreciation_Method()
	 {
		return $this->depreciation_method; 
	 }
	 	 
	 public function setDepreciation_Method($depreciation_method)
	 {
		 $this->depreciation_method = $depreciation_method;
	 }


	 public function getEntered_Date()
	 {
		return $this->entered_date; 
	 }
	 	 
	 public function setEntered_Date($entered_date)
	 {
		 $this->entered_date = $entered_date;
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