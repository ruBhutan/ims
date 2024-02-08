<?php

namespace Notification\Model;

class Notification
{
	protected $id;
	protected $notification_type;
	protected $submitted_by;
	protected $submission_to;
	protected $submission_to_department;
	protected $notification_status;
	protected $notification_date;
	protected $view_status;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getNotification_Type()
	{
		return $this->notification_type;
	}
	
	public function setNotification_Type($notification_type)
	{
		$this->notification_type = $notification_type;
	}
	
	public function getSubmitted_By()
	{
		return $this->submitted_by;
	}
	
	public function setSubmitted_By($submitted_by)
	{
		$this->submitted_by = $submitted_by;
	}
	
	public function getSubmission_To()
	{
		return $this->submission_to;
	}
	
	public function setSubmission_To($submission_to)
	{
		$this->submission_to = $submission_to;
	}
	
	public function getSubmission_To_Department()
	{
		return $this->submission_to_department;
	}
	
	public function setSubmission_To_Department($submission_to_department)
	{
		$this->submission_to_department = $submission_to_department;
	}
	
	public function getNotification_Status()
	{
		return $this->notification_status;
	}
	
	public function setNotification_Status($notification_status)
	{
		$this->notification_status = $notification_status;
	}
	 
	public function getNotification_Date()
	{
		return $this->notification_date;
	}
	
	public function setNotification_Date($notification_date)
	{
		$this->notification_date = $notification_date;
	}
	 
	public function getView_Status()
	{
		return $this->view_status;
	}
	
	public function setView_Status($view_status)
	{
		$this->view_status = $view_status;
	}
	 
}