<?php

namespace StudentImage\Model;

class StudentProfilePicture
{
	protected $id;
	protected $profile_picture;
	protected $student_details_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getProfile_Picture()
	{
		return $this->profile_picture;
	}
	
	public function setProfile_Picture($profile_picture)
	{
		$this->profile_picture = $profile_picture;
	}
	
	public function getStudent_Details_Id()
	{
		return $this->student_details_id;
	}
	
	public function setStudent_Details_Id($student_details_id)
	{
		$this->student_details_id = $student_details_id;
	}
}