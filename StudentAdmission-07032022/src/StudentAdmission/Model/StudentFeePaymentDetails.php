<?php

namespace StudentAdmission\Model;

class StudentFeePaymentDetails {

  protected $id;
  protected $student_fees_details_id;
  protected $amount;
  protected $payment_type;
  protected $cheque_no;
  protected $dd_no;
  protected $status;
  protected $created_at;
  protected $updated_at;

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getStudent_Fees_Details_Id() {
    return $this->student_fees_details_id;
  }

  public function setStudent_Fees_Details_Id($student_fees_details_id) {
    $this->student_fees_details_id = $student_fees_details_id;
  }

  public function getAmount() {
    return $this->amount;
  }

  public function setAmount($amount) {
    $this->amount = $amount;
  }

  public function getPayment_Type() {
    return $this->payment_type;
  }

  public function setPayment_Type($payment_type) {
    $this->payment_type = $payment_type;
  }
  
  public function getCheque_No() {
    return $this->cheque_no;
  }

  public function setCheque_No($cheque_no) {
    $this->cheque_no = $cheque_no;
  }

  public function getDd_No() {
    return $this->dd_no;
  }

  public function setDd_No($dd_no) {
    $this->dd_no = $dd_no;
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
