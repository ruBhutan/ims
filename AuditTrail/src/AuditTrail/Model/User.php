<?php

namespace AuditTrail\Model;

class User
{
	protected $id;
	protected $username;
	protected $last_login;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getUsername()
	{
		return $this->username;
	}
	
	public function setUsername($username)
	{
		$this->username = $username;
	}
	
	public function getLast_Login()
	{
		return $this->last_login;
	}
	
	public function setLast_Login($last_login)
	{
		$this->last_login = $last_login;
	}      
	 
}