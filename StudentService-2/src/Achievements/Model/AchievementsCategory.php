<?php

namespace Achievements\Model;

class AchievementsCategory
{
	protected $id;
	protected $achievement_name;
	protected $remarks;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getAchievement_Name()
	{
		return $this->achievement_name;
	}
	
	public function setAchievement_Name($achievement_name)
	{
		$this->achievement_name = $achievement_name;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
	
}