<?php

namespace Planning\Model;

class SuccessIndicatorTrend
{
	protected $id;
	protected $year_one_projected;
	protected $year_one_achieved;
	protected $year_two_projected;
	protected $year_two_achieved;
	protected $year_three_projected;
	protected $year_three_achieved;
	protected $year_four_projected;
	protected $year_four_achieved;
	protected $year_five_projected;
	protected $year_five_achieved;
	protected $awpa_activities_id;
 	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 	
	public function getYear_One_Projected()
	{
		return $this->year_one_projected;
	}
	
	public function setYear_One_Projected($year_one_projected)
	{
		$this->year_one_projected = $year_one_projected;
	}
	
	public function getYear_Two_Projected()
	{
		return $this->year_two_projected;
	}
	
	public function setYear_Two_Projected($year_two_projected)
	{
		$this->year_two_projected = $year_two_projected;
	}
	
	public function getYear_Three_Projected()
	{
		return $this->year_three_projected;
	}
	
	public function setYear_Three_Projected($year_three_projected)
	{
		$this->year_three_projected = $year_three_projected;
	}
	
	public function getYear_Four_Projected()
	{
		return $this->year_four_projected;
	}
	
	public function setYear_Four_Projected($year_four_projected)
	{
		$this->year_four_projected = $year_four_projected;
	}
	
	public function getYear_Five_Projected()
	{
		return $this->year_five_projected;
	}
	
	public function setYear_Five_Projected($year_five_projected)
	{
		$this->year_five_projected = $year_five_projected;
	}
	
	public function getYear_One_Achieved()
	{
		return $this->year_one_achieved;
	}
	
	public function setYear_One_Achieved($year_one_achieved)
	{
		$this->year_one_achieved = $year_one_achieved;
	}
	
	public function getYear_Two_Achieved()
	{
		return $this->year_two_achieved;
	}
	
	public function setYear_Two_Achieved($year_two_achieved)
	{
		$this->year_two_achieved = $year_two_achieved;
	}
	
	public function getYear_Three_Achieved()
	{
		return $this->year_three_achieved;
	}
	
	public function setYear_Three_Achieved($year_three_achieved)
	{
		$this->year_three_achieved = $year_three_achieved;
	}
	
	public function getYear_Four_Achieved()
	{
		return $this->year_four_achieved;
	}
	
	public function setYear_Four_Achieved($year_four_achieved)
	{
		$this->year_four_achieved = $year_four_achieved;
	}
	
	public function getYear_Five_Achieved()
	{
		return $this->year_five_achieved;
	}
	
	public function setYear_Five_Achieved($year_five_achieved)
	{
		$this->year_five_achieved = $year_five_achieved;
	}
	
	public function getAwpa_Activities_Id()
	{
		return $this->awpa_activities_id;
	}
	
	public function setAwpa_Activities_Id($awpa_activities_id)
	{
		$this->awpa_activities_id = $awpa_activities_id;
	}
}