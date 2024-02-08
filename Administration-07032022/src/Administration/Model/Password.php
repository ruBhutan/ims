<?php

namespace Administration\Model;

class Password
{
	protected $id;
	protected $password;
	protected $repeat_password;
	protected $user_type_id;
	
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

	public function getUser_Type_Id()
	{
		return $this->user_type_id;
	}
	
	public function setUser_Type_Id($user_type_id)
	{
		return $this->user_type_id = $user_type_id;
	}
}