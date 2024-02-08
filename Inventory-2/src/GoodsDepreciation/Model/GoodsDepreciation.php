<?php

namespace GoodsDepreciation\Model;

class GoodsDepreciation
{
	//ItemCategory, ItemSubCategory, ItemQuantityType and ItemName
	protected $id;
	protected $item_category_type;
	protected $sub_category_type;
	protected $item_name;
	protected $item_quantity_type;
	protected $item_quantity;
	protected $item_purchasing_rate;
	protected $item_received_date;

	protected $goods_received_id;
	protected $goods_received_date;
	protected $goods_value;
	protected $depreciation_rate;
	protected $goods_life;
	protected $scrap_value;
	protected $depreciation_method;
	protected $goods_depreciation_quantity;
	protected $depreciated_year;
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

	 public function getItem_Quantity()
	 {
		return $this->item_quantity; 
	 }
	 	 
	 public function setItem_Quantity($item_quantity)
	 {
		 $this->item_quantity = $item_quantity;
	 }

	 public function getItem_Purchasing_Rate()
	 {
		return $this->item_purchasing_rate; 
	 }
	 	 
	 public function setItem_Purchasing_Rate($item_purchasing_rate)
	 {
		 $this->item_purchasing_rate = $item_purchasing_rate;
	 }

	 public function getItem_Received_Date()
	 {
		return $this->item_received_date; 
	 }
	 	 
	 public function setItem_Received_Date($item_received_date)
	 {
		 $this->item_received_date = $item_received_date;
	 }

	 

	 public function getGoods_Received_Id()
	 {
		return $this->goods_received_id; 
	 }
	 	 
	 public function setGoods_Received_Id($goods_received_id)
	 {
		 $this->goods_received_id = $goods_received_id;
	 }

	 public function getGoods_Received_Date()
	 {
		return $this->goods_received_date; 
	 }
	 	 
	 public function setGoods_Received_Date($goods_received_date)
	 {
		 $this->goods_received_date = $goods_received_date;
	 }

	 public function getGoods_Value()
	 {
		return $this->goods_value; 
	 }
	 	 
	 public function setGoods_Value($goods_value)
	 {
		 $this->goods_value = $goods_value;
	 }

	 public function getDepreciation_Method()
	 {
		return $this->depreciation_method; 
	 }
	 	 
	 public function setDepreciation_Method($depreciation_method)
	 {
		 $this->depreciation_method = $depreciation_method;
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

	  public function getGoods_Depreciation_Quantity()
	 {
		return $this->goods_depreciation_quantity; 
	 }
	 	 
	 public function setGoods_Depreciation_Quantity($goods_depreciation_quantity)
	 {
		 $this->goods_depreciation_quantity = $goods_depreciation_quantity;
	 }

	 public function getDepreciated_Year()
	 {
		return $this->depreciated_year; 
	 }
	 	 
	 public function setDepreciated_Year($depreciated_year)
	 {
		 $this->depreciated_year = $depreciated_year;
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