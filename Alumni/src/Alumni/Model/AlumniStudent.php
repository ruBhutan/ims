<?php

namespace Alumni\Model;

class AlumniStudent
{
	protected $id;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $cid;
	protected $date_of_birth;
	protected $std_no;
	protected $programme_name;
	//protected $alumni_programme_id;
	protected $graduation_year;
	protected $contact_no;
	protected $email_address;
	protected $present_address;
	protected $current_job;
    protected $qualification;
    protected $alumni_status;
    protected $subscription;

	protected $event_name;
	protected $from_date;
	protected $to_date;
	protected $content;

	protected $graduation_year_id;
	protected $student_id;
	protected $alumni_programmes_id;
	protected $organisation_id;
	protected $name;
	
	protected $description;
	protected $link;
	protected $alumni_remarks;
	
	protected $topic;
	protected $email_contents;
	protected $alumni_id;
	protected $enquiry_status;
	
	protected $faq_questions;
	protected $faq_answers;
		 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getName()
	 {
		return $this->name; 
	 }
	 	 
	 public function setName($name)
	 {
		 $this->name = $name;
	 }
	 
	 public function getFirst_Name()
	 {
		return $this->first_name; 
	 }
	 	 
	 public function setFirst_Name($first_name)
	 {
		 $this->first_name = $first_name;
	 }
	 
	 public function getMiddle_Name()
	 {
		 return $this->middle_name;
	 }
	 
	 public function setMiddle_Name($middle_name)
	 {
		 $this->middle_name = $middle_name;
	 }
	 	 
	 public function getLast_Name()
	 {
		return $this->last_name; 
	 }
	 	 
	 public function setLast_Name($last_name)
	 {
		 $this->last_name = $last_name;
	 }
	
	 public function getCid()
	 {
		return $this->cid; 
	 }
	 	 
	 public function setCid($cid)
	 {
		 $this->cid = $cid;
	 }

	  public function getDate_Of_Birth()
	 {
		return $this->date_of_birth; 
	 }
	 	 
	 public function setDate_Of_Birth($date_of_birth)
	 {
		 $this->date_of_birth = $date_of_birth;
	 }

	 public function getStd_No()
	 {
		return $this->std_no; 
	 }
	 	 
	 public function setStd_No($std_no)
	 {
		 $this->std_no = $std_no;
	 }
	 
	 public function getGraduation_Year()
	 {
		return $this->graduation_year; 
	 }
	 	 
	 public function setGraduation_Year($graduation_year)
	 {
		 $this->graduation_year = $graduation_year;
	 }
	
	 public function getContact_No()
	 {
		return $this->contact_no; 
	 }
	 	 
	 public function setContact_No($contact_no)
	 {
		 $this->contact_no = $contact_no;
	 }
	 
	 public function getEmail_Address()
	 {
		 return $this->email_address;
	 }
	 
	 public function setEmail_Address($email_address)
	 {
		 $this->email_address = $email_address;
	 }

	 public function getPresent_Address()
	 {
		 return $this->present_address;
	 }
	 
	 public function setPresent_Address($present_address)
	 {
		 $this->present_address = $present_address;
	 }

	  public function getCurrent_Job()
	 {
		 return $this->current_job;
	 }
	 
	 public function setCurrent_Job($current_job)
	 {
		 $this->current_job = $current_job;
	 }

	  public function getQualification()
	 {
		return $this->qualification; 
	 }
	 	 
	 public function setQualification($qualification)
	 {
		 $this->qualification = $qualification;
	 }

	  public function getAlumni_Status()
	 {
		return $this->alumni_status; 
	 }
	 	 
	 public function setAlumni_Status($alumni_status)
	 {
		 $this->alumni_status = $alumni_status;
	 }
	 
	  public function getSubscription()
	 {
		return $this->subscription; 
	 }
	 	 
	 public function setSubscription($subscription)
	 {
		 $this->subscription = $subscription;
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

	  public function getStudent_Id()
	 {
		return $this->student_id; 
	 }
	 	 
	 public function setStudent_Id($student_id)
	 {
		 $this->student_id = $student_id;
	 }

	  public function getAlumni_Programmes_Id()
	 {
		return $this->alumni_programmes_id; 
	 }
	 	 
	 public function setAlumni_Programmes_Id($alumni_programmes_id)
	 {
		 $this->alumni_programmes_id = $alumni_programmes_id;
	 }

	 public function getGraduation_Year_Id()
	 {
		return $this->graduation_year_id; 
	 }
	 	 
	 public function setGraduation_Year_Id($graduation_year_id)
	 {
		 $this->graduation_year_id = $graduation_year_id;
	 }
	 
	 public function getProgramme_Name()
	 {
		return $this->programme_name; 
	 }
	 	 
	 public function setProgramme_Name($programme_name)
	 {
		 $this->programme_name = $programme_name;
	 }

	  public function getOrganisation_Id()
	 {
		return $this->organisation_id; 
	 }
	 	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }
	 
	 public function getDescription()
	 {
		return $this->description; 
	 }
	 	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }

	 public function getLink()
	 {
		 return $this->link;
	 }
	 
	 public function setLink($link)
	 {
		 $this->link = $link;
	 }
	 
	 public function getAlumni_Remarks()
	 {
		 return $this->alumni_remarks;
	 }
	 
	 public function setAlumni_Remarks($alumni_remarks)
	 {
		 $this->alumni_remarks = $alumni_remarks;
	 }
	 
	 
	 public function getTopic()
	 {
		return $this->topic; 
	 }
	 	 
	 public function setTopic($topic)
	 {
		 $this->topic = $topic;
	 }
	 
	 public function getEmail_Contents()
	 {
		 return $this->email_contents;
	 }
	 
	 public function setEmail_Contents($email_contents)
	 {
		 $this->email_contents = $email_contents;
	 }
	 
	 public function getAlumni_Id()
	 {
		 return $this->alumni_id;
	 }
	 
	 public function setAlumni_Id($alumni_id)
	 {
		 $this->alumni_id = $alumni_id;
	 }
	 
	 public function getEnquiry_Status()
	 {
		return $this->enquiry_status; 
	 }
	 	 
	 public function setEnquiry_Status($enquiry_status)
	 {
		 $this->enquiry_status = $enquiry_status;
	 }
	 
	 public function getFaq_Questions()
	 {
		return $this->faq_questions; 
	 }
	 	 
	 public function setFaq_Questions($faq_questions)
	 {
		 $this->faq_questions = $faq_questions;
	 }

	 public function getFaq_Answers()
	 {
		return $this->faq_answers; 
	 }
	 	 
	 public function setFaq_Answers($faq_answers)
	 {
		 $this->faq_answers = $faq_answers;
	 }
	 
	 

	
}