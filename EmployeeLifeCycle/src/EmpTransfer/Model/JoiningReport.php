<?php

namespace EmpTransfer\Model;

class JoiningReport
{
	protected $id;
	protected $joining_report;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getJoining_Report()
	{
		return $this->joining_report;
	}
	
	public function setJoining_Report($joining_report)
	{
		$this->joining_report = $joining_report;
	}
}