<?php

namespace FinanceCodes\Model;

class ObjectCode
{
	protected $id;
	protected $object_name;
	protected $object_code;
	protected $broad_head_name_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getObject_Name()
	{
		return $this->object_name;
	}
	
	public function setObject_Name($object_name)
	{
		$this->object_name = $object_name;
	}
	
	public function getObject_Code()
	{
		return $this->object_code;
	}
	
	public function setObject_Code($object_code)
	{
		$this->object_code = $object_code;
	}
	
	public function getBroad_Head_Name_Id()
	{
		return $this->broad_head_name_id;
	}
	
	public function setBroad_Head_Name_Id($broad_head_name_id)
	{
		$this->broad_head_name_id = $broad_head_name_id;
	}
}