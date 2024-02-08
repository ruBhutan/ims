<?php

namespace GoodsTransaction\Model;

class ItemCategory
{
	protected $id;
	protected $category_type;
	protected $category_code;
	protected $description;
	protected $major_class_id;
	
	 	 
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

	 public function getCategory_Code()
	 {
		return $this->category_code; 
	 }
	 	 
	 public function setCategory_Code($category_code)
	 {
		 $this->category_code = $category_code;
	 }
	 
	 public function getDescription()
	 {
		 return $this->description;
	 }
	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }

	 public function getMajor_Class_Id()
	 {
		 return $this->major_class_id;
	 }
	 
	 public function setMajor_Class_Id($major_class_id)
	 {
		 $this->major_class_id = $major_class_id;
	 }

}