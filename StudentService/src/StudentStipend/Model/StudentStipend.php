<?php

namespace StudentStipend\Model;

class StudentStipend {

    protected $id;
    protected $student_id;
    protected $year;
    protected $month;
    protected $stipend;
    protected $h_r;
    protected $ebill;
    protected $net_amount;

    public function getId() {return $this->id;}

    public function setId($id) {$this->id = $id;}

    public function getStudent_Id() {return $this->student_id;}

    public function setStudent_Id($student_id) {$this->student_id = $student_id;}

    public function getYear() {return $this->year;}

    public function setYear($year) {$this->year = $year;}

    public function getMonth() {return $this->month;}

    public function setMonth($month) {$this->month = $month;}

    public function getStipend() {return $this->stipend;}

    public function setStipend($stipend) {$this->stipend = $stipend;}

    public function getH_R() {return $this->h_r;}

    public function setH_R($h_r) {$this->h_r = $h_r;}

    public function getEbill() {return $this->ebill;}

    public function setEbill($ebill) {$this->ebill = $ebill;}

    public function getNet_Amount() {return $this->net_amount;}

    public function setNet_Amount($net_amount) {$this->net_amount = $net_amount;}
}
