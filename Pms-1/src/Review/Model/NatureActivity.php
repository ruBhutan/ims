<?php

namespace Review\Model;

class NatureActivity
{
	protected $id;
	protected $nature_of_activity;
	protected $maximum_score;
	protected $pms_academic_weight_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getNature_Of_Activity()
	{
		return $this->nature_of_activity;
	}
	
	public function setNature_Of_Activity($nature_of_activity)
	{
		$this->nature_of_activity = $nature_of_activity;
	}
	
	public function getMaximum_Score()
	{
		return $this->maximum_score;
	}
	
	public function setMaximum_Score($maximum_score)
	{
		$this->maximum_score = $maximum_score;
	}
	
	public function getPms_Academic_Weight_Id()
	{
		return $this->pms_academic_weight_id;
	}
	
	public function setPms_Academic_Weight_Id($pms_academic_weight_id)
	{
		$this->pms_academic_weight_id = $pms_academic_weight_id;
	}
	
}