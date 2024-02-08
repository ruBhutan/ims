<?php

namespace FinanceCodes\Model;

class BroadHeadName
{
	protected $id;
	protected $broad_head_name;
	protected $broad_head_code;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getBroad_Head_Name()
	{
		return $this->broad_head_name;
	}
	
	public function setBroad_Head_Name($broad_head_name)
	{
		$this->broad_head_name = $broad_head_name;
	}
	
	public function getBroad_Head_Code()
	{
		return $this->broad_head_code;
	}
	
	public function setBroad_Head_Code($broad_head_code)
	{
		$this->broad_head_code = $broad_head_code;
	}
}