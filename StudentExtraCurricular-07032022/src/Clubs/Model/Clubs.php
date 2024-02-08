<?php

namespace Clubs\Model;

class Clubs
{
	protected $id;
	protected $club_name;
	protected $maximum_members;
	protected $advisor_name;
	protected $coordinator_name;
	protected $description;
	protected $date;
	protected $student_id;
	protected $clubs_id;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getClub_Name()
	{
		return $this->club_name;
	}
	
	public function setClub_Name($club_name)
	{
		$this->club_name = $club_name;
	}
	
	public function getMaximum_Members()
	{
		return $this->maximum_members;
	}
	
	public function setMaximum_Members($maximum_members)
	{
		$this->maximum_members = $maximum_members;
	}
	
	public function getAdvisor_Name()
	{
		return $this->advisor_name;
	}
	
	public function setAdvisor_Name($advisor_name)
	{
		$this->advisor_name = $advisor_name;
	}
	
	public function getCoordinator_Name()
	{
		return $this->coordinator_name;
	}
	
	public function setCoordinator_Name($coordinator_name)
	{
		$this->coordinator_name = $coordinator_name;
	}
	
	public function getDate()
	{
		return $this->date;
	}
	
	public function setDate($date)
	{
		$this->date = $date;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	
	public function getClubs_Id()
	{
		return $this->clubs_id;
	}
	
	public function setClubs_Id($clubs_id)
	{
		$this->clubs_id = $clubs_id;
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