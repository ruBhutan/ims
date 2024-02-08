<?php

namespace Alumni\Model;

class Alumni
{
	protected $id;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $cid;
	protected $gender;
	protected $date_of_birth;
	protected $programme_name;
	protected $enrollment_year;
	protected $graduation_year;
	protected $contact_no;
	protected $email_address;
	protected $present_address;
	protected $current_job_title;
	protected $current_job_organisation;
    protected $qualification_level_id;
    protected $qualification_field;
    protected $alumni_status;
    protected $subscription;
    protected $registration_date;
    protected $alumni_type;

	protected $event_name;
	protected $from_date;
	protected $to_date;
	protected $content;
	protected $student_id;
	protected $alumni_programmes_id;
	protected $organisation_id;
	protected $name;
	protected $batch;
	
	protected $description;
	protected $link;
	protected $alumni_remarks;
	
	protected $topic;
	protected $email_contents;
	protected $alumni_id;
	protected $enquiry_status;
	
	protected $faq_questions;
	protected $faq_answers;

	protected $created_date;

	//Subscription
	protected $subscription_details;
	protected $subscription_charge;
	protected $remarks;
	protected $subscription_type;
	protected $subscription_status;
	protected $application_date;
	protected $subscriber_details;

	protected $bank_name;
	protected $bank_account_no;
		 
	 	 
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

	 public function getGender()
	 {
		return $this->gender; 
	 }
	 	 
	 public function setGender($gender)
	 {
		 $this->gender = $gender;
	 }

	  public function getDate_Of_Birth()
	 {
		return $this->date_of_birth; 
	 }
	 	 
	 public function setDate_Of_Birth($date_of_birth)
	 {
		 $this->date_of_birth = $date_of_birth;
	 }

	  public function getEnrollment_Year()
	 {
		return $this->enrollment_year; 
	 }
	 	 
	 public function setEnrollment_Year($enrollment_year)
	 {
		 $this->enrollment_year = $enrollment_year;
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

	   public function getCurrent_Job_Title()
	 {
		 return $this->current_job_title;
	 }
	 
	 public function setCurrent_Job_Title($current_job_title)
	 {
		 $this->current_job_title = $current_job_title;
	 }

	 public function getCurrent_Job_Organisation()
	 {
		 return $this->current_job_organisation;
	 }
	 
	 public function setCurrent_Job_Organisation($current_job_organisation)
	 {
		 $this->current_job_organisation = $current_job_organisation;
	 }

	 public function getQualification_Level_Id()
	 {
		return $this->qualification_level_id; 
	 }
	 	 
	 public function setQualification_Level_Id($qualification_level_id)
	 {
		 $this->qualification_level_id = $qualification_level_id;
	 }

	 public function getQualification_Field()
	 {
		return $this->qualification_field; 
	 }
	 	 
	 public function setQualification_Field($qualification_field)
	 {
		 $this->qualification_field = $qualification_field;
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

	  public function getRegistration_Date()
	 {
		return $this->registration_date; 
	 }
	 	 
	 public function setRegistration_Date($registration_date)
	 {
		 $this->registration_date = $registration_date;
	 }

	 public function getAlumni_Type()
	 {
		return $this->alumni_type; 
	 }
	 	 
	 public function setAlumni_Type($alumni_type)
	 {
		 $this->alumni_type = $alumni_type;
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

	  public function getBatch()
	 {
		return $this->batch; 
	 }
	 	 
	 public function setBatch($batch)
	 {
		 $this->batch = $batch;
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

	 public function getCreated_Date()
	 {
		return $this->created_date; 
	 }
	 	 
	 public function setCreated_Date($created_date)
	 {
		 $this->created_date = $created_date;
	 }

	 public function getSubscription_Details()
	 {
		return $this->subscription_details; 
	 }
	 	 
	 public function setSubscription_Details($subscription_details)
	 {
		 $this->subscription_details = $subscription_details;
	 }

	 public function getSubscription_Charge()
	 {
		 return $this->subscription_charge;
	 }
	 
	 public function setSubscription_Charge($subscription_charge)
	 {
		 $this->subscription_charge = $subscription_charge;
	 }
	 
	 public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }

	 public function getSubscription_Type()
	 {
		return $this->subscription_type; 
	 }
	 	 
	 public function setSubscription_Type($subscription_type)
	 {
		 $this->subscription_type = $subscription_type;
	 }

	 public function getSubscription_Status()
	 {
		return $this->subscription_status; 
	 }
	 	 
	 public function setSubscription_Status($subscription_status)
	 {
		 $this->subscription_status = $subscription_status;
	 }

	 public function getApplication_Date()
	 {
		return $this->application_date; 
	 }
	 	 
	 public function setApplication_Date($application_date)
	 {
		 $this->application_date = $application_date;
	 }

	  public function getSubscriber_Details()
	 {
		return $this->subscriber_details; 
	 }
	 	 
	 public function setSubscriber_Details($subscriber_details)
	 {
		 $this->subscriber_details = $subscriber_details;
	 }

	 public function getBank_Name()
	 {
		return $this->bank_name; 
	 }
	 	 
	 public function setBank_Name($bank_name)
	 {
		 $this->bank_name = $bank_name;
	 }	

	public function getBank_Account_No()
	 {
		return $this->bank_account_no; 
	 }
	 	 
	 public function setBank_Account_No($bank_account_no)
	 {
		 $this->bank_account_no = $bank_account_no;
	 }	
	
}