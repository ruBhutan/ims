<?php

namespace EmployeeDetail\Model;

class EmployeePayDetails
{
	protected $id;
	protected $pay_scale_id;
	protected $basic_pay;
	protected $increment;
	protected $university_allowance;
	protected $professional_allowance;
	protected $house_rent_allowance;
	protected $communication_allowance;
	protected $kabney_allowance;
	protected $teaching_allowance;
	protected $fixed_term_allowance;
	protected $vice_chancellor_allowance;
	protected $dean_allowance;
	protected $patang_allowance;
	protected $employee_details_id;
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getPay_Scale_Id()
	 {
		return $this->pay_scale_id; 
	 }
	 	 
	 public function setPay_Scale_Id($pay_scale_id)
	 {
		 $this->pay_scale_id = $pay_scale_id;
	 }
	 	 
	 public function getBasic_Pay()
	 {
		return $this->basic_pay; 
	 }
	 	 
	 public function setBasic_Pay($basic_pay)
	 {
		 $this->basic_pay=$basic_pay;
	 }
	 
	 public function getIncrement()
	 {
		return $this->increment; 
	 }
	 	 
	 public function setIncrement($increment)
	 {
		 $this->increment=$increment;
	 }

	 public function getUniversity_Allowance()
	 {
		return $this->university_allowance; 
	 }
	 	 
	 public function setUniversity_Allowance($university_allowance)
	 {
		 $this->university_allowance=$university_allowance;
	 }
	 
	 public function getProfessional_Allowance()
	 {
		return $this->professional_allowance; 
	 }
	 	 
	 public function setProfessional_Allowance($professional_allowance)
	 {
		 $this->professional_allowance=$professional_allowance;
	 }
	 
	 public function getHouse_Rent_Allowance()
	 {
		return $this->house_rent_allowance; 
	 }
	 	 
	 public function setHouse_Rent_Allowance($house_rent_allowance)
	 {
		 $this->house_rent_allowance=$house_rent_allowance;
	 }
	 
	 public function getCommunication_Allowance()
	 {
		 return $this->communication_allowance;
	 }
	 
	 public function setCommunication_Allowance($communication_allowance)
	 {
		 $this->communication_allowance = $communication_allowance;
	 }
	 
	 public function getKabney_Allowance()
	 {
		return $this->kabney_allowance; 
	 }
	 	 
	 public function setKabney_Allowance($kabney_allowance)
	 {
		 $this->kabney_allowance=$kabney_allowance;
	 }
	 
	 public function getTeaching_Allowance()
	 {
		 return $this->teaching_allowance;
	 }
	 
	 public function setTeaching_Allowance($teaching_allowance)
	 {
		 $this->teaching_allowance = $teaching_allowance;
	 }
	 
	 public function getFixed_Term_Allowance()
	 {
		 return $this->fixed_term_allowance;
	 }
	 
	 public function setFixed_Term_Allowance($fixed_term_allowance)
	 {
		 $this->fixed_term_allowance = $fixed_term_allowance;
	 }

	 public function getVice_Chancellor_Allowance()
	 {
		 return $this->vice_chancellor_allowance;
	 }
	 
	 public function setVice_Chancellor_Allowance($vice_chancellor_allowance)
	 {
		 $this->vice_chancellor_allowance = $vice_chancellor_allowance;
	 }


	 public function getDean_Allowance()
	 {
		 return $this->dean_allowance;
	 }
	 
	 public function setDean_Allowance($dean_allowance)
	 {
		 $this->dean_allowance = $dean_allowance;
	 }

	 public function getPatang_Allowance()
	 {
		 return $this->patang_allowance;
	 }
	 
	 public function setPatang_Allowance($patang_allowance)
	 {
		 $this->patang_allowance = $patang_allowance;
	 }

	 public function getEmployee_Details_Id()
	 {
		 return $this->employee_details_id;
	 }
	 
	 public function setEmployee_Details_Id($employee_details_id)
	 {
		 $this->employee_details_id = $employee_details_id;
	 }
}