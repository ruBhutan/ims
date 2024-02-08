<?php

namespace Job\Model;

class PayScale
{
	protected $id;
	protected $minimum_pay_scale;
	protected $increment;
	protected $maximum_pay_scale;
	protected $position_level;
        protected $status;
	 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getMinimum_Pay_Scale()
	 {
		 return $this->minimum_pay_scale;
	 }
	 
	 public function setMinimum_Pay_Scale($minimum_pay_scale)
	 {
		 $this->minimum_pay_scale = $minimum_pay_scale;
	 }
	 
	 public function getIncrement()
	 {
		 return $this->increment;
	 }
	 
	 public function setIncrement($increment)
	 {
		$this->increment = $increment; 
	 }
	 
	 public function getMaximum_Pay_Scale()
	 {
		 return $this->maximum_pay_scale;
	 }
	 
	 public function setMaximum_Pay_Scale($maximum_pay_scale)
	 {
		 $this->maximum_pay_scale = $maximum_pay_scale;
	 }
	 
	 public function getPosition_Level()
	 {
		 return $this->position_level;
	 }
	 
	 public function setPosition_Level($position_level)
	 {
		 $this->position_level = $position_level;
	 }
         
         public function getStatus()
         {
                return $this->status;
         }
         
         public function setStatus($status)
         {
                $this->status = $status;
         }

}