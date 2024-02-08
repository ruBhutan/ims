<?php

namespace UniversityAdministration\Model;

class NewsPaper
{
	protected $id;
	protected $newspaper_type;
	protected $newspaper_date;
	protected $english_newspaper;
	protected $dzongkha_newspaper;
	protected $recorded_by;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 	
	public function getNewsPaper_Type()
	{
		return $this->newspaper_type;
	}
	
	public function setNewsPaper_Type($newspaper_type)
	{
		$this->newspaper_type = $newspaper_type;
	}
	
	public function getNewspaper_date()
	{
		return $this->newspaper_date;
	}
	
	public function setNewspaper_date($newspaper_date)
	{
		$this->newspaper_date = $newspaper_date;
	}
	   
    public function getenglish_newspaper()
    {
            return $this->english_newspaper;
    }
    
    public function setenglish_newspaper($english_newspaper)
    {
            $this->english_newspaper = $english_newspaper;
    }

    public function getdzongkha_newspaper()
    {
            return $this->dzongkha_newspaper;
    }
    
    public function setdzongkha_newspaper($dzongkha_newspaper)
    {
            $this->dzongkha_newspaper = $dzongkha_newspaper;
    }
	
	public function getRecorded_By()
	{
		return $this->recorded_by;
	}
	
	public function setRecorded_By($recorded_by)
	{
		$this->recorded_by = $recorded_by;
	}
}