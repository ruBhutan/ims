<?php

namespace EmpTraining\Model;

class WorkshopDetails
{
	protected $id;
	protected $type;
	protected $hrd_type;
	protected $title;
	protected $institute_name;
	protected $institute_location;
	protected $institute_country;
	protected $workshop_start_date;
	protected $workshop_end_date;
	protected $source_of_funding;
	protected $proposing_agency;
	protected $remarks;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getHrd_Type()
	{
		return $this->hrd_type;
	}
	
	public function setHrd_Type($hrd_type)
	{
		$this->hrd_type = $hrd_type;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function getInstitute_Name()
	{
		return $this->institute_name;
	}
	
	public function setInstitute_Name($institute_name)
	{
		$this->institute_name = $institute_name;
	}
	
	public function getInstitute_Location()
	{
		return $this->institute_location;
	}
	
	public function setInstitute_Location($institute_location)
	{
		$this->institute_location = $institute_location;
	}
	
	public function getInstitute_Country()
	{
		return $this->institute_country;
	}
	
	public function setInstitute_Country($institute_country)
	{
		$this->institute_country = $institute_country;
	}
		
	public function getWorkshop_Start_Date()
	{
		return $this->workshop_start_date;
	}
	
	public function setWorkshop_Start_Date($workshop_start_date)
	{
		$this->workshop_start_date = $workshop_start_date;
	}
	
	public function getWorkshop_End_Date()
	{
		return $this->workshop_end_date;
	}
	
	public function setWorkshop_End_Date($workshop_end_date)
	{
		$this->workshop_end_date = $workshop_end_date;
	}
		
	public function getSource_Of_Funding()
	{
		return $this->source_of_funding;
	}
	
	public function setSource_Of_Funding($source_of_funding)
	{
		$this->source_of_funding = $source_of_funding;
	}
	
	public function getProposing_Agency()
	{
		return $this->proposing_agency;
	}
	
	public function setProposing_Agency($proposing_agency)
	{
		$this->proposing_agency = $proposing_agency;
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