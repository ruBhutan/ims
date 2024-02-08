<?php

namespace HrSettings\Model;

class ResearchCategory
{
	protected $id;
	protected $research_category;
	protected $description;
		 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getResearch_Category()
	 {
		 return $this->research_category;
	 }
	 
	 public function setResearch_Category($research_category)
	 {
		 $this->research_category = $research_category;
	 }
	 
	 public function getDescription()
	 {
		 return $this->description;
	 }
	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }
	 
}