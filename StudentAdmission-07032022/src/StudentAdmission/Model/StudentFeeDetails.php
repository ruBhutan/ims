<?php

namespace StudentAdmission\Model;

class StudentFeeDetails
{

    protected $id;
    protected $student_fee_structure_id;
    protected $student_fee_category_id;
    protected $student_id;
    protected $organisation_id;
    protected $semester_id;
    protected $year_id;
    protected $financial_year;
    protected $due_date;
    protected $amount;
    protected $status;
    protected $created_at;
    protected $updated_at;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getStudent_Fee_Structure_Id() {
        return $this->student_fee_structure_id;
    }

    public function setStudent_Fee_Structure_Id($student_fee_structure_id) {
        $this->student_fee_structure_id = $student_fee_structure_id;
    }
    
    public function getStudent_Fee_Category_Id() {
        return $this->student_fee_category_id;
    }

    public function setStudent_Fee_Category_Id($student_fee_category_id) {
        $this->student_fee_category_id = $student_fee_category_id;
    }

    public function getStudent_Id() {
        return $this->student_id;
    }

    public function setStudent_Id($student_id) {
        $this->student_id = $student_id;
    }
    
    public function getOrganisation_Id() {
        return $this->organisation_id;
    }

    public function setOrganisation_Id($organisation_id) {
        $this->organisation_id = $organisation_id;
    }
    
    public function getSemester_Id() {
        return $this->semester_id;
    }

    public function setSemester_Id($semester_id) {
        $this->semester_id = $semester_id;
    }
    
    public function getYear_Id() {
        return $this->year_id;
    }

    public function setYear_Id($year_id) {
        $this->year_id = $year_id;
    }

    public function getFinancial_Year() {
        return $this->financial_year;
    }

    public function setFinancial_Year($financial_year) {
        $this->financial_year = $financial_year;
    }
    
    public function getAmount() {
        return $this->amount;
    }
    
    public function setAmount($amount) {
        $this->amount = $amount;
    }
    
    public function getDue_Date() {
        return $this->due_date;
    }
    
    public function setDue_Date($due_date) {
        $this->due_date = $due_date;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function setStatus($status) {
        $this->status = $status;
    }
    
    public function getCreated_at() {
        return $this->created_at;
    }

    public function setCreated_at($created_at) {
        $this->created_at = $created_at;
    }

    public function getUpdated_at() {
        return $this->updated_at;
    }

    public function setUpdated_at($updated_at) {
        $this->updated_at = $updated_at;
    }

}
