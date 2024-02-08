<?php

namespace JobPortal\Model;

class References
{
	protected $id;
	protected $name;
	protected $title;
	protected $position;
	protected $organisation;
	protected $relation_applicant;
	protected $telephone;
	protected $mobile;
	protected $email;
	protected $job_applicant_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function getPosition()
	{
		return $this->position;
	}
	
	public function setPosition($position)
	{
		$this->position = $position;
	}
	
	public function getOrganisation()
	{
		return $this->organisation;
	}
	
	public function setOrganisation($organisation)
	{
		$this->organisation = $organisation;
	}
	
	public function getRelation_Applicant()
	{
		return $this->relation_applicant;
	}
	
	public function setRelation_Applicant($relation_applicant)
	{
		$this->relation_applicant = $relation_applicant;
	}
	
	public function getTelephone()
	{
		return $this->telephone;
	}
	
	public function setTelephone($telephone)
	{
		$this->telephone = $telephone;
	}
	
	public function getMobile()
	{
		return $this->mobile;
	}
	
	public function setMobile($mobile)
	{
		$this->mobile = $mobile;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;
	}
	
	public function getJob_Applicant_Id()
	{
		return $this->job_applicant_id;
	}
	
	public function setJob_Applicant_Id($job_applicant_id)
	{
		$this->job_applicant_id = $job_applicant_id;
	}

}