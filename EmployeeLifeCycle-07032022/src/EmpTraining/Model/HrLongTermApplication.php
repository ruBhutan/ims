<?php

namespace EmpTraining\Model;

class HrLongTermApplication
{
	protected $id;
	protected $award_letter;
	protected $understanding_letter;
	protected $departure_intimidation_form;
	protected $predeparture_briefing_form;
	protected $understanding_secondment;
	 	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getAward_Letter()
	{
		return $this->award_letter;
	}
	
	public function setAward_Letter($award_letter)
	{
		$this->award_letter = $award_letter;
	}
	
	public function getUnderstanding_Letter()
	{
		return $this->understanding_letter;
	}
	
	public function setUnderstanding_Letter($understanding_letter)
	{
		$this->understanding_letter = $understanding_letter;
	}
	
	public function getDeparture_Intimidation_Form()
	{
		return $this->departure_intimidation_form;
	}
	
	public function setDeparture_Intimidation_Form($departure_intimidation_form)
	{
		$this->departure_intimidation_form = $departure_intimidation_form;
	}
	
	public function getPredeparture_Briefing_Form()
	{
		return $this->predeparture_briefing_form;
	}
	
	public function setPredeparture_Briefing_Form($predeparture_briefing_form)
	{
		$this->predeparture_briefing_form = $predeparture_briefing_form;
	}
	
	public function getUnderstanding_Secondment()
	{
		return $this->understanding_letter;
	}
	
	public function setUnderstanding_Secondment($understanding_letter)
	{
		$this->understanding_letter = $understanding_letter;
	}
}