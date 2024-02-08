<?php

namespace StudentAdmission\Model;

class StudentFeeCategory
{

    protected $id;
    protected $fee_category;
    protected $remarks;
    protected $organisation_id;
    protected $created_at;
    protected $updated_at;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getFee_Category() {
        return $this->fee_category;
    }

    public function setFee_Category($fee_category) {
        $this->fee_category = $fee_category;
    }

    public function getRemarks() {
        return $this->remarks;
    }
    
    public function setRemarks($remarks) {
        $this->remarks = $remarks;
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
