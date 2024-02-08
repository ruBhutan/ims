<?php

namespace JobPortal\Model;

class LanguageSkills
{
	protected $id;
	protected $language;
	protected $spoken;
	protected $reading;
	protected $writing;
	protected $job_applicant_id;
	 	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		 $this->id = $id;
	}
	
	public function getLanguage()
	{
		return $this->language;
	}
	
	public function setLanguage($language)
	{
		$this->language = $language;
	}
	
	public function getSpoken()
	{
		return $this->spoken;
	}
	
	public function setSpoken($spoken)
	{
		$this->spoken = $spoken;
	}
	
	public function getReading()
	{
		return $this->reading;
	}
	
	public function setReading($reading)
	{
		$this->reading = $reading;
	}
	
	public function getWriting()
	{
		return $this->writing;
	}
	
	public function setWriting($writing)
	{
		$this->writing = $writing;
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