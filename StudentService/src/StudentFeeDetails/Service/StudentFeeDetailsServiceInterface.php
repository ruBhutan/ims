<?php

namespace StudentFeeDetails\Service;

interface StudentFeeDetailsServiceInterface {

    public function getStudentDetailsByID($table_name, $user_name);

    public function listStudentFeeList($student_id);
}
