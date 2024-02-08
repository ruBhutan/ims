<?php

namespace Administration\Model;

class Password
{
	protected $id;
	protected $password;
	protected $repeat_password;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getPassword()
	{
		return $this->password;
	}
	
	public function setPassword($password)
	{
		$this->password = $password;
	}
	
	public function getRepeat_Password()
	{
		return $this->repeat_password;
	}
	
	public function setRepeat_Password($repeat_password)
	{
		return $this->repeat_password = $repeat_password;
	}
}