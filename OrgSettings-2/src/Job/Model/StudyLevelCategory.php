<?php

namespace Job\Model;

class StudyLevelCategory
{
	protected $id;
	protected $study_level;
	protected $remarks;
		 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getStudy_Level()
	 {
		 return $this->study_level;
	 }
	 
	 public function setStudy_Level($study_level)
	 {
		 $this->study_level = $study_level;
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