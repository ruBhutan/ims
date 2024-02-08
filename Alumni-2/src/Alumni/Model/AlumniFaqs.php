<?php

namespace Alumni\Model;

class AlumniFaqs
{
	protected $id;
	protected $faq_questions;
	protected $faq_answers;
	protected $organisation_id;
	
		 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
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
	 	
	 public function getOrganisation_Id()
	 {
		return $this->organisation_id; 
	 }
	 	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }
	
}