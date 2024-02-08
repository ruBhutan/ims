<?php

namespace Discipline\Model;

class DisciplineCategory
{
	protected $id;
	protected $discipline_category;
	protected $description;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 		
	public function getDiscipline_Category()
	{
		return $this->discipline_category;
	}
	
	public function setDiscipline_Category($discipline_category)
	{
		$this->discipline_category = $discipline_category;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}

}