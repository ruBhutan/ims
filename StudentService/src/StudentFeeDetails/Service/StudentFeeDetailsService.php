<?php

namespace StudentFeeDetails\Service;

use StudentFeeDetails\Mapper\StudentFeeDetailsMapperInterface;

class StudentFeeDetailsService implements StudentFeeDetailsServiceInterface {

    protected $mapper;

    public function __construct(StudentFeeDetailsMapperInterface $mapper) {
        $this->mapper = $mapper;
    }

    public function getStudentDetailsByID($table_name, $user_name) {
        return $this->mapper->getStudentDetailsByID($table_name, $user_name);
    }

    public function listStudentFeeList($student_id) {
        return $this->mapper->listStudentFeeList($student_id);
    }
}
