<?php

namespace JobPortal\Model;

class ApplicantMarks
{
	protected $id;
	protected $x_english;
	protected $x_sub1_mark;
	protected $x_sub2_mark;
	protected $x_sub3_mark;
	protected $x_sub4_mark;
	protected $xll_english;
	protected $xll_sub1_mark;
	protected $xll_sub2_mark;
	protected $xll_sub3_mark;
	protected $job_applicant_id;
	protected $last_updated;

	  
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	 public function getX_English()
	 {
		 return $this->x_english;
	 }
	 
	 public function setX_English($x_english)
	 {
		 $this->x_english = $x_english;
	 }

	 public function getX_Sub1_Mark()
	 {
		 return $this->x_sub1_mark;
	 }
	 
	 public function setX_Sub1_Mark($x_sub1_mark)
	 {
		 $this->x_sub1_mark = $x_sub1_mark;
	 }


	 public function getX_Sub2_Mark()
	 {
		 return $this->x_sub2_mark;
	 }
	 
	 public function setX_Sub2_Mark($x_sub2_mark)
	 {
		 $this->x_sub2_mark = $x_sub2_mark;
	 }

	 public function getX_Sub3_Mark()
	 {
		 return $this->x_sub3_mark;
	 }
	 
	 public function setX_Sub3_Mark($x_sub3_mark)
	 {
		 $this->x_sub3_mark = $x_sub3_mark;
	 }

	 public function getX_Sub4_Mark()
	 {
		 return $this->x_sub4_mark;
	 }
	 
	 public function setX_Sub4_Mark($x_sub4_mark)
	 {
		 $this->x_sub4_mark = $x_sub4_mark;
	 }


	 public function getXll_English()
	 {
		 return $this->xll_english;
	 }
	 
	 public function setXll_English($xll_english)
	 {
		 $this->xll_english = $xll_english;
	 }

	 public function getXll_Sub1_Mark()
	 {
		 return $this->xll_sub1_mark;
	 }
	 
	 public function setXll_Sub1_Mark($xll_sub1_mark)
	 {
		 $this->xll_sub1_mark = $xll_sub1_mark;
	 }

	 public function getXll_Sub2_Mark()
	 {
		 return $this->xll_sub2_mark;
	 }
	 
	 public function setXll_Sub2_Mark($xll_sub2_mark)
	 {
		 $this->xll_sub2_mark = $xll_sub2_mark;
	 }


	 public function getXll_Sub3_Mark()
	 {
		 return $this->xll_sub3_mark;
	 }
	 
	 public function setXll_Sub3_Mark($xll_sub3_mark)
	 {
		 $this->xll_sub3_mark = $xll_sub3_mark;
	 }
	 	 
	 
	 public function getJob_Applicant_Id()
	 {
		return $this->job_applicant_id;
	 }
	
	 public function setJob_Applicant_Id($job_applicant_id)
	 {
		$this->job_applicant_id = $job_applicant_id;
	 }


	 public function getLast_Updated()
	 {
		return $this->last_updated;
	 }
	
	 public function setLast_Updated($last_updated)
	 {
		$this->last_updated = $last_updated;
	 }
}