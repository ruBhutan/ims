<?php

namespace StudentAdmission\Model;

class StudentFeeStructure
{

    protected $id;
    protected $student_fee_category_id;
    protected $fees;
    protected $programmes_id;
    protected $financial_year;
    protected $organisation_id;
    protected $created_at;
    protected $updated_at;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getStudent_Fee_Category_Id() {
        return $this->student_fee_category_id;
    }

    public function setStudent_Fee_Category_Id($student_fee_category_id) {
        $this->student_fee_category_id = $student_fee_category_id;
    }

    public function getFees() {
        return $this->fees;
    }
    
    public function setFees($fees) {
        $this->fees = $fees;
    }
    
    public function getProgrammes_Id() {
        return $this->programmes_id;
    }

    public function setProgrammes_Id($programmes_id) {
        $this->programmes_id = $programmes_id;
    }
    
    public function getFinancial_Year() {
        return $this->financial_year;
    }
    
    public function setFinancial_Year($financial_year) {
        $this->financial_year = $financial_year;
    }
    
    public function getOrganisation_Id() {
        return $this->organisation_id;
    }

    public function setOrganisation_Id($organisation_id) {
        $this->organisation_id = $organisation_id;
    }

    public function getCreated_At() {
        return $this->created_at;
    }

    public function setCreated_At($created_at) {
        $this->created_at = $created_at;
    }

    public function getUpdated_At() {
        return $this->updated_at;
    }

    public function setUpdated_At($updated_at) {
        $this->updated_at = $updated_at;
    }

}
