<?php
//RUB Activities
namespace Planning\Model;

class Activities
{
	protected $id;
	protected $activity_name;
	protected $budget_overlay;
        protected $funding;
	protected $rub_objectives_id;
 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getActivity_Name()
         {
             return $this->activity_name;
         }
         
         public function setActivity_Name($activity_name)
         {
             $this->activity_name = $activity_name;
         }
         
         public function getBudget_Overlay()
         {
             return $this->budget_overlay;
         }
         
         public function setBudget_Overlay($budget_overlay)
         {
             $this->budget_overlay = $budget_overlay;
         }
         
         public function getFunding()
         {
             return $this->funding;
         }
         
         public function setFunding($funding)
         {
             $this->funding = $funding;
         }
         
         public function getRub_Objectives_Id()
         {
             return $this->rub_objectives_id;
         }
         
         public function setRub_Objectives_Id($rub_objectives_id)
         {
             $this->rub_objectives_id = $rub_objectives_id;
         }
}
