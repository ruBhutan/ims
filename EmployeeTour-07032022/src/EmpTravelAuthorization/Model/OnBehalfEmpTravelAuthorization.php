<?php

namespace EmpTravelAuthorization\Model;

class OnBehalfEmpTravelAuthorization
{
	protected $id;
	protected $travel_auth_no;
	protected $travel_auth_date;
	protected $no_of_days;
	protected $start_date;
	protected $end_date;
	protected $estimated_expenses;
	protected $advance_required;
	protected $advance_sanctioned;
	protected $tour_status;
    protected $officiating_staff;
    protected $applied_by_id;
	protected $remarks;
	protected $authorizing_officer;
	protected $purpose_of_tour;
	protected $related_document_file;
	protected $organisation_id;
	protected $employee_details_id;
	protected $emptraveldetails;
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	 public function getTravel_Auth_No()
	 {
		 return $this->travel_auth_no;
	 }
	 
	 public function setTravel_Auth_No($travel_auth_no)
	 {
		 $this->travel_auth_no = $travel_auth_no;
	 }
	 
	 public function getTravel_Auth_Date()
	 {
		 return $this->travel_auth_date;
	 }
	 
	 public function setTravel_Auth_Date($travel_auth_date)
	 {
		 $this->travel_auth_date = $travel_auth_date;
	 }
	 	 
	 public function getNo_Of_Days()
	 {
		return $this->no_of_days; 
	 }
	 	 
	 public function setNo_Of_Days($no_of_days)
	 {
		 $this->no_of_days=$no_of_days;
	 }

	 public function getStart_Date()
	 {
		return $this->start_date; 
	 }
	 	 
	 public function setStart_Date($start_date)
	 {
		 $this->start_date=$start_date;
	 }

	 public function getEnd_Date()
	 {
		return $this->end_date; 
	 }
	 	 
	 public function setEnd_Date($end_date)
	 {
		 $this->end_date=$end_date;
	 }
	 
	 public function getEstimated_Expenses()
	 {
		return $this->estimated_expenses; 
	 }
	 	 
	 public function setEstimated_Expenses($estimated_expenses)
	 {
		 $this->estimated_expenses=$estimated_expenses;
	 }
	 
	 public function getAdvance_Required()
	 {
		return $this->advance_required; 
	 }
	 	 
	 public function setAdvance_Required($advance_required)
	 {
		 $this->advance_required = $advance_required;
	 }
	 
	 public function getAdvance_Sanctioned()
	 {
		 return $this->advance_sanctioned;
	 }
	 
	 public function setAdvance_Sanctioned($advance_sanctioned)
	 {
		 $this->advance_sanctioned = $advance_sanctioned;
	 }
	 
	 public function getTour_Status()
	 {
		return $this->tour_status; 
	 }
	 	 
	 public function setTour_Status($tour_status)
	 {
		 $this->tour_status=$tour_status;
	 }


	  public function getOfficiating_Staff()
	 {
		return $this->officiating_staff; 
	 }
	 	 
	 public function setOfficiating_Staff($officiating_staff)
	 {
		 $this->officiating_staff=$officiating_staff;
     }
     

     public function getApplied_By_Id()
	{
		return $this->applied_by_id;
	}
	
	public function setApplied_By_Id($applied_by_id)
	{
		$this->applied_by_id = $applied_by_id;
	}

	 
	 public function getRemarks()
	 {
		 return $this->remarks;
	 }
	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }
	 
	 public function getAuthorizing_Officer()
	 {
		 return $this->authorizing_officer;
	 }
	 
	 public function setAuthorizing_Officer($authorizing_officer)
	 {
		 $this->authorizing_officer = $authorizing_officer;
	 }
	 
	 public function getPurpose_Of_Tour()
	 {
		 return $this->purpose_of_tour;
	 }
	 
	 public function setPurpose_Of_Tour($purpose_of_tour)
	 {
		 $this->purpose_of_tour = $purpose_of_tour;
	 }

	 public function getRelated_Document_File()
	 {
		 return $this->related_document_file;
	 }
	 
	 public function setRelated_Document_File($related_document_file)
	 {
		 $this->related_document_file = $related_document_file;
	 }
         
	 public function getEmployee_Details_Id()
	 {
		 return $this->employee_details_id;
	 }
	 
	 public function setEmployee_Details_Id($employee_details_id)
	 {
		 $this->employee_details_id = $employee_details_id;
	 }
	 
	 public function getOrganisation_Id()
	 {
		 return $this->organisation_id;
	 }
	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }
	 
	 public function getEmptraveldetails()
	 {
		 return $this->emptraveldetails;
	 }
	 
	 public function setEmptraveldetails(array $emptraveldetails)
	 {
		 $this->emptraveldetails = $emptraveldetails;
		 return $this;
	 }
}

class TravelDetails
{
	protected $id;
	protected $from_station;
	protected $from_date;
	protected $to_station;
	protected $to_date;
	protected $mode_of_travel;
	protected $halt_at;
	protected $travel_authorization_id;
	
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getFrom_Station()
	 {
		 return $this->from_station;
	 }
	 
	 public function setFrom_Station($from_station)
	 {
		 $this->from_station = $from_station;
	 }
	 
	 public function getFrom_Date()
	 {
		 return $this->from_date;
	 }
	 
	 public function setFrom_Date($from_date)
	 {
		 $this->from_date = $from_date;
	 }
	 
	 public function getTo_Station()
	 {
		 return $this->to_station;
	 }
	 
	 public function setTo_Station($to_station)
	 {
		 $this->to_station = $to_station;
	 }
	 
	 public function getTo_Date()
	 {
		 return $this->to_date;
	 }
	 
	 public function setTo_Date($to_date)
	 {
		 $this->to_date = $to_date;
	 }
	 
	 public function getMode_Of_Travel()
	 {
		 return $this->mode_of_travel;
	 }
	 
	 public function setMode_Of_Travel($mode_of_travel)
	 {
		 $this->mode_of_travel = $mode_of_travel;
	 }
	 
	 public function getHalt_At()
	 {
		 return $this->halt_at;
	 }
	 
	 public function setHalt_At($halt_at)
	 {
		 $this->halt_at = $halt_at;
	 }
	 
	 public function getTravel_Authorization_Id()
	 {
		 return $this->travel_authorization_id;
	 }
	 
	 public function setTravel_Authorization_Id($travel_authorization_id)
	 {
		 $this->travel_authorization_id = $travel_authorization_id;
	 }
}