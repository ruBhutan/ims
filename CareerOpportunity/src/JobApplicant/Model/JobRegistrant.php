<?php

namespace JobApplicant\Model;

class JobRegistrant
{
	protected $id;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $email;
	protected $cid;
	protected $gender;
	
	
	public function getId()
	{
		return $this->id;
		
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}

	public function getFirst_name()
	{
		return $this->first_name;
	}
	
	public function setFirst_name($first_name)
	{
		$this->first_name = $first_name;
	}

	public function getMiddle_name()
	{
		return $this->middle_name;
	}
	
	public function setMiddle_name($middle_name)
	{
		$this->middle_name = $middle_name;
	}

	public function getLast_name()
	{
		return $this->last_name;
	}
	
	public function setLast_name($last_name)
	{
		$this->last_name = $last_name;
	}

	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getCid()
	{
		return $this->cid;
	}
	
	public function setCid($cid)
	{
		$this->cid = $cid;
	}
}