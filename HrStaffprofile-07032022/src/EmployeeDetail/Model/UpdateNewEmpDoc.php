<?php
//This model is to handle new employees. Will not be used for anything else.

namespace EmployeeDetail\Model;

class UpdateNewEmpDoc
{
	protected $id;
	protected $announcement_doc;
	protected $shortlist_doc;
	protected $selection_doc;
	protected $minutes_doc;
	protected $emp_application_form_doc;
	protected $emp_academic_transcript_doc;
	protected $emp_training_doc;
	protected $emp_cid_wp_doc;
	protected $emp_security_cl_doc;
	protected $emp_medical_doc;
	protected $emp_no_objec_doc;
	protected $appointment_order_doc;
	protected $others_doc;
	protected $new_employee_details_id;
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getAnnouncement_Doc()
	 {
		 return $this->announcement_doc;
	 }
	 
	 public function setAnnouncement_Doc($announcement_doc)
	 {
		 $this->announcement_doc = $announcement_doc;
	 }

	 public function getShortlist_Doc()
	 {
		 return $this->shortlist_doc;
	 }
	 
	 public function setShortlist_Doc($shortlist_doc)
	 {
		 $this->shortlist_doc = $shortlist_doc;
	 }

	 public function getSelection_Doc()
	 {
		 return $this->selection_doc;
	 }
	 
	 public function setSelection_Doc($selection_doc)
	 {
		 $this->selection_doc = $selection_doc;
	 }


	 public function getMinutes_Doc()
	 {
		 return $this->minutes_doc;
	 }
	 
	 public function setMinutes_Doc($minutes_doc)
	 {
		 $this->minutes_doc = $minutes_doc;
	 }

	 public function getEmp_Application_Form_Doc()
	 {
		 return $this->emp_application_form_doc;
	 }
	 
	 public function setEmp_Application_Form_Doc($emp_application_form_doc)
	 {
		 $this->emp_application_form_doc = $emp_application_form_doc;
	 }

	 public function getEmp_Academic_Transcript_Doc()
	 {
		 return $this->emp_academic_transcript_doc;
	 }
	 
	 public function setEmp_Academic_Transcript_Doc($emp_academic_transcript_doc)
	 {
		 $this->emp_academic_transcript_doc = $emp_academic_transcript_doc;
	 }

	 public function getEmp_Training_Doc()
	 {
		 return $this->emp_training_doc;
	 }
	 
	 public function setEmp_Training_Doc($emp_training_doc)
	 {
		 $this->emp_training_doc = $emp_training_doc;
	 }

	 public function getEmp_Cid_Wp_Doc()
	 {
		 return $this->emp_cid_wp_doc;
	 }
	 
	 public function setEmp_Cid_Wp_Doc($emp_cid_wp_doc)
	 {
		 $this->emp_cid_wp_doc = $emp_cid_wp_doc;
	 }

	 public function getEmp_Security_Cl_Doc()
	 {
		 return $this->emp_security_cl_doc;
	 }
	 
	 public function setEmp_Security_Cl_Doc($emp_security_cl_doc)
	 {
		 $this->emp_security_cl_doc = $emp_security_cl_doc;
	 }

	 public function getEmp_Medical_Doc()
	 {
		 return $this->emp_medical_doc;
	 }
	 
	 public function setEmp_Medical_Doc($emp_medical_doc)
	 {
		 $this->emp_medical_doc = $emp_medical_doc;
	 }

	 public function getEmp_No_Objec_Doc()
	 {
		 return $this->emp_no_objec_doc;
	 }
	 
	 public function setEmp_No_Objec_Doc($emp_no_objec_doc)
	 {
		 $this->emp_no_objec_doc = $emp_no_objec_doc;
	 }

	 public function getAppointment_Order_Doc()
	 {
		 return $this->appointment_order_doc;
	 }
	 
	 public function setAppointment_Order_Doc($appointment_order_doc)
	 {
		 $this->appointment_order_doc = $appointment_order_doc;
	 }

	 public function getOthers_Doc()
	 {
		 return $this->others_doc;
	 }
	 
	 public function setOthers_Doc($others_doc)
	 {
		 $this->others_doc = $others_doc;
	 }

	 public function getNew_Employee_Details_Id()
	 {
		 return $this->new_employee_details_id;
	 }
	 
	 public function setNew_Employee_Details_Id($new_employee_details_id)
	 {
		 $this->new_employee_details_id = $new_employee_details_id;
	 }
	 
}