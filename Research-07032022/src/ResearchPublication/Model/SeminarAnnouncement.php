<?php

namespace ResearchPublication\Model;

class SeminarAnnouncement
{
	protected $id;
	protected $seminar_title;
	protected $seminar_location;
	protected $seminar_country;
    protected $seminar_start_date;
    protected $seminar_end_date;
    protected $funding_agency;
    protected $organisation_id;
    protected $announced_by;
    protected $remarks;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getSeminar_Title()
	{
		return $this->seminar_title;
	}
	
	public function setSeminar_Title($seminar_title)
	{
		$this->seminar_title = $seminar_title;
	}
	
	public function getSeminar_Location()
	{
		return $this->seminar_location;
	}
	
	public function setSeminar_Location($seminar_location)
	{
		$this->seminar_location = $seminar_location;
	}
	
	public function getSeminar_Country()
	{
		return $this->seminar_country;
	}
	
	public function setSeminar_Country($seminar_country)
	{
		$this->seminar_country = $seminar_country;
    }
    
    public function getSeminar_Start_Date()
	{
		return $this->seminar_start_date;
	}
	
	public function setSeminar_Start_Date($seminar_start_date)
	{
		$this->seminar_start_date = $seminar_start_date;
    }
    
    public function getSeminar_End_Date()
	{
		return $this->seminar_end_date;
	}
	
	public function setSeminar_End_Date($seminar_end_date)
	{
		$this->seminar_end_date = $seminar_end_date;
    }
    
    public function getFunding_Agency()
	{
		return $this->funding_agency;
	}
	
	public function setFunding_Agency($funding_agency)
	{
		$this->funding_agency = $funding_agency;
    }

    public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
    
    public function getAnnounced_By()
	{
		return $this->announced_by;
	}
	
	public function setAnnounced_By($announced_by)
	{
		$this->announced_by = $announced_by;
    }
    
    public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}	
}