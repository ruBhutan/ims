<?php

namespace Planning\Model;

class SuccessIndicatorDefinition
{
	protected $id;
	protected $description;
	protected $data_collection_methodology;
	protected $data_collection;
	protected $data_source;
	protected $awpa_activities_id;
	
 	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getData_Collection_Methodology()
	{
		return $this->data_collection_methodology;
	}
	
	public function setData_Collection_Methodology($data_collection_methodology)
	{
		$this->data_collection_methodology = $data_collection_methodology;
	}
	
	public function getData_Collection()
	{
		return $this->data_collection;
	}
	
	public function setData_Collection($data_collection)
	{
		$this->data_collection = $data_collection;
	}
	
	public function getData_Source()
	{
		return $this->data_source;
	}
	
	public function setData_Source($data_source)
	{
		$this->data_source = $data_source;
	}
	
	public function getAwpa_Activities_Id()
	{
		return $this->awpa_activities_id;
	}
	
	public function setAwpa_Activities_Id($awpa_activities_id)
	{
		$this->awpa_activities_id = $awpa_activities_id;
	}
}