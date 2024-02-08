<?php

namespace AuditTrail\Model;

class AuditTrail
{
	protected $id;
	protected $user_name;
	protected $ipAddress;
	protected $date;
	protected $table;
	protected $column;
	protected $action;
	protected $values;
	protected $status;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getUser_Name()
	{
		return $this->user_name;
	}
	
	public function setUser_Name($user_name)
	{
		$this->user_name = $user_name;
	}
	
	public function getIpAddress()
	{
		return $this->ipAddress;
	}
	
	public function setIpAddress($ipAddress)
	{
		$this->ipAddress = $ipAddress;
	}
	
	public function getDate()
	{
		return $this->date;
	}
	
	public function setDate($date)
	{
		$this->date = $date;
	}
	
	public function getTable()
	{
		return $this->table;
	}
	
	public function setTable($table)
	{
		$this->table = $table;
	}
	 
	public function getColumn()
	{
		return $this->column;
	}
	
	public function setColumn($column)
	{
		$this->column = $column;
	}
	 
	public function getAction()
	{
		return $this->action;
	}
	
	public function setAction($action)
	{
		$this->action = $action;
	}
        
        public function getValues()
	{
		return $this->values;
	}
	
	public function setValues($values)
	{
		$this->values = $values;
	}

        public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
        
	 
}