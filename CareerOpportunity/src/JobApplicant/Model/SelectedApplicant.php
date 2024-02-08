<?php

namespace Vacancy\Model;

class SelectedApplicant
{
	protected $id;
	protected $emp_job_applications_id;
        protected $appointment_order_no;
        protected $date_of_appointment;
        protected $appointment_order_file;
        protected $cid;
        protected $nationality;
        protected $date_of_birth;
        protected $working_agency;
        protected $position_title;
        protected $position_level;
        protected $position_category;
        protected $pay_scale;
        
	
	public function getId()
	{
            return $this->id;
	}
	 
	public function setId($id)
	{
            $this->id = $id;
	}
	
        public function getEmp_Job_Applications_Id()
        {
            return $this->emp_job_applications_id;
        }
        
        public function setEmp_Job_Applications_Id($emp_job_applications_id)
        {
            $this->emp_job_applications_id = $emp_job_applications_id;
        }
        
        public function getAppointment_Order_No()
        {
            return $this->appointment_order_no;
        }
        
        public function setAppointment_Order_No($appointment_order_no)
        {
            $this->appointment_order_no = $appointment_order_no;
        }
        
        public function getDate_Of_Appointment()
        {
            return $this->date_of_appointment;
        }
        
        public function setDate_Of_Appointment($date_of_appointment)
        {
            $this->date_of_appointment = $date_of_appointment;
        }
        
        public function getAppointment_Order_File()
        {
            return $this->appointment_order_file;
        }
        
        public function setAppointment_Order_File($appointment_order_file)
        {
            $this->appointment_order_file = $appointment_order_file;
        }
        
        public function getCid()
        {
            return $this->cid;
        }
        
        public function setCid($cid)
        {
            $this->cid = $cid;
        }
        
        public function getNationality()
        {
            return $this->nationality;
        }
        
        public function setNationality($nationality)
        {
            $this->nationality = $nationality;
        }
        
        public function getDate_Of_Birth()
        {
            return $this->date_of_birth;
        }
        
        public function setDate_Of_Birth($date_of_birth)
        {
            $this->date_of_birth = $date_of_birth;
        }
        
        public function getWorking_Agency()
        {
            return $this->working_agency;
        }
        
        public function setWorking_Agency($working_agency)
        {
            $this->working_agency = $working_agency;
        }
        
        public function getPosition_Title()
        {
            return $this->position_title;
        }
        
        public function setPosition_Title($position_title)
        {
            $this->position_title = $position_title;
        }
        
        public function getPosition_Level()
        {
            return $this->position_level;
        }
        
        public function setPosition_Level($position_level)
        {
            $this->position_level = $position_level;
        }
        
        public function getPosition_Category()
        {
            return $this->position_category;
        }
        
        public function setPosition_Category($position_category)
        {
            $this->position_category = $position_category;
        }
        
        public function getPay_Scale()
        {
            return $this->pay_scale;
        }
	
        public function setPay_Scale($pay_scale)
        {
            $this->pay_scale = $pay_scale;
        }
}