<?php

namespace Review\Model;

class AcademicWeight
{
	protected $id;
	protected $category;
	protected $weight;
	protected $remarks;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getCategory()
	{
		return $this->category;
	}
	
	public function setCategory($category)
	{
		$this->category = $category;
	}
	
	public function getWeight()
	{
		return $this->weight;
	}
	
	public function setWeight($weight)
	{
		$this->weight = $weight;
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