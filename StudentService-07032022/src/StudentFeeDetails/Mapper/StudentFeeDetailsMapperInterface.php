<?php

namespace StudentFeeDetails\Mapper;

interface StudentFeeDetailsMapperInterface {
    public function getStudentDetailsByID($table_name, $user_name);

    public function listStudentFeeList($student_id);
}
