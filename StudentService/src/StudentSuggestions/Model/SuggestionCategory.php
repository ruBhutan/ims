<?php

namespace StudentSuggestions\Model;

class SuggestionCategory
{
	protected $id;
	protected $suggestion_category;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getSuggestion_Category()
	{
		return $this->suggestion_category;
	}
	
	public function setSuggestion_Category($suggestion_category)
	{
		$this->suggestion_category = $suggestion_category;
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