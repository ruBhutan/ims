<?php

namespace PmsRatings\Model;

class FeedbackQuestions
{
	protected $id;
	protected $questions;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getQuestions()
	{
		return $this->questions;
	}
	
	public function setQuestions($questions)
	{
		$this->questions = $questions;
	}
	
}