<?php

namespace Job\Model;

class PositionTitle
{
	protected $id;
	protected $position_title;
	protected $description;
	protected $notes;
	protected $position_category_id;
		 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getPosition_Title()
	 {
		return $this->position_title; 
	 }
	 	 
	 public function setPosition_Title($position_title)
	 {
		 $this->position_title = $position_title;
	 }
	 
	 public function getDescription()
	 {
		 return $this->description;
	 }
	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }
	 	 
	 public function getNotes()
	 {
		return $this->notes; 
	 }
	 	 
	 public function setNotes($notes)
	 {
		 $this->notes = $notes;
	 }
	 
	 public function getPosition_Category_Id()
	 {
		 return $this->position_category_id;
	 }
	 
	 public function setPosition_Category_Id($position_category_id)
	 {
		 $this->position_category_id = $position_category_id;
	 }
	 
}