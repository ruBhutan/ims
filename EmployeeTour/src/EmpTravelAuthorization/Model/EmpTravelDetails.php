<?php

namespace EmpTravelAuthorization\Model;

class EmpTravelDetails
{
	protected $id;
	protected $from_station;
	protected $from_date;
	protected $to_station;
	protected $to_date;
	protected $mode_of_travel;
	protected $halt_at;
	protected $purpose_of_tour;
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
	 
	 public function getPurpose_Of_Tour()
	 {
		 return $this->purpose_of_tour;
	 }
	 
	 public function setPurpose_Of_Tour($purpose_of_tour)
	 {
		 $this->purpose_of_tour = $purpose_of_tour;
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