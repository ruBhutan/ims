<?php

namespace Alumni\Model;

class AlumniEvent
{
	protected $id;
	protected $batch;
	protected $event_name;
	protected $from_date;
	protected $to_date;
	protected $content;
	protected $alumni_programmes_id;
	protected $organisation_id;
	//protected $fk_tbl_alumni_id;
		 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	public function getBatch()
	 {
		return $this->batch; 
	 }
	 	 
	 public function setBatch($batch)
	 {
		 $this->batch = $batch;
	 }

	 public function getEvent_Name()
	 {
		 return $this->event_name;
	 }
	 
	 public function setEvent_Name($event_name)
	 {
		 $this->event_name = $event_name;
	 }
	 	 
	 public function getFrom_Date()
	 {
		return $this->from_date; 
	 }
	 	 
	 public function setFrom_Date($from_date)
	 {
		 $this->from_date = $from_date;
	 }
	 
	 public function getTo_Date()
	 {
		return $this->to_date; 
	 }
	 	 
	 public function setTo_Date($to_date)
	 {
		 $this->to_date = $to_date;
	 }

	 public function getContent()
	 {
		return $this->content; 
	 }
	 	 
	 public function setContent($content)
	 {
		 $this->content = $content;
	 }

	 public function getAlumni_Programmes_Id()
	 {
		return $this->alumni_programmes_id; 
	 }
	 	 
	 public function setAlumni_Programmes_Id($alumni_programmes_id)
	 {
		 $this->alumni_programmes_id = $alumni_programmes_id;
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